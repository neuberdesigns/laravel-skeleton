<?php
class InputFile extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::file($this->getName(), $this->getFieldAttributes());
	}
}
