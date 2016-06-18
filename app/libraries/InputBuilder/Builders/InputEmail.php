<?php
class InputEmail extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::email($this->getName(), null, $this->getFieldAttributes());
	}
}
