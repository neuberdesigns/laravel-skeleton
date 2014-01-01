<?php

class BaseAdminController extends Controller {
	protected $model;
	protected $modelRow;
	protected $controllerName;
	protected $uploads 			= array();
	protected $except 			= array();
	protected $aditionalData 	= array();
	protected $rules 			= array();
	protected $orderBy 			= array('field'=>null, 'order'=>'ASC');
	protected $layout 			= 'layout.admin';
	protected $base 			= 'admin';
	protected $view 			= 'add';
	protected $list 			= 'list';
	
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
		
		if( empty($controllerName) ){
			$segs = explode('/', BasePath::getPath());
			$this->controllerName = end($segs);
		}
	}
	
	public function getIndex(){
		return Redirect::to( BasePath::getPath('listagem') );
	}
	
	public function getAdicionar($id=0)
	{		
		$data = array();
		$id = (int)$id;
		$formModel;
		$formModel = $id>0 ? $this->model->find($id) : $this->model;
		
		$data['model'] = $formModel;
		$data['controllerName'] = $this->controllerName;
		
		$data = array_merge($data, $this->aditionalData);
		
		$viewPath = $this->base.'.'.$this->getControllerName().'.'.$this->view;
		return View::make($viewPath, $data);
	}
	
	public function postAdicionar($id=0){
		$id = (int)$id;		
		
		//Validation rules
		$rules = $this->rules;
		
		$this->beforeValidator($id, $validator);
		$validator = Validator::make(Input::all(), $rules);
		$validator->passes();
		$this->afterValidator($id, $validator);
		
		$validatorResult = $validator->errors()->isEmpty();
		
		if( $validatorResult ){
			$files = FileUpload::batch($this->uploads, 'temp');
			$this->modelRow = $this->model->find($id);
			
			if( !empty($this->modelRow) ){
				$this->deleteOldUploads($files);
			}else{
				$this->modelRow = $this->model;
			}
			$this->modelRow->fill( Input::except($this->except) );
			$this->makeUploads($files);
			
						
			if( $this->modelRow->save() ){
			}			
			
			return $this->successRedirect();
		}else{
			Input::flash();
			return Redirect::to( URL::current() )->withErrors($validator);
		}
	}
	
	public function getListagem($filter=null, $order='ASC'){
		$data = array();
		
		$list = $this->model;
		
		if( !empty($filter) ){
			$list = $list->orderBy($filter, $order);
		}
		
		$pagination = $list->paginate(30);
		
		$data['pagination'] = $pagination->links();
		$data['list'] = $pagination->getItems();
		$data['controllerName'] = $this->controllerName;
		$data = array_merge($data, $this->aditionalData);
		
		$viewPath = $this->base.'.'.$this->getControllerName().'.'.$this->list;
		return View::make($viewPath, $data);
	}
	
	public function getDeletar($id){
		$uri = BasePath::getPath('adicionar');
		
		$row = $this->model->find($id);
		if( !empty($row) ){
			foreach ($this->uploads as $key=>$field)
				FileUpload::delete($row->$field);
			
			$row->delete();
		}
		
		return Redirect::to($uri);
	}	
	
	
	
	protected function getControllerName(){
		return strtolower( str_replace('Controller', '', get_class($this) ) );
	}
	
	protected function deleteOldUploads($files){
		if( !empty($this->modelRow) ){
			if( !empty($files) ){
				foreach( $this->uploads as $k=>$v ){
					if( !empty($files[$k]) ){
						FileUpload::delete($this->modelRow->$v);
						$this->model->$v = null;
					}
				}
			}
		}
	}
	
	protected function makeUploads($files){
		foreach ($files as $key => $filename) {
			if( !empty($filename) ){
				$fieldName = $this->uploads[$key];
				$this->modelRow->$fieldName = $filename;
			}
		}
		FileUpload::moveBatch($files);
	}
	
	protected function successRedirect(){
		$segments = Request::segments();
		$last = end($segments);
		if( !is_numeric($last) )
			array_push($segments, $this->modelRow->getKey() );
		
		$path = implode('/', $segments);
		
		return Redirect::to( $path );
	}	
	
	protected function beforeValidator($id=null, &$validator=null){}
	
	protected function afterValidator($id=null, &$validator=null){}
}