<?php

class BaseAdminController extends Controller {
	protected $model;
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
		
		$this->controllerName = strtolower( str_replace('Controller', '', get_class($this) ) );
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
		
		$viewPath = $this->base.'.'.$this->controllerName.'.'.$this->view;
		return View::make($viewPath, $data);
	}
	
	public function getListagem($filter=null, $order='ASC'){
		$data = array();
		
		$list = $this->model;
		
		if( !empty($list) ){
			$list->orderBy($filter, $order);
		}
		
		$list->paginate(10);
		
		$data['list'] = $list->get();
		$data['pagination'] = $list->links();
		$data['controllerName'] = $this->controllerName;
		$data = array_merge($data, $this->aditionalData);
		
		$viewPath = $this->base.'.'.$this->controllerName.'.'.$this->list;
		return View::make($viewPath, $data);
	}
	
	public function postAdicionar($id=0){
		return $this->persist($id);
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
	
	protected function persist($id=0){
		$id = (int)$id;
		
		//Validation rules
		$rules = $this->rules;
		
		$validator = Validator::make(Input::all(), $rules);
		if( $validator->passes() ){
			$files = FileUpload::batch($this->uploads, 'temp');
			
			$data = array();
			
			if( $id>0 ){
				$data = $this->model->find($id);
				if( !empty($data) ){
					$this->model = $data;
					
					if( !empty($files) ){
						foreach( $this->uploads as $k=>$v ){
							if( !empty($files[$k]) ){
								FileUpload::delete($this->model->$v);
								$this->model->$v = null;
							}
						}
					}
				}
			}
						
			$this->model->fill(Input::except($this->except));
			
			foreach ($files as $key => $filename) {
				if( !empty($filename) ){
					$fieldName = $this->uploads[$key];
					$this->model->$fieldName = $filename;
				}
			}
			
			if( $this->model->save() ){
				FileUpload::moveBatch($files);
			}
			
			
			$segments = Request::segments();
			$last = end($segments);
			if( !is_numeric($last) )
				array_push($segments, $this->model->getKey() );
			
			$path = implode('/', $segments);
			return Redirect::to( $path );
		}else{
			Input::flash();
			return Redirect::to( URL::current() )->withErrors($validator);
		}
	}

}