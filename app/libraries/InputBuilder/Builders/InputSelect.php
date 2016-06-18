<?php
class InputSelect extends InputBuilderAbstract {
	protected function buildInputElement(){
		return Form::select($this->getName(), $this->getList(), $this->getSelectedItem());
	}
}
