<?php

class FileUpload {
	const UPLOAD_DIR = 'upload/';
	const TEMP_DIR = 'upload/temp/';
	const MISSING_IMG = '0-no-image.jpg';
	
	const DESTINATION_FINAL = 0;
	const DESTINATION_TEMP = 1;
	
	protected $uploadPath = null;
	protected $tempPath = null;
	
	protected $queue = array();
	protected $error = null;
	
	protected $uploaded = false;
	protected $uploadedName = null;
	protected $fieldName = null;
	protected $destinationType = 1;
	protected $destinationDir = null;
	
	
	
	public static function make(){
		$instance = new FileUpload();
		return $instance;
	}
	
	protected function __construct(){
		$base = public_path().'/';
		$this->uploadPath = $base.UPLOAD_DIR;
		$this->tempPath = $base.UPLOAD_TEMP_DIR;
	}
	
	public function field($name){
		$this->fieldName = $name;
		return $this;
	}
	
	public function destination($type){
		$this->destinationType = $type;
		$this->destinationDir = self::DESTINATION_TEMP ? self::UPLOAD_TEMP_DIR : self::UPLOAD_DIR;
		return $this;
	}
	
	
	public function getDestinationPath(){
		return ($this->destinationType==self::DESTINATION_FINAL ? $this->getUploadPath() : $this->getTempPath() )
	}
	
	public function isUploaded(){
		return $this->uploaded;
	}
	
	public function getUploadedName(){
		return $this->uploadedName;
	}
	
	public function getTempPath($withUploadedFile=false){
		return $this->tempPath.($withUploadedFile?$this->getUploadedName():'');
	}
	
	public function getUploadPath($withUploadedFile=false){
		return $this->uploadPath.($withUploadedFile?$this->getUploadedName():'');
	}
	
	public function receive(){
		if( Input::hasFile($this->fieldName) ){
			$file = Input::file($this->fieldName);
						
			$ext = $file->getClientOriginalExtension();
			$dest = ;
			
			$newName = uniqid().'.'.$ext;
			
			try {
				$file->move( $this->getDestinationPath(), $newName );
				$this->uploadedName = $newName;
				$this->uploaded = true;
			}catch(Exception $exception){
				$this->error = $exception;
				$this->uploaded = true;
			}
		}
		
		return $this->isUploaded();
	}
	
	//TODO refactor to use class insted static things
	private static function fromUrl($url, $type='upload'){
		$base = public_path().'/';
		$dest = $type=='upload' ? $base.UPLOAD_DIR :  $base.UPLOAD_TEMP_DIR;
		$newName = uniqid();
		$fileinfo = false;
		try {
			$fileinfo = getimagesize($url);
		} catch (Exception $e) {
			error_log('FileUpload: finfo error: '.$e->getMessage().' on line: '.$e->getLine() );
		}
		
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
	
	//TODO refactor to use class insted static things
	private static function batch($files, $type='upload'){
		$list = array();
		
		foreach ($files as $key => $value) {
			$list[] = self::make($value, $type);
		}
		
		return $list;
	}
	
	public static function move(){
		$moved = false;
		$src = $this->getTempPath(true);
		$target = $this->getUploadPath(true);
		
		if( $this->isUploaded() ){
			if( is_file($src) ){
				$moved = rename($src, $target);
			}
		}
		
		return $moved;
	}
	
	//TODO refactor to use class insted static things
	private static function moveBatch($filenames){
		foreach ($filenames as $key => $value) {
			$src = public_path().'/'.UPLOAD_TEMP_DIR.$value;
			$target = public_path().'/'.UPLOAD_DIR.$value;
			
			if( is_file($src) )
				rename($src, $target);
		}
	}
	
	public function get(){
		$target = $this->getUploadPath(true);
		
		if( is_file($target) )
			return URL::asset( self::UPLOAD_DIR.$this->getUploadedName() );
		else
			return URL::asset( self::UPLOAD_DIR.self::MISSING_IMG );
	}
	
	public function getTim($width=null, $height=null, $zc=2, $attribs=array(), $url=true ){
		$tim = new Timthumb();
		$tim->setZc($zc);
		$tim->setWidth($width);
		$tim->setHeight($height);
		$tim->setBase(URL::to('').'/');
		
		$alt = isset($attribs['alt']) ? $attribs['alt'] : null;
		$thumb = $tim->thumb($this->get(), null, null, false);
		
		if( $url )
			return $thumb;
		else
			return HTML::image( $thumb, $alt, $attribs );
	}
	
	
	public function delete(){
		$removed = false;
		$dirs = array(
			$this->getUploadPath(true),
			$this->getTempPath(true),
		);
		
		foreach($dirs as $target ){
			if( is_file($target) ){
				 $removed = unlink($target);
				 break;
			}
		}
		
		return $removed;
	}
	
	private function __clone(){}
	private function __wakeup(){}
}
