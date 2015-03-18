<?php
/**
 * author: Neuber Oliveira <neuberdesigns@hotmail.com>
 */
class DateTimeConvert {
	
	public static function toMysql($date, $from='br', $sep='/'){
		$method = 'from'.ucfirst($from);
		return self::$method($date, $sep);
	}
	
	
	public static function toBr($dateRaw, $sep='/'){
		$hasTime = false;
		
		$fullPat = '';
		$datePat = 'd'.$sep.'m'.$sep.'Y';
		$timePat = 'H:i';
		$date = '';
		$time = '';
		$dateTime = explode(' ', $dateRaw);
		
		if( isset($dateTime[0]) )
			$fullPat = $datePat;
		
		
		if( isset($dateTime[1]) )
			$fullPat .= $timePat;
		
		return date($fullPat, strtotime($dateRaw));
	}
	
	
	//From 00/00/0000 00:00
	protected static function fromBr($dateRaw, $sep){
		$newDate = array();
		$newTime = '';
		$dateTime = explode(' ', $dateRaw);
		
		if( isset($dateTime[0]) )
			$newDate = explode($sep, $dateTime[0]);
		
		if( isset($dateTime[1]) )
			$newTime = ' '.$dateTime[1];
		
		return implode('-', array_reverse($newDate)).$newTime;
	}
	
} 
