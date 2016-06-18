<?php
class InputNumber extends InputBuilderAbstract {
	protected function buildInputElement(){
		$this->value(0);
		return Form::number($this->getName(), $this->getValue());
	}
}
