<?php
class InputText extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::text($this->getName(), $this->getValue(), $this->getFieldAttributes());
	}
}
