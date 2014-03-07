<?php

class BaseAdminController extends Controller {
	protected $model;
	protected $modelRow;
	protected $controllerSegment;
	protected $uploads 			= array();
	protected $except 			= array();
	protected $aditionalData 	= array();
	protected $rules 			= array();
	protected $orderBy 			= array('field'=>null, 'order'=>'ASC');
	protected $layout 			= 'layout.admin';
	protected $base 			= 'admin';
	protected $view 			= 'add';
	protected $list 			= 'list';
	protected $hasSlug 			= false;
	protected $slugSource		= null;
	
	/*public function missingMethod($parameters){
		var_dump($parameters);
		exit('404');
	}*/
	
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
		
		if( empty($this->controllerSegment) ){
			$segs = explode('/', BasePath::getPath());
			$this->controllerSegment = end($segs);
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
		$data['controllerSegment'] = $this->controllerSegment;
		
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
			
			if( $this->hasSlug ){
				$this->modelRow->slug = Slug::slugfy( Input::get($this->slugSource) );
			}
			
			$this->beforeSave($this->modelRow);
			if( $this->modelRow->save() ){
			}
			$this->afterSave($this->modelRow);			
			
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
		$data['controllerSegment'] = $this->controllerSegment;
		$data = array_merge($data, $this->aditionalData);
		
		$viewPath = $this->base.'.'.$this->getControllerName().'.'.$this->list;
		return View::make($viewPath, $data);
	}
	
	public function getDeletar($id){
		$uri = BasePath::getPath('listagem');
		
		$row = $this->model->find($id);
		if( !empty($row) ){
			foreach ($this->uploads as $key=>$field)
				FileUpload::delete($row->$field);
			
			$row->delete();
		}
		
		return Redirect::to($uri);
	}	
	
	
	
	protected function getControllerName(){
		$classname = get_class($this);
		$controllerName = str_replace(array('_controller', '_'), array('', '-'), snake_case($classname) );
		
		return $controllerName;
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
	
	//GALLERY
	//postAjaxSendFiles()
	public function doAjaxSendFiles($fk, $mdGallery, $mdGalleryImage){
		ini_set('html_errors', 'off');
		
		$fieldname = 'gallery_files';
		$json = new Json();
		$files = array();
		$allowedVideos = array();
		$allowedImages = array('jpeg', 'jpg', 'png', 'gif');
		$allowed = array_merge($allowedImages, $allowedVideos);
		$type = null;
		
		if( Request::ajax() ){
			$filesUpload = Input::file($fieldname);
			$files = array();
			
			$id = (int)Input::get('id');			
			$gallery = $mdGallery->find($id);
			
			if( !empty($filesUpload) ){
				if( !empty($gallery) ){
					
					foreach ($filesUpload as $key => $file) {
						
						$ext = strtolower( $file->getClientOriginalExtension() );
						
						if( in_array($ext, $allowedImages) ){
							$type = 'image';
						}
						
						if( !empty($type) ){
							$upload = FileUpload::make($fieldname, 'temp', $key);
							if( $upload!==false ){
								$photo = $mdGalleryImage->newInstance();
								$photo->image = $upload;
								$photo->order = 99999;
								$photo->$fk = $gallery->id;
								
								if( $photo->save() ){
									FileUpload::move($upload);
									$files[$key] = array(
										'name' => $upload,
										'url' => FileUpload::get( $upload ),
										'thumbnailUrl' => FileUpload::getTim( $upload, null, null, null, false ),
										'type' => $type,
										'item' => View::make('admin.partial.gallery-list-item', array('file'=>$photo) )->render(),
									);
									$json->setSuccess('Imagem enviada');
								}else{
									$json->setFail('Ocorreu um erro ao salvar a imagem');
								}
							}else{
								$json->setFail('Ocorreu um erro ao enviar a foto');
								$files[$key]['error'] = $json->response;
							}
						}else{
							$json->setFail('Arquivo invalido, é permitido somente: '. implode(', ', $allowed) );
							$files[$key]['error'] = $json->response;
						}
					}// endforeach
				}else{
					$json->setFail('O album parece não existir');
					$files[]['error'] = $json->response;
				}
			}else{
				$json->setFail('O arquivo parece ser muito grande');
				$files[]['error'] = $json->response;
			}
			
		}
		
		$json->add('files', $files);
		
		return $json->make();
	}
	
	//postAjaxRemoveFile()
	public function doAjaxRemoveFile($mdGalleryImage){
		ini_set('html_errors', 'off');
		$json = new Json();
		
		if( Request::ajax() ){
			$id = (int)Input::get('id');
			$delete = $this->deleteFileGallery($id, $mdGalleryImage);
			
			if( $delete ){
				$json->setSuccess('Imagem removida com sucesso');
			}else{
				$json->setFail('Erro ao remover a imagem');
			}
		}
		
		return $json->make();
	}
	
	//postAjaxOrganize()
	public function doAjaxUpdateOrder($fk, $mdGallery, $mdGalleryImage){
		ini_set('html_errors', 'off');
		$json = new Json();
		
		if( Request::ajax() ){
			$galleryId = (int)Input::get('gallery_id');
			$order = Input::get('order');
			$gallery = $mdGallery->find($galleryId);
			
			if( !empty($gallery) ){
				foreach( $order as $k=>$photo ){
					$mdGalleryImage->where($fk, '=', $gallery->id)
						->where('id', '=', $photo['id'])
						->update( array('order'=>$photo['index']) );
				}
				$json->setSuccess('Ordem atulizada');
			}else{
				$json->setFail('O album não existe');
			}
		}
		
		return $json->make();
	}
	
	protected function deleteFileGallery($id, $mdGalleryImage){
		$file = $mdGalleryImage->find($id);
		
		if( !empty($file) ){
			FileUpload::delete($file->image);
			$delRow = $file->delete();
			
			if( $delRow ){
				return true;
			}
		}
		
		return false;
	}
	
	protected function buildSelectList($collection, $prop, $key='id'){
		$list = array();
		
		foreach( $collection as $k=>$v ){
			$list[$v->$key] = $v->$prop;
		}
		
		return $list;
	}
	
	protected function beforeValidator($id=null, &$validator=null){}
	
	protected function afterValidator($id=null, &$validator=null){}
	
	protected function beforeSave($model){}
	
	protected function afterSave($model){}
}