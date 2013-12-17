<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Admin
Route::group(array('before'=>'auth'), function(){
	Route::controller('admin/index', 'AdminController');
});
Route::controller('/admin', 'AdminController');

//Front
Route::controller('/', 'HomeController');

//Filters
Route::filter('auth', function(){
	if( Auth::guest() ) 
		return Redirect::to('admin/login');
});