<?php
class ObjectSearch {
	public static function arraySearch($objectArray, $property, $value){
		foreach( $objectArray as $k=>$o ){
			if( $o->$property == $value ){
				return true;
			}
		}
		
		return false;
	}
}