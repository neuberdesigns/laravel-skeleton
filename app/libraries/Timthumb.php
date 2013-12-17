<?php
class Timthumb {
	const NEGATE 		= 1; 	// 1 Negate - Invert colours
	const GRAYSCALE 	= 2; 	// 2 Grayscale - turn the image into shades of grey
	const BRIGHTNESS 	= 3; 	// 3 Brightness - Adjust brightness of image. Requires 1 argument to specify the amount of brightness to add. Values can be negative to make the image darker.
	const CONTRAST 		= 4; 	// 4 Contrast - Adjust contrast of image. Requires 1 argument to specify the amount of contrast to apply. Values greater than 0 will reduce the contrast and less than 0 will increase the contrast.
	const COLORIZE 		= 5; 	// 5 Colorize/ Tint - Apply a colour wash to the image. Requires the most parameters of all filters. The arguments are RGBA
	const EDGE_DETECT	= 6; 	// 6 Edge Detect - Detect the edges on an image
	const EMBOSS 		= 7; 	// 7 Emboss - Emboss the image (give it a kind of depth), can look nice when combined with the colorize filter above.
	const GAUSSIAN_BLUR = 8; 	// 8 Gaussian Blur - blur the image, unfortunately you can't specify the amount, but you can apply the same filter multiple times (as shown in the demos)
	const SELECTIVE_BLUR= 9; 	// 9 Selective Blur - a different type of blur. Not sure what the difference is, but this blur is less strong than the Gaussian blur.
	const MEAN_REMOVAL 	= 10; 	// 10 Mean Removal - Uses mean removal to create a "sketchy" effect.
	const SMOOTH 		= 11; 	// 11 Smooth - Makes the image smoother.

	const CENTER 		= 'c';
	const TOP 			= 't';
	const BOTTOM 		= 'b';
	const LEFT 			= 'l';
	const RIGHT 		= 'r';
	const TOP_RIGHT 	= 'tr';
	const TOP_LEFT 		= 'tl';
	const BOTTOM_RIGHT 	= 'br';
	const BOTTOM_LEFT 	= 'bl';
	
	private $fullUrl;
	private $base = '';
	private $params = '';
	private $tim= 'timthumb-lib.php?';

	private $canvasColor = '#FFFFFF';
	private $zc = 1;
	private $width = null;
	private $height = null;
	private $quality = 80;
	private $transparency = true;
	private $sharpen = false;
	private $filters = array();
	private $alignment = self::CENTER;
	private $paramsMount = array('src'=>'', 'w'=>'', 'h'=>'', 'zc'=>'', 'a'=>'', 'q'=>'', 'f'=>'', 's'=>'', 'cc'=>'', 'ct'=>'');

	public function __construct(){}
	
	/*GETs*/
	public function getFullUrl(){
		return $this->fullUrl;
	}
	
	public function getBase(){
		return $this->base;
	}
	
	public function getTim(){
		return $this->tim;
	}
	
	public function getParamsMount(){
		return $this->paramsMount;
	}
	
	public function getCanvasColor(){
		return $this->canvasColor;
	}
	
	public function getZc(){
		return $this->zc;
	}
	
	public function getWidth(){
		return $this->width;
	}
	
	public function getHeight(){
		return $this->height;
	}
	
	public function getQuality(){
		return $this->quality;
	}
	
	public function getTransparency(){
		return $this->transparency;
	}
	
	public function getSharpen(){
		return $this->sharpen;
	}
	
	public function getFilter(){
		return implode('|', $this->filters);
	}
	
	public function getAlignment(){
		return $this->alignment;
	}
	
	/*SETs*/
	public function setBase($b){
		$this->base = $b;
	}
	
	public function setCanvasColor($cc){
		$this->canvasColor = $cc;
		
		return $this;
	}
	
	public function setZc($zc){
		$this->zc = (int)$zc;
		
		return $this;
	}
	
	public function setWidth($w){
		$this->width = (int)$w;
		
		return $this;
	}
	
	public function setHeight($h){
		$this->height = (int)$h;
		
		return $this;
	}
	
	public function setQuality($q){
		$this->quality = (int)$q;
		
		return $this;
	}
	
	public function setTransparency($t){
		$this->transparency = (bool)$t;
		
		return $this;
	}
	
	public function setSharpen($s){
		$this->sharpen = (bool)$s;
		
		return $this;
	}
	
	public function setAlignment($a){
		$this->alignment = $a;
		
		return $this;
	}
	
	public function setFilter($f){
		$this->filter = $f;
		
		return $this;
	}
	
	public function addFilter($filter, $param=array(), $multiplier=1){
		$multiplier = (int)$multiplier;
		if( !is_array($param) && !empty($param) || is_null($param)  )
			$param = array($param);
		
		array_unshift($param, $filter);
		for ($i=0; $i<$multiplier; $i++) {
			array_push( $this->filters, implode(',', $param) );
		}
		
		return $this;
	}
	
	/*PROTECTED*/
	protected function mount($param, $value){
		$this->paramsMount[$param] = $value;
	}
	
	protected function reset(){
		$this->setFilter(array());
		$this->setSharpen(false);
		$this->setAlignment(self::CENTER);
		$this->setTransparency(false);
	}
	
	protected function buildParams($src, $width, $height){
		$urlPartial = array();
		
		if(empty($width))
			$this->mount('w', $this->getWidth());
		else
			$this->mount('w', $width);

		if(empty($height))
			$this->mount('h', $this->getHeight());
		else
			$this->mount('h', $height);

		$this->mount('src', $src);
		$this->mount('zc', $this->getZc());
		$this->mount('a', $this->getAlignment());
		$this->mount('q', $this->getQuality());
		$this->mount('f', $this->getFilter());
		$this->mount('s', $this->getSharpen());
		$this->mount('cc', $this->getCanvasColor());
		$this->mount('ct', $this->getTransparency());
		
		foreach ($this->getParamsMount() as $p => $v)
			array_push($urlPartial, $p.'='.$v);
		
		return implode('&', $urlPartial);
	}

	/*PUBLIC*/
	public function preConfigure($width, $height, $zc){

	}

	public function thumb($src, $width=null, $height=null, $echo=true){		
		$thumbUrl = $this->getBase().$this->getTim().$this->buildParams($src, $width, $height);
		$this->fullUrl = $thumbUrl;
		
		$this->reset();
		if($echo)
			echo $thumbUrl;
		else
			return $thumbUrl;
	}
}