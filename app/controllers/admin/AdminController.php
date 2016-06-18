<?php
class AdminController extends BaseAdminController {
	public function __construct(){
		parent::__construct();
		$this->model = new Admin();
		$this->uploads = array();
		$this->except = array('password');
		$this->rules = array(
			'name'=>'required',
			'email'=>'required',
			'password'=>'required',
		);
	}
	public function getIndex($id=null){
		if( Auth::guest() )
			return Redirect::to('admin/login');
		else
			return View::make('admin.home');
	}
	
	public function getLogin(){
		//var_dump( Hash::make('123') );
		if( Auth::check() )
			return Redirect::to('admin/index');
		else
			return View::make('admin.login');
	}
	
	public function postProcessLogin(){
		$userdata = array(
			'email'=>Input::get('email'),
			'password'=>Input::get('password')
		);
		
		$env = App::environment();
		if( $env=='local' && (empty(Input::get('email')) && empty(Input::get('password'))) ){
			$master = Admin::first();
			$passRaw = '123';
			
				
			if( empty($master) ){
				$adm = new Admin();
				$adm->name = 'Neuber Oliveira';
				$adm->email = 'neuberdesigns@hotmail.com';
				$adm->password = Hash::make($passRaw);
				
				if( $adm->save() ){
					$userdata = array(
						'email'=>$adm->email,
						'password'=>$passRaw,
					);
				}
			}else{
				$userdata = array(
					'email'=>$master->email,
					'password'=>$passRaw,
				);
			}
		}
		
		if( Auth::attempt( $userdata ) ){
			return Redirect::to('admin/index');
		}else{
			return Redirect::to('admin/login')->with('login_errors', true);
		}
	}
	
	public function getLogout(){
		Auth::logout();
		return Redirect::to('admin/login');
	}
	
	
	/*public function getAdicionar($id=null){
		return parent::getAdicionar( Auth::user()->id );
	}
	
	public function postAdicionar($id=null){
		return parent::postAdicionar( Auth::user()->id );
	}*/
	
	protected function beforeSave($model){
		$pass = trim( Input::get('password') );
		
		if( !empty($pass) ){
			$model->password = Hash::make($pass);
		}
	}
	
	protected function afterValidator($id=null, &$validator=null){
		$rules = $validator->getRules();
		$bag = $validator->errors();
		if( $id==0 ){
			$rules['email'][] = 'unique:admin,email';
		}else{
			unset($rules['password']);
		}
		
		$validator->setRules( $rules );
		
		$validator->passes();
	}
}
