<?php
class InputHidden extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::hidden($this->getName(), null, $this->getFieldAttributes());
	}
}
