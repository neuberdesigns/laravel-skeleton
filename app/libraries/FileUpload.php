<?php

class FileUpload {
	public static function make($fieldname, $type='upload', $index=null){
		if( Input::hasFile($fieldname) ){
			$file = Input::file($fieldname);
			
			if( !is_null($index) && is_numeric($index) )
				$file = $file[$index];
			
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
	
	public static function fromUrl($url, $type='upload'){
		$base = public_path().'/';
		$dest = $type=='upload' ? $base.UPLOAD_DIR :  $base.UPLOAD_TEMP_DIR;
		$newName = uniqid();
		
		$fileinfo = getimagesize($url);
		if( $fileinfo !== false ){
			if( $fileinfo[2]==IMG_GIF || $fileinfo[2]==IMG_JPG || $fileinfo[2]==IMG_PNG ){
				$ext = image_type_to_extension($fileinfo[2], true);
				$newName .= $ext;
				
				if( copy($url, $dest.$newName) ){					
					return $newName;
				}
			}
		}
		
		return false;
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
	
	public static function getTim( $filename, $width=null, $height=null, $zc=2, $attribs=array(), $url=true ){
		$tim = new Timthumb();
		$tim->setZc($zc);
		$tim->setWidth($width);
		$tim->setHeight($height);
		$tim->setBase(URL::to('').'/');
		
		$alt = isset($attribs['alt']) ? $attribs['alt'] : null;
		$thumb = $tim->thumb(self::get($filename), null, null, false);
		
		if( $url )
			return $thumb;
		else
			return HTML::image( $thumb, $alt, $attribs );
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
