<?php
class InputRadio extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::radio($this->getName(), $this->getValue(), $this->isChecked());
	}
}
