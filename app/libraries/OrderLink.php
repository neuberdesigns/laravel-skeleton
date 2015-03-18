<?php
class OrderLink {
	public static function make($label, $field=null){
		if( empty($field) ){
			$field = strtolower( str_replace(' ', '_', $label) );
		}
		
		$segs = Request::segments();
		$direction = strtolower( end($segs) );
		
		if( $direction=='desc' ){
			$direction = 'asc';
			$caret = '';
		}else{
			$caret = 'caret-top';
			$direction = 'desc';
		}
		
		$page = Input::get('page');
		if( !empty($page) ){
			$page = '?page='.$page;
		}
		
		$link = Request::root().BasePath::getPath('listagem/'.$field.'/'.$direction.$page);		
		return '<a href="'.$link.'">'.$label.' <span class="caret '.$caret.'"></span></a>';
	}
}