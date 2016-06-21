<?php
class OrderLink {
	protected $link;
	protected $label;
	protected $field;
	protected $direction;
	protected $orderParamName = 'order';
	protected $directionParamName = 'direction';
	protected $queryString = array();
	
	public function __construct(){
		$this->link = URL::current();
		$this->addParamFromUrl($this->orderParamName);
		$this->addParamFromUrl($this->directionParamName);
		$this->addAll();
	}
	
	public function __toString(){
		return '<a href="'.$this->getUrl().'">'.$this->getLabel().' <span class="caret '.$this->getCarret().'"></span></a>';
	}
	
	public function __get($name){
		$value = null;
		if( $this->hasParam($name) )
			$value = $this->queryString[$name];
		
		return $value;
	}
	
	public static function make($label, $field=null){
		$orderLink = new OrderLink();
		$orderLink->setDirectionFromUrl();
		$orderLink->setLabel($label, $field);
		
		$field = $orderLink->getFieldName();
		$orderLink->addParam('order', $field);
		$orderLink->addParam('direction', $orderLink->getDirection() );
		
		return $orderLink;
	}
	
	public function setLabel($lb, $fd=null){
		$this->label = $lb;
		$this->field = $fd;
	}
	
	public function getLabel(){
		return $this->label;
	}
	
	public function getField(){
		return $this->field;
	}
	
	public function getLink(){
		return $this->link;
	}
	
	public function getUrl(){
		return $this->link.$this->getQueryString();
	}
	
	public function getDirection(){
		return $this->direction;
	}
	
	public function getCarret(){
		$carret = '';
		if( $this->direction=='desc' ){
			$this->direction = 'asc';
			$caret = '';
		}else{
			$caret = 'caret-top';
			$this->direction = 'desc';
		}
		
		return $carret;
	}
	
	public function setDirection($direction){
		$this->direction = $direction;
	}
	
	public function setDirectionFromUrl(){
		$dir = strtolower( Input::get( $this->directionParamName ) );
		if( $dir=='asc' ){
			$this->setDirection('desc');
		}else{
			$this->setDirection('asc');
		}
	}
	
	protected function addAll(){
		$params = Request::query();
		foreach( $params as $name=>$value){
			$this->addParam($name, $value);
		}
	}
	public function addParamFromUrl($name, $qsName=null){
		$param = Input::get($name);
		
		if( !empty($param) ){
			$this->addParam( ($qsName ? $qsName : $name), $param);
		}
	}
	
	public function addParam($name, $value=null){
		if( !empty($value) || $value=='0' ){
			$this->queryString[$name] = $value;
		}
	}
	
	public function removeParam($name){
		if($this->hasParam($name)){
			unset($this->queryString[$name]);
		}
	}
	
	public function clear(){
		$this->queryString = array();
	}
	
	public function getParams($formated=false){
		return $formated ? $this->getFormatedParams() : $this->queryString;
	}
	
	protected function getFormatedParams(){
		$params = $this->getParams(false);
		$formated = array();
		
		foreach($params as $name=>$value){
			$formated[] = "$name=$value";
		}
		
		return $formated;
	}
	
	public function getQueryString(){
		$params = $this->getParams(true);
		
		$qs = !empty($params) ? '?'.implode('&', $params ) : '';
		return $qs;
	}
	
	public function hasParam($name){
		return isset($this->queryString[$name]);
	}
	
	public function hasOrder(){
		return $this->hasParam($this->orderParamName);
	}
	
	public function getFieldName(){
		$field = $this->field ? $this->field : $this->label;
		
		return strtolower( str_replace(' ', '_', $field) );
	}
}
