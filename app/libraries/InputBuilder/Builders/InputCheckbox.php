<?php
class InputCheckbox extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::checkbox($this->getName(), $this->getValue(), $this->isChecked());
	}
}
