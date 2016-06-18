<?php
class InputFactory {
	public static function create($type){
		$input = 'Input'.studly_case($type);
		if(class_exists($input)){
			return new $input();
		}
		else {
			throw new Exception("Invalid input type given");
		}
	}
}
