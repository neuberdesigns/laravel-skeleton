<?php
class SEOHelper {
	public static function get($object_id=null, $type=null, $id=null){
		$seo;
		
		if( !empty($id) ){
			$seo = Seo::find( (int)$id );
		}else{
			$seo = Seo::where('object_id', '=', $object_id)->where('type', '=', $type)->first();
		}
		
		if( !empty($seo) ){
			View::share('metaTitle', $seo->title);
			View::share('metaKeywords', $seo->keywords);
			View::share('metaDescription', $seo->description);
		}
	}
}
