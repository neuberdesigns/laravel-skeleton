<?php

class Json {
	private $properties = array();
	
	public function __construct(){
		$this->add('response', 'Calix');
		$this->add('msgType', 'error');
		$this->add('status', 400);
		$this->add('autoclear', true);
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
	
	public function setSuccess($response=null){
		$this->msgType = 'success';
		$this->status = 200;
		$this->response = $response;
	}
	
	public function setFail($response=null){
		$this->msgType = 'error';
		$this->status = 400;
		$this->response = $response;
	}
	
	protected function getProperties(){
		return $this->properties;
	}
	
	protected function setProperties($props){
		$this->properties = $props;
	}
	
	protected function check($prop){
		return array_key_exists($prop, $this->getProperties());
	}
	
	public function add($prop, $value){
		if( !$this->check($prop) ){
			$this->properties[$prop] = $value;
			return true;
		}else{
			return false;
		}
	}
	
	public function addBatch($properties){
		foreach( $properties as $prop=>$val ){
			$this->add($prop, $val);
		}
	}
	
	public function getJson(){
		return json_encode($this->getProperties());
	}
	
	public function make(){
		return Response::make($this->getJson(), $this->status);
		//return Response::json($this->getProperties(), $this->status);
	}
	
	public function makeJson(){
		//return Response::make($this->getJson(), $this->status);
		return Response::json( $this->getProperties() );
	}
	
	/**
	 * Import a json string,, all previous properties will be lost
	 * @param  string $json A json string
	 * @return void
	 */
	public function import($json){
		$this->setProperties( json_decode($json, true) );
	}
	
	public function __toString(){
		return $this->getJson();
	}
}
