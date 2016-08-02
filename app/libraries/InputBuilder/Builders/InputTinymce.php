<?php
class InputTinymce extends InputBuilderAbstract {
	protected function buildInputElement(){
		$this->addClass('tinymce');
		$this->addFieldAttr('rows', 20);
		return Form::textarea($this->getName(), $this->getValue(), $this->getFieldAttributes());
	}
}
