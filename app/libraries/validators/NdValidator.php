<?php
namespace App\Extension\Validation;
class NdValidator extends \Illuminate\Validation\Validator {

    public function validatePhone($attribute, $value, $parameters){
        return preg_match('/[0-9]{4,5}\-?[0-9]{4}//', $value)>0;
    }
    
    public function validatePhoneArea($attribute, $value, $parameters){
        return preg_match('/\([0-9]{2}\)\s?[0-9]{4,5}\-?[0-9]{4}/', $value)>0;
    }
    
    public function validateHex($attribute, $value, $parameters){
        return preg_match("/^#?([a-fA-F0-9]{3,6})$/", $value)>0;
    }
    
    /*protected function replacePhone($message, $attribute, $rule, $parameters){
	    return str_replace(':foo', $parameters[0], $message);
	}*/
	
	protected function replacePhoneArea($message, $attribute, $rule, $parameters){
        //var_dump($message, $attribute, $rule, $parameters);
        //exit;
	    return str_replace(':attribute', 'ops!', $message);
	}

}
