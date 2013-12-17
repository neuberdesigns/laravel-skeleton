<?php

class FileUpload {
	public static function make($fieldname, $type='upload'){
		if( Input::hasFile($fieldname) ){
			$file = Input::file($fieldname);
			$ext = $file->getClientOriginalExtension();
			
			$base = public_path().'/';
			$dest = $type=='upload' ? $base.UPLOAD_DIR :  $base.UPLOAD_TEMP_DIR;
			
			$newName = uniqid().'.'.$ext;
			
			$file->move( $dest, $newName );
			
			return $newName;
		}else{
			return false;
		}
	}
	
	public static function batch($files, $type='upload'){
		$list = array();
		
		foreach ($files as $key => $value) {
			$list[] = self::make($value, $type);
		}
		
		return $list;
	}
	
	public static function move($filename){
		$src = public_path().'/'.UPLOAD_TEMP_DIR.$filename;
		$target = public_path().'/'.UPLOAD_DIR.$filename;
		
		if( is_file($src) )
			rename($src, $target);
	}
	
	public static function moveBatch($filenames){
		foreach ($filenames as $key => $value) {
			$src = public_path().'/'.UPLOAD_TEMP_DIR.$value;
			$target = public_path().'/'.UPLOAD_DIR.$value;
			
			if( is_file($src) )
				rename($src, $target);
		}
	}
	
	public static function get($filename){
		$target = public_path().'/'.UPLOAD_DIR.$filename;
		if( is_file($target) )
			return URL::asset( UPLOAD_DIR.$filename );
		else
			return URL::asset( UPLOAD_DIR.MISSING_IMG );
	}
	
	public static function getTim( $filename, $width=null, $height=null, $attribs=array() ){
		$tim = new Timthumb();
		$tim->setZc(2);
		$tim->setWidth($width);
		$tim->setHeight($height);
		$tim->setBase(URL::to('').'/');
		
		return HTML::image( $tim->thumb(self::get($filename), null, null, false), null, $attribs );
	}
	
	public static function delete($filename){
		$target = public_path().'/'.UPLOAD_DIR.$filename;
		$targetTemp = public_path().'/'.UPLOAD_TEMP_DIR.$filename;
		
		if( is_file($target) )
			return unlink( $target );
		
		elseif( is_file($targetTemp) )
			return unlink( $targetTemp );
		
		return null;
	}
}