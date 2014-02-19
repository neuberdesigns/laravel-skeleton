<?php

class AdminController extends BaseAdminController {

	public function getIndex(){
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
}