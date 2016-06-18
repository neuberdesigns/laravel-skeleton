<?php
abstract class InputBuilderAbstract {
	protected $html;
	protected $type;
	protected $name;
	protected $displayName;
	protected $label;
	protected $selectedItem;
	protected $isChecked 			= false;
	protected $value 				= 'on';
	protected $size 				= 4;
	protected $list 				= array();
	protected $fieldAttributes 		= array();
	protected $labelAttributes 		= array();
	
	protected abstract function buildInputElement();
	
	public function __construct(){
		$this->fieldAttributes = array('class'=>'form-control');
		$this->labelAttributes = array('class'=>'col-xs-2 col-md-2 control-label');
	}
	
	//build the entire element, wrapers, label and input
	public function build(){
		$this->html = '';
		$this->html .= '<div class="form-group">';
		$this->html .= 	$this->buildLabelElement();
		$this->html .= '	<div class="col-xs-'.$this->getSize().' col-md-'.$this->getSize().'">';
		$this->html .= '		'.$this->buildInputElement();
		$this->html .= '	</div>';
		$this->html .= '</div>';
		
		return $this->getHtml();
	}
	
	//Setters / Config
	public function name($name, $displayName=null){
		$this->name = $name;
		$this->displayName = ucwords(str_replace('_', ' ', snake_case($displayName)));
		return $this;
	}
	
	public function displayName($displayName){
		$this->displayName = $displayName;
		return $this;
	}
	
	public function label($label){
		$this->label = $label;
		return $this;
	}
	
	public function value($value){
		$this->value = $value;
		return $this;
	}
	
	public function size($size){
		$this->size = $size;
		return $this;
	}
	
	public function checked($isChecked){
		$this->isChecked = $isChecked;
		return $this;
	}
	
	public function selected($item){
		$this->selectedItem = $item;
		return $this;
	}
	
	public function addFieldAttr($attr, $value){
		$this->fieldAttributes[$attr] = $value;
		return $this;
	}
	
	public function addClass($class){
		$this->addFieldAttr('class', $class);
		return $this;
	}
	
	public function placeholder($text){
		$this->addFieldAttr('placeholder', $text);
		return $this;
	}
	
	public function id($id){
		$this->addLabelAttr('for', $id);
		$this->addFieldAttr('id', $id);
		return $this;
	}
	
	public function addLabelAttr($attr, $value){
		$this->labelAttributes[$attr] = $value;
		return $this;
	}
	
	public function setList($list){
		$this->list = $list;
		return $this;
	}
	
	public function addListItem($key, $value){
		$this->list[$key] = $value;
		return $this;
	}
	
	
	//Getters
	/**
	 * Gets the value of html.
	 *
	 * @return mixed
	 */
	protected function getHtml(){
		return $this->html;
	}

	/**
	 * Gets the value of name.
	 *
	 * @return mixed
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * Gets the value of displayName.
	 *
	 * @return mixed
	 */
	public function getDisplayName(){
		return $this->displayName;
	}

	/**
	 * Gets the value of label.
	 *
	 * @return mixed
	 */
	public function getLabel(){
		return $this->label;
	}

	/**
	 * Gets the value of value.
	 *
	 * @return mixed
	 */
	public function getValue(){
		return $this->value;
	}

	/**
	 * Gets the value of isChecked.
	 *
	 * @return mixed
	 */
	public function isChecked(){
		return $this->isChecked;
	}

	/**
	 * Gets the value of selectedItem.
	 *
	 * @return mixed
	 */
	public function getSelectedItem(){
		return $this->selectedItem;
	}

	/**
	 * Gets the value of size.
	 *
	 * @return mixed
	 */
	public function getSize(){
		return $this->size;
	}

	/**
	 * Gets the value of list.
	 *
	 * @return mixed
	 */
	public function getList(){
		return $this->list;
	}

	/**
	 * Gets the value of fieldAttributes.
	 *
	 * @return mixed
	 */
	public function getFieldAttributes(){
		return $this->fieldAttributes;
	}

	/**
	 * Gets the value of labelAttributes.
	 *
	 * @return mixed
	 */
	public function getLabelAttributes() {
		return $this->labelAttributes;
	}
	
	//Build the label
	protected function buildLabelElement(){
		return Form::label($this->getDisplayName(), $this->getLabel(), $this->getLabelAttributes());
	}	
}
