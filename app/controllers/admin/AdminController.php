<?php

class AdminController extends BaseAdminController {
	public function start(){
		$this->model = new Admin();
		$this->view = 'admin';
		$this->controller = 'admin';
		$this->controllerTitle = 'Admin';
		$this->uploads = array();
		$this->except = array('password');
		$this->rules = array(
			'name'=>'required',
			'email'=>'required|email|unique:admin,email',
			'password_input'=>'required',
		);
	}
	
	public function index(){
		return Redirect::to('admin/login');
	}
	
	public function login(){
		//var_dump( Hash::make('123') );
		if( Auth::check() )
			return Redirect::to('admin/dashboard');
		else
			return View::make('admin..admin.login');
	}
	
	public function processLogin(){
		$lm = new LoginAdmin();
		$loged = $lm->login('admin', 'email', 'password');
		if( $loged ){
			$user = LoginAdmin::getData();
			Session::put('user_id', $user->id);
			return Redirect::to('admin/dashboard');
		}else{
			return Redirect::to('admin/login')->with('login_errors', true);
		}
	}
	
	public function getLogout(){
		Auth::logout();
		return Redirect::to('/');
	}
	
	public function logout(){
		Auth::logout();
		return Redirect::to('admin/login');
	}
	
	protected function hookBeforeSave($model){
		$pass = Input::get('password_input');
		$pass = trim($pass);
		
		if( !empty($pass) ){
			$model->password = Hash::make($pass);
		}
	}
	
	protected function hookBeforeValidator($id=null, $model){
		if( $id==0 ){
			//$this->rules['email'][] = 'unique:admin,email';
		}else{
			unset($this->rules['password_input']);
		}
	}
}
