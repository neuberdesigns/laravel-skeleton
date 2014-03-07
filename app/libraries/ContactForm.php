<?php
class ContactForm {
	protected $formAttrs = array();
	protected $fields = array();
	protected $size = '6';
	protected $nameField = 'nome';
	protected $emailField = 'email';
	protected $submitLabel = 'Enviar';
	protected $submitAttr = array('class'=>'btn btn-primary btn-lg');
	protected $msgSuccess = 'Contato enviado com sucesso';
	protected $msgError = 'Ocorreu um erro ao enviar o contato';
	protected $hasFile = false;
	protected $fileFields = array();
	
	
	public function config($confs){
		foreach ($confs as $prop=>$value) {
			if( property_exists($this, $prop) ){
				$this->$prop = $value;
			}
		}
	}
	
	public function set($attr, $value){
		if( property_exists($this, $attr) ){
			$this->$attr = $value;
		}
		
		return $this;
	}
	
	public function addField($label, $validation=null, $type='text', $attrs=null){
		$name = strtolower( str_replace(' ', '_', $label) );
		
		$defaultAttrs = array();
		$defaultValidation = array();
		
		$attrs = empty($attrs) ? array() : $attrs;
		
		$this->fields[$name] = (object)array(
			'type'=>$type,
			'label'=>$label,
			'name'=>$name,
			'validation'=>$validation,
			'attributes'=>$defaultAttrs+$attrs,
		);
	}
	
	public function removeField($name){
		if( isset($this->fields[$name]) ){
			unset($this->fields[$name]);
		}
	}
	
	public function setFormAttributes($attrs){
		$this->formAttrs = $attrs;
	}
	
	public function validate(&$errors=null){
		$rules = $this->getRules();
		
		
		$validator = Validator::make(Input::all(), $rules);
		
		if( $validator->passes() ){
			return true;
		}else{
			$errors = $validator->errors();
			Input::flash();
			return false;
		}
	}
	
	public function getRules(){
		$rules = array();
		foreach($this->fields as $k=>$field){
			$rules[$field->name] = $field->validation;
		}
		
		return $rules;
	}
	
	public function sendMail($subject, $to=EMAIL_RECEIVER, $toName=EMAIL_NAME, $view=null){
		$data = array();
		$fields = array();
		$view = empty($view) ? 'emails.default' : $view;
		
		foreach($this->fields as $k=>$field){
			$fields[$field->label] = Input::get($field->name);
		}
		
		$data['fields'] = $fields;
		
		$send = Mail::send($view, $data, function($message) use ($to, $toName, $subject){
			$from = empty($this->emailField) ? $to : Input::get($this->emailField);
			$fromName = empty($this->nameField) ? $toName : Input::get($this->nameField);
			
			$message->subject($subject);
			$message->replyTo($from, $fromName );
			$message->from($from, $fromName);
			$message->to($to, $toName);
			
			if( $this->hasFile ){
				if( !empty($this->fileFields) && is_array($this->fileFields) ){
					foreach( $this->fileFields as $file ){
						$attach = Input::file($file);
								
						$path = public_path().'/'.UPLOAD_TEMP_DIR;
						$filename = $attach->getClientOriginalName();
						$pathFull = $path.$filename;
						
						$attach->move($path, $filename);
						$message->attach($pathFull);
					}
				}
			}
		});
		
		if( $send>0 )
			$message = $this->msgSuccess;
		else
			$message = $this->msgError;
		
		Session::flash('mailsend', $message);
		
		return $send;
	}
	
	public function buildForm($echo=false){
		$elements = array();
		
		array_push( $elements, Form::open($this->formAttrs) );
		foreach($this->fields as $k=>$field){
			array_push( $elements, BsFormField::make($field->name, $field->label, $this->size, $field->type, array() ) );
		}
		array_push($elements, Form::submit($this->submitLabel, $this->submitAttr ) );
		array_push( $elements, Form::close() );
		
		$form = implode(' ', $elements);
		if( $echo ){
			echo $form;
		}else{
			return $form;
		}
	}
}