<?php
class InputPassword extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::password($this->getName(), $this->getFieldAttributes());
	}
}
