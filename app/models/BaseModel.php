<?php
class BaseModel extends Eloquent {
	public function scopeAsc($q, $column='name'){
		return $q->orderBy($column, 'asc');
	}
	
	public function scopeDesc($q, $column='name'){
		return $q->orderBy($column, 'desc');
	}
} 
