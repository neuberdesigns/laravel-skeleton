<?php

class BaseAdminController extends Controller {
	protected $model;
	protected $view;
	protected $uploads = array();
	protected $rules = array();
	protected $orderBy = array('field'=>null, 'order'=>'ASC');
	protected $layout = 'layout.admin';
	protected $base = 'admin';
	
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
	}
	
	public function getAdicionar($id=0)
	{
		$id = (int)$id;
		$formModel;
		
		$formModel = $id>0 ? $this->model->find($id) : $this->model;
		
		if( empty($this->orderBy['field']) )
			$list = $this->model->all();
		else
			$list = $this->model->orderBy($this->orderBy['field'], $this->orderBy['order'])->get();
		
		/*var_dump($this->layout);
		var_dump($this->base.'/'.$this->view);
		die('done');*/
		
		return View::make($this->base.'/'.$this->view)
					->with('model', $formModel)
					->with('list', $list)
					->with('primaryName', $this->model->getKeyName());
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
		if($validator->fails()){
			Input::flash();
			return Redirect::to( URL::current() )->withErrors($validator);
		}else{
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
						
			$this->model->fill(Input::except(''));
			
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
		}
	}

}