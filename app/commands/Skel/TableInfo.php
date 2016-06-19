<?php
class TableInfo {
	protected $database;
	protected $name;
	protected $fields = array();
	
	public function __construct($tableName, $db){
		$this->name = $tableName;
		$this->database = $db;
		
		$fields = DB::select('SHOW COLUMNS FROM `'.$this->getName().'`');
		foreach($fields as $col){
			$field = new FieldInfo($col, $this->getDatabase());
			$this->fields[] = $field;
		}
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getNameForClass(){
		/*$className = ucwords( str_replace('_', ' ', $this->getName()) );
		$className = str_replace(' ', '', $className);
		return $className;*/
		
		return studly_case($this->getName());
	}
	
	public function getDatabase(){
		return $this->database;
	}
	
	public function getFields(){
		return $this->fields;
	}
	
	public function find($colName){
		$colResult = null;
		foreach($this->getFields() as $col){
			if($col->getName()==$colName){
				$colResult = $col;
				break;
			}
		}
		
		return $colResult;
	}
}
