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
Route::group(array('before'=>'adm.auth', 'prefix'=>'admin'), function(){
	Route::get('sair', 'AdminController@logout');
	Route::controller('admin', 'AdminController');
});
Route::get('/admin', 'AdminController@index');
Route::get('/admin/login', 'AdminController@login');
Route::post('/admin/process-login', 'AdminController@processLogin');
Route::controller('/admin/dashboard', 'DashboardController');

//Front
Route::controller('/', 'SiteController');

//Filters
Route::filter('adm.auth', function(){
	if( Auth::guest() ) 
		return Redirect::to('admin/login');
});
