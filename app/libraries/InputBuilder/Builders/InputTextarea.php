<?php
class InputTextarea extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::textarea($this->getName(), $this->getValue(), $this->getFieldAttributes());
	}
}
