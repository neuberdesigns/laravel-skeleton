<?php
class BaseModel extends Eloquent {
	public function langs(){
		return $this->hasMany($this->getLangRelationName());
	}
	
	public function scopeByUser($q, $id){
		return $q->where('usuario_id', '=', $id);
	}
	
	public function scopeAsc($q, $column='name'){
		return $q->orderBy($column, 'asc');
	}
	
	public function scopeDesc($q, $column='name'){
		return $q->orderBy($column, 'desc');
	}
	
	public function scopeLang($q){
		return $q->with($relationName, "$relationName.lang");
	}
	
	public function translations(){
		return $this->hasMany($this->getLangRelationName());
	}
	
	public function translation(){
		return $this->hasOne($this->getLangRelationName());
	}
	
	public function scopeByLang($q, $langId){
		$relationName = $this->getLangRelationName();
		$fk = $this->getLangRelationFK();
		
		return $q->with(array('translation'=>function($sq) use($langId, $fk){
			return $sq->where('lang_id', '=', $langId);
		}) );
	}
	
	public function getLangRelationName(){
		$relationName = studly_case($this->table.'_translation');
		return $relationName;
	}
	
	public function getTranslationTableName(){
		$transname = $this->table.'_translation';
		return $transname;
	}
	
	public function getLangRelationFK(){
		return $this->table.'_'.$this->primaryKey;
	}
} 
