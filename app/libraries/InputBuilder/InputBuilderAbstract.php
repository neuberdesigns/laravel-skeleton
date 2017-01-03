<?php
abstract class InputBuilderAbstract {
	protected $html;
	protected $type;
	protected $name;
	protected $displayName;
	protected $label;
	protected $selectedItem;
	protected $toogleOn;
	protected $toogleOff;
	protected $isChecked 			= false;
	protected $value 				= null;
	protected $size 				= null;
	protected $list 				= array();
	protected $fieldAttributes 		= array();
	protected $labelAttributes 		= array();
	protected $extraMarkup	 		= '';
	
	protected abstract function buildInputElement();
	
	public function __construct($type=null){
		$this->type = $type;
		$this->fieldAttributes = array('class'=>'form-control');
		$this->labelAttributes = array('class'=>'');
		$this->size = new StdObject();
		$this->size->sm = 12;
		$this->size->md = 12;
		$this->size->lg = 12;
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
	
	/**
	 * Set size of input element, sm is most common to have value of 12, so its come after md and has the default value of 12
	 * @param int $md size for the col-sm mediaquery
	 * @param int $sm size for the col-md mediaquery
	 * @param int|null $lg size for the col-lg mediaquery
	 * @return instance
	 */
	public function size($md, $sm=12, $lg=null){
		$defaultSize = 12;
		if(!$md)
			$md = $defaultSize;
		
		if(!$sm)
			$sm = $md;
		
		if(!$lg)
			$lg = $md;
		
		$this->size->sm = $sm;
		$this->size->md = $md;
		$this->size->lg = $lg;
		
		return $this;
	}
	
	
	public function addFieldAttr($attr, $value=null){
		if(is_string($attr)){
			$this->fieldAttributes[$attr] = $value;
		}else if(is_array($attr)){
			foreach($attr as $attribute=>$value){
				$this->addFieldAttr($attribute, $value);
			}
		}
		
		return $this;
	}
	
	public function removeFieldAttr($attr){
		if( isset($this->fieldAttributes[$attr]) ){
			unset($this->fieldAttributes[$attr]);
		}
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
	
	//Field Parameters
	public function addClass($class){
		$this->fieldAttributes['class'] .= ' '.$class;
		return $this;
	}
	
	public function mask($value){
		$this->addClass($value);
		return $this;
	}
	
	public function placeholder($text){
		$this->addFieldAttr('placeholder', $text);
		return $this;
	}
	
	public function id($id){
		$this->addFieldAttr('id', $id);
		return $this;
	}
	
	public function disabled(){
		$this->addFieldAttr('disabled', 'true');
		return $this;
	}
	
	public function readOnly(){
		$this->addFieldAttr('readonly', 'true');
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
	
	public function cols($size=null){
		return $this->addFieldAttr('cols', $size);
	}
	
	public function rows($size=null){
		return $this->addFieldAttr('rows', $size);
	}
	
	public function tinymce(){
		return $this->addClass('tinymce');
	}
	
	/**
	 * Appends an extra markup (HTML) next to the input element
	 * @param string $markup html markup
	 * @return instance
	 */
	public function append($markup){
		$this->extraMarkup = $markup;
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
	 * Gets the value of type.
	 *
	 * @return mixed
	 */
	public function getType(){
		return $this->type;
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
	 * @return mixed
	 */
	public function getLabelAttributes() {
		return $this->labelAttributes;
	}
	
	public function getExtraMarkup(){
		return $this->extraMarkup;
	}
	
	//Build the label
	protected function buildLabelElement(){
		return Form::label($this->getDisplayName(), $this->getLabel(), $this->getLabelAttributes());
	}
	
	//build the entire element, wrapers, label and input
	public function build(){
		$size = $this->getSize();
		
		$this->html = PHP_EOL;
		$this->html .= "\t\t\t\t".'<div class="col-sm-'.$size->sm.' col-md-'.$size->md.' col-lg-'.$size->lg.'">'.PHP_EOL;
		$this->html .= "\t\t\t\t\t".'<div class="form-group">'.PHP_EOL;
		$this->html .= "\t\t\t\t\t\t".$this->buildLabelElement().PHP_EOL;
		
		if($this->getType()=='checkbox'){
			$this->html .= "\t\t\t\t\t\t".'<br>'.PHP_EOL;
		}
			$this->html .= "\t\t\t\t\t\t".$this->buildInputElement().PHP_EOL;
			$this->html .= "\t\t\t\t\t\t".$this->getExtraMarkup().PHP_EOL;
		
		$this->html .= "\t\t\t\t\t".'</div>'.PHP_EOL;
		$this->html .= "\t\t\t\t".'</div>'.PHP_EOL;
		
		return $this->getHtml();
	}

	
}
