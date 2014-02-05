<?php
class NestedSet extends HierarchyInterface {
	
	public function scopeById($query, $id){
		return $query->where('parent.id', '=', $id);
	}
	
	public function scopeByName($query, $name){
		return $query->where('parent.name', '=', $name);
	}
	
	public function scopeHierarchical($query){
		return $query->select('node.*')
			->from(DB::raw($this->table.' AS node , '.$this->table.' AS parent') )
			->whereRaw('node.lft BETWEEN parent.lft AND parent.rgt')
			->orderBy('node.lft', 'ASC');
	}
	
	public function scopeWithDepth($query){
		return $query->select( DB::raw('node.*, (COUNT(parent.id) - 1) AS depth') )
			->groupBy('node.id');
	}
	
	public function scopeConcat($query, $separator='-'){
		return $query->select( DB::raw('node.*, CONCAT( REPEAT("'.$separator.'", COUNT(parent.id) - 1), node.name) AS name') )
			->groupBy('node.id');
	}
	
	public function addNext($parentNode, $nodeId){
		$parent = self::find($parentNode);
		$node = self::find($nodeId);
		$transaction = false;
		
		if( !empty($parent) && !empty($node) ){
			$aRight = $parent->rgt;
			$aLeft = $parent->lft;
			
			$transaction = DB::transaction(function() use ($aLeft, $aRight, $node){
				$update1 = self::where('lft', '>', $aRight)
					->update( array('lft'=>DB::raw('lft+2') ) );
				
				$update2 = self::where('rgt', '>', $aRight)
					->update( array('rgt'=>DB::raw('rgt+2') ) );
					
				if( $update1 || $update2 ){
					$fields = array(
						'lft'=>$aRight + 1,
						'rgt'=>$aRight + 2,
					);
					
					$next = $node->update($fields);
					
					return $next;
				}
				
				return false;
			});
		}
		
		return $transaction;
	}
	
	public function appendTo($parentNode, $nodeId){
		$parent = self::find($after);
		$node = self::find($nodeId);
		$transaction = false;
		
		if( !empty($parent) && !empty($node) ){
			$aRight = $parent->rgt;
			$aLeft = $parent->lft;
			
			$transaction = DB::transaction(function() use ($aLeft, $aRight, $node){
				
				$update1 = self::where('lft', '>', $aLeft)
					->update( array('lft'=>DB::raw('lft+2') ) );
					
				$update2 = self::where('rgt', '>', $aLeft)
					->update( array('rgt'=>DB::raw('rgt+2') ) );
					
				if( $update1 || $update2 ){
					$fields = array(
						'lft'=>$aLeft + 1,
						'rgt'=>$aLeft + 2,
					);
					
					$append= $node->update($fields);
					
					return $append;
				}
				
				return false;
			});
		}
		
		return $transaction;
	}

	public function deleteNode($id){
		$node = self::find($id);
		$transaction = false;
		
		if( !empty($node) ){
			$nRight = $node->rgt;
			$nLeft = $node->lft;
			$nWidth = $nRight - $nLeft + 1;
			
			$transaction = DB::transaction(function() use ($nLeft, $nRight, $nWidth){
				$delete = self::whereRaw('lft BETWEEN '.$nLeft.' AND '.$nRight)->delete();
				
				if( $delete ){
					$update = self::where('lft', '>', $nRight)
					->where('rgt', '>', $nRight)
					->update( array('lft'=>DB::raw('lft-'.$nWidth), 'rgt'=>DB::raw('rgt-'.$nWidth) ) );
					
					return $update;
				}
				
				return false;
			});
		}
		
		return $transaction;
	}
}

