<?php
class SkelTemplate {
	protected $targetName;
	protected $targetPath;
	protected $templateName;
	protected $templatesDir;
	protected $replacements = array();
	
	protected $replacedText = null;
	protected $destinationFile = null;
	
	public function __construct($templateName){
		$this->templatesDir = __DIR__.'/templates/';
		$name = $templateName.'.txt';
		
		if( is_file($this->templatesDir.$name)){
			$this->targetName = $name;
		}else{
			throw new Exception("The template file '$name' not exists", 1);
		}
	}
	
	public static function make($templateName){
		return new SkelTemplate($templateName);
	}
	
	public function mark($mark, $value){
		$this->replacements['{'.$mark.'}'] = $value;
		return $this;
	}
	
	
	public function replace(){
		$content = file_get_contents($this->templatesDir.$this->targetName);
		$marks = array_keys($this->replacements);
		$values = array_values($this->replacements);
		
		$this->replacedText = str_replace($marks, $values, $content);
		return $this;
	}
	
	public function save($targetPath){
		$this->destinationFile = $targetPath;
		$this->replace();
		file_put_contents($targetPath, $this->replacedText);
		return $this;
	}
	
	public function getOutput(){
		return $this->replacedText;
	}
}
