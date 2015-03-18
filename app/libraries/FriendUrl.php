<?php
class FriendUrl{
	public static function encode($str){		
		return str_replace( array('-', ' '), array('|', '-'), strtolower($str) );
	}
	
	public static function decode($str){
		return str_replace(array('-', '|'), array(' ', '-'), $str);
	}
}