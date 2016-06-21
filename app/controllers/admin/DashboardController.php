<?php
class DashboardController extends BaseAdminController {
	public function __construct(){
		$this->model;
		$this->searchField = null;
		$this->controller = null;
		$this->controllerTitle = null;
		$this->uploads = array();
		$this->rules = array();		
		parent::__construct();
	}
	
	public function anyIndex($id=null){
		if( Auth::guest() )
			return Redirect::to('admin/login');
		else
			return View::make('admin.home');
	}
	
	public function getBusca(){
		return $this->anyIndex(null);
	}
	
	public function anyEditar($id=null){
		return $this->anyIndex(null);
	}
	
	public function postSave($id=null){
		return $this->anyIndex(null);
	}
	
	public function getRemover($id=null){
		return $this->anyIndex(null);
	}
}
