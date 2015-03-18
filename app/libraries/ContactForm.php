<?php
class ContactForm {
	protected $rules 		= array();
	protected $hasFile 		= false;
	protected $fileFields 	= array();
	protected $msgSuccess 	= 'Enviado com sucesso';
	protected $msgError 	= 'Erro ao enviar';
	
	public function __construct($rules=array()){
		foreach( $rules as $field=>$validation ){
			if( !is_numeric($field) && is_string($field) ){
				$this->addRule($field, $validation);
			}
		}
	}
	
	public function addRule($field, $validation=null){		
		$this->rules[$field] = $validation;
	}
	
	public function removeField($field){
		if( isset($this->rules[$field]) ){
			unset($this->rules[$field]);
		}
	}
	
	public function validate(&$errors=null){
		$rules = $this->rules;		
		
		$validator = Validator::make(Input::all(), $rules);
		
		if( $validator->passes() ){
			return true;
		}else{
			$errors = $validator->errors();
			Input::flash();
			return false;
		}
	}
	
	public function send($subject, $to=EMAIL_RECEIVER, $toName=EMAIL_NAME, $view=null){
		$data = array();
		$fields = array();
		$view = empty($view) ? 'emails.default' : $view;
		
		foreach($this->rules as $field=>$validation){
			$fields[$field] = Input::get($field);
		}
		
		$data['fields'] = $fields;
		$self = $this;
		$send = Mail::send($view, $data, function($message) use ($to, $toName, $subject, $self){
			$from = EMAIL_RECEIVER;
			$fromName = EMAIL_NAME;
			
			$message->subject($subject);
			$message->replyTo($from, $fromName );
			$message->from($from, $fromName);
			$message->to($to, $toName);
			
			if( $self->hasFile ){
				if( !empty($self->fileFields) && is_array($self->fileFields) ){
					foreach( $self->fileFields as $file ){
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
}
