<?php
class BasePath {
	public static function getPath($to='', $segments=2){
		$to = trim($to);
		$segs = array();
		$uri;
		for ($i=1; $i <= $segments ; $i++) { 
			$segs[] = Request::segment($i);
		}
		
		$uri = '/'.implode('/', $segs);
		if( !empty($to) )
			$uri .= '/'.$to;
		
		return $uri;
	}
} 
