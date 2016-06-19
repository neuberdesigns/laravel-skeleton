<?php
class DatabaseInfo {
	protected $tables = array();
	public $name;
	
	public function __construct(){
		$this->name = DB::getDatabaseName();
		$tables = DB::select('SHOW TABLES');
		
		foreach( $tables as $tableObj ){
			$tableIndex = 'Tables_in_'.$this->getName();
			$tableName = $tableObj->$tableIndex;
			$table = new TableInfo($tableName, $this);
			$this->tables[] = $table;
		}
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getTables(){
		return $this->tables;
	}
	
	public function find($tablename){
		$tableResult = null;
		foreach($this->getTables() as $table){
			if($table->getName()==$tablename){
				$tableResult = $table;
				break;
			}
		}
		
		return $tableResult;
	}
	
}
