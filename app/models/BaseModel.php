<?php
class BaseModel extends Eloquent {
	public function scopeByUser($q, $id){
		return $q->where('usuario_id', '=', $id);
	}
	
	public function scopeAsc($q, $column='nome'){
		return $q->orderBy($column, 'asc');
	}
	
	public function scopeDesc($q, $column='nome'){
		return $q->orderBy($column, 'desc');
	}
} 
