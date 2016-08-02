<?php
class InputSelect extends InputBuilderAbstract {
	protected function buildInputElement(){
		$this->addClass('form-control');
		return Form::select($this->getName(), $this->getList(), $this->getSelectedItem(), $this->getFieldAttributes());
	}
}
