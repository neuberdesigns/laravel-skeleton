<?php
class StatesCities {
	protected $json;
	
	public static function make(){
		return new StatesCities();
	}
	
	public function __construct(){
		$raw = file_get_contents(__DIR__.'/StatesCitiesSource.json');
		$this->json = json_decode($raw);
	}
	
	public function fetchState($term){
		$found = null;
		foreach($this->json->estados as $state){
			if( (is_numeric($term) && $state->id==$term) || ($state->sigla==$term || $state->nome==$term) ){
				$found = $state;
				break;
			}
		}
		
		return $found;
	}
	
	public function listStates($byName=false, $simple=true){
		$list = array(trans('admin.select'));
		$field = $byName ? 'nome' : 'sigla';
		foreach($this->json->estados as $state){
			$value = $state->$field;
			if($simple)
				$list[] = $value;
			else
				$list[$value] = $value;
		}
		
		return $list;
	}
	
	public function listCities($term){
		$list = array();
		$state = $this->fetchState($term);
		
		if($state){
			foreach($state->cidades as $city){
				$list[] = $city;
			}
		}
		
		return $list;
	}
	
	public function checkCity($term, $state){
		$found = null;
		foreach($state->cidades as $city){
			if( $city==$term ){
				$found = true;
				break;
			}
		}
		
		return $found;
	}
}
