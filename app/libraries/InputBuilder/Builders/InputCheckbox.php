<?php
class InputCheckbox extends InputBuilderAbstract {
	public function __construct($type=null){
		parent::__construct($type);
		$this->toogleOn = trans('admin.active');
		$this->toogleOff = trans('admin.inactive');
	}
	
	protected function buildInputElement(){
		$this->removeFieldAttr('class');
		$this->addFieldAttr('data-toggle', 'toggle');
		$this->addFieldAttr('data-on', $this->toogleOn);
		$this->addFieldAttr('data-off', $this->toogleOff);
		return Form::checkbox($this->getName(), $this->getValue(), $this->isChecked(), $this->getFieldAttributes());
	}
}
