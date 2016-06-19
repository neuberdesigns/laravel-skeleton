<?php
class FieldInfo {
	protected $table;
	protected $database;
	protected $name;
	protected $type;
	protected $options = array();
	protected $size = 0;
	protected $null;
	protected $key;
	protected $default;
	protected $extra;
	
	public function __construct($col/*, $db*/){
		/*$this->database = $db;*/
		$this->setName($col->Field);
		$this->setType($col->Type);
		$this->setNull($col->Null);
		$this->setKey($col->Key);
		$this->setDefault($col->Default);
		$this->setExtra($col->Extra);
	}
	
	public function typeOf($type){
		$result = false;
		if(is_string($type)){
			$result = $this->type == strtolower($type);
		}else if(is_array($type)){
			$matches = 0;
			foreach($type as $typecheck){
				if($this->typeOf($typecheck)){
					$matches ++;
					break;
				}
			}
			$result = $matches>0;
		}
		
		return $result;
	}
	
	
	  ////////////////////////////////////////////
	 ////            GETTERS                /////
	////////////////////////////////////////////
	/**
	 * Gets the value of table.
	 *
	 * @return mixed
	 */
	public function getTable(){
		return $this->table;
	}

	/**
	 * Gets the value of database.
	 *
	 * @return mixed
	 */
	public function getDatabase(){
		return $this->database;
	}

	/**
	 * Gets the value of name.
	 *
	 * @return mixed
	 */
	public function getName(){
		return $this->name;
	}
	
	public function getDisplayName(){
		return ucfirst(str_replace('_', ' ', $this->getname()));
	}

	/**
	 * Gets the value of type.
	 *
	 * @return mixed
	 */
	public function getType(){
		return $this->type;
	}
	
	public function getOptions(){
		return $this->options;
	}
	
	public function getSize(){
		return $this->size;
	}
	
	public function hasOptions($depthCheck=false){
		$has = false;
		if(!$depthCheck)
			$this->typeOf('enum');
		else{
			$has = $this->typeOf('enum') && count($this->getOptions())>0;
		}
		
		return $has;
	}

	/**
	 * Gets the value of null.
	 *
	 * @return mixed
	 */
	public function isNull(){
		return $this->null == 'YES';
	}

	/**
	 * Gets the value of key.
	 *
	 * @return mixed
	 */
	public function getKey(){
		return $this->key;
	}
	
	public function isPrimaryKey(){
		return $this->getKey()=='PRI';
	}
	
	public function isMultiKey(){
		return $this->getKey()=='MUL';
	}

	/**
	 * Gets the value of default.
	 *
	 * @return mixed
	 */
	public function getDefault(){
		return $this->default;
	}

	/**
	 * Gets the value of extra.
	 *
	 * @return mixed
	 */
	public function getExtra(){
		return $this->extra;
	}
	
	public function isAutoIncrement(){
		return $this->extra == 'auto_increment';
	}
	
	
	
	  ////////////////////////////////////////////
	 ////            SETTERS                /////
	////////////////////////////////////////////
	/**
	 * Sets the value of table.
	 *
	 * @param mixed $table the table
	 *
	 * @return self
	 */
	protected function setTable($table){
		$this->table = $table;

		return $this;
	}
	
	/**
	 * Sets the value of name.
	 *
	 * @param mixed $name the name
	 *
	 * @return self
	 */
	protected function setName($name){
		$this->name = $name;

		return $this;
	}

	/**
	 * Sets the value of database.
	 *
	 * @param mixed $database the database
	 *
	 * @return self
	 */
	protected function setDatabase($database){
		$this->database = $database;

		return $this;
	}

	/**
	 * Sets the value of type.
	 *
	 * @param mixed $type the type
	 *
	 * @return self
	 */
	protected function setType($type){
		$matches = array();
		$result = preg_match('/\((.*)\)/', $type, $matches);
		$fullMatch = isset($matches[0]) ? $matches[0] : null;
		$parentesisValue = isset($matches[1]) ? $matches[1] : null;
		
		if($result>0){
			$type = str_replace($fullMatch, '', $type);
			if($parentesisValue){
				if(is_numeric($parentesisValue)){
					$this->setSize($parentesisValue);
				}else{
					$this->setOptions($parentesisValue);
				}
			}
		}
		$this->type = strtolower($type);
		
		return $this;
	}
	
	protected function setOptions($value){
		if( is_string($value) ){
			$options = explode(',', $value);
			foreach($options as $opt){
				$this->options[] = preg_replace("/^'|'$/", '', $opt);
			}
		}else if(is_array($value)){
			$this->options = $value;
		}

		return $this;
	}
	
	protected function setSize($size){
		$this->size = (int)$size;
	} 

	/**
	 * Sets the value of null.
	 *
	 * @param mixed $null the null
	 *
	 * @return self
	 */
	protected function setNull($null){
		$this->null = $null=='NULL';

		return $this;
	}

	/**
	 * Sets the value of key.
	 *
	 * @param mixed $key the key
	 *
	 * @return self
	 */
	protected function setKey($key){
		$this->key = $key;

		return $this;
	}

	/**
	 * Sets the value of default.
	 *
	 * @param mixed $default the default
	 *
	 * @return self
	 */
	protected function setDefault($default){
		$this->default = $default=='NULL'?null:$default;

		return $this;
	}

	/**
	 * Sets the value of extra.
	 *
	 * @param mixed $extra the extra
	 *
	 * @return self
	 */
	protected function setExtra($extra){
		$this->extra = $extra;

		return $this;
	}
}
