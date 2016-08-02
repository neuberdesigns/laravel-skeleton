<?php

class Json {
	protected $status = 400;
	private $properties = array();
	
	public function __construct(){
	}
	
	public function __get($prop){
		if( $this->check($prop) )
			return $this->properties[$prop];
		else
			return null;
	}
	
	public function __set($prop, $value){
		if( $this->check($prop) )
			$this->properties[$prop] = $value;
	}
	
	public function success($status=200){
		$this->status = $status;
		return $this;
	}
	
	public function fail($errors=null, $status=400, $formErrorFormat=false){
		$this->status = $status;
		if(!empty($errors)){
			if(is_array($errors))
				$this->add('errors', $errors);
			else
				if($formErrorFormat)
					$this->add('errors', array('default'=>array($errors)));
				else
					$this->add('errors', array($errors));
		}
		
		return $this;
	}
	
	public function getProperties(){
		return $this->properties;
	}
	
	public function setProperties($props){
		$this->properties = $props;
		return $this;
	}
	
	public function check($prop){
		return array_key_exists($prop, $this->getProperties());
	}
	
	public function add($prop, $value){
		if( !$this->check($prop) ){
			$this->properties[$prop] = $value;
		}		
		return $this;
	}
	
	public function finish(){
		return Response::make($this->getProperties(), $this->status);
	}
	
	public function __toString(){
		//return json_encode($this->getProperties());
		return $this->finish();
	}
}
