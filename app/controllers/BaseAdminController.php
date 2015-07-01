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
	protected $manualSlug 		= false;
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
		
		$this->beforeValidator($id, $validator);
		$validator = Validator::make(Input::all(), $this->rules);
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
			
			if( !empty($this->slugSource) && !$this->manualSlug ){
				$this->modelRow->slug = Slug::slugfy( Input::get($this->slugSource) );
			}
			
			$this->beforeSave($this->modelRow);
			if( $this->modelRow->save() ){
				Session::flash('save_success', 'Salvo com sucesso');
			}else{
				Session::flash('save_fail', 'Ops! Ocorreu um erro ao salvar');
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
	
	public function getOrganizar($field='order', $direction='asc'){
		$data = array();
		$controllerName = $this->getControllerName();
		$viewPath = $this->base.'.organize';
		
		$data['list'] = $this->model->orderBy($field, $direction)->get();
		$data['controllerName'] = $controllerName;
		return View::make($viewPath, $data);
	}
	
	public function postAjaxOrganize(){
		ini_set('html_errors', 'off');
		$json = new Json();
		
		if( Request::ajax() ){
			$list = Input::get('list');
			//print_r($list);
			foreach($list as $k=>$item ){
				$model = $this->model->newInstance();
				$row = $model->find($item);
				
				if( !empty($row) ){
					$row->order = $k;
					$row->save();
				}
			}
		}
		
		$json->setSuccess('Reorganizado');
		return $json;
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
	
	protected function export($map, $downloadName=null, $delimiter=';'){
		$tempDir = public_path().'/'.UPLOAD_TEMP_DIR;
		$fileName = 'export.csv';
		$path = $tempDir.$fileName;
		$fp = fopen($path, 'w');
		
		if( empty($downloadName) )
			$downloadName = date('Y-m-d').'_newsletter';
		
		
		$headerLine = array_values($map);
		
		fputcsv($fp, $headerLine, $delimiter);
		foreach( $this->model->all() as $k=>$row ){
			$line = array();
			foreach( $map as $field=>$name){
				array_push($line, $row->$field);
			}
			
			fputcsv($fp, $line, $delimiter);
		}
		
		return Response::download($path, $downloadName.'.csv');
	}
	
	public function postAjaxSeoLoad(){
		ini_set('html_errors', false);
		
		$seo 	= new Seo();
		$id 	= (int)Input::get('id');
		$json 	= new Json();
		
		if( Request::ajax() && $id > 0 ){
			$type = $this->model->getTable();
			$item = $seo->where('object_id', '=', $id)->where('type', '=', $type)->first();
			
			$dataSeo = array(
				'object_id'=>$id,
				'type'=>$type,
				'title'=>'',
				'keywords'=>'',
				'description'=>'',
			);
			
			//var_dump($item->toArray());
			if( empty($item) ){
				$item = $seo->create($dataSeo);
			}
			
			$json->add( 'data', $item->toArray() );
			$json->setSuccess('Carregado');
		}else{
			$json->setFail('Requisição invalida ou o item não existe');
		}
		
		echo $json;
		exit;
	}
	
	public function postAjaxSeoSave(){
		ini_set('html_errors', false);
		
		$json 	= new Json();
		$seo 	= new Seo();
		$id 	= (int)Input::get('object_id');
		
		if( Request::ajax() && $id > 0 ){
			$type = $this->model->getTable();
			$item = $seo->where('object_id', '=', $id)->where('type', '=', $type)->first();
			
			$dataSeo = array(
				'title'=>Input::get('title'),
				'keywords'=>Input::get('keywords'),
				'description'=>Input::get('description'),
			);
			
			if( !empty($item) ){
				$item->fill( $dataSeo );
				$item->save();
				$json->setSuccess('Salvo com sucesso');
			}else{
				$json->setFail('Erro ao salvar');
			}
			
		}else{
			$json->setFail('Requisição invalida ou o item não existe');
		}
		
		echo $json;
		exit;
	}
	
	public function postDeleteImage(){
		ini_set('html_errors', 'off');
		$json = new Json();
		
		$id = (int)Input::get('id');
		$field = (string)Input::get('field');
		
		$row = $this->model->find($id);
		
		if( !empty($row) && (isset($row->$field) && !is_null($row->$field) ) ){
			FileUpload::delete($row->$field);
			$row->$field = null;
			$row->save();
			$json->setSuccess('Imagem removida');
		}else{
			$json->setFail('A imagem não foi removida');
		}
		
		echo $json;
		exit;
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
	
	protected function doJqUploader(){
		$files 		= Input::file('files');
		$filesList	= array();
		$extensions = array('jpg', 'jpeg', 'png', 'gif');
		$ext;
		$error 		= null;
		$win 		= ';)';
		$oldName 	= '';
		
		foreach( $files as $i=>$file ){
			$error = null;
			$newName = null;
			$status = (object)array('uploaded'=>false, 'response'=>'', 'name'=>'', 'type'=>'', 'size'=>'', 'error'=>false);
			
			$ext = strtolower( $file->getClientOriginalExtension() );
			$mime = $file->getMimeType();
			
			if( in_array($ext, $extensions) ){
				$newName = FileUpload::make('files', 'upload', $i);
				if( $newName!==false ){
					//FileUpload::move($newName);
					//FileUpload::delete($oldName);
					$win = 'Arquivo enviado';
				}else{
					$error = 'O arquivo não pode ser enviado';
				}
			}else{
				$error = 'Somente imagens do tipo '.implode(', ', $extensions).' são permitidos';
			}
			
			if( empty($error) ){
				$status->uploaded 		= true;
				$status->response 		= $win;
				$status->name 			= $newName;
				$status->originalName	= $file->getClientOriginalName();
			}else{
				$status->response 	= $error;
				$status->error = true;
			}
			$status->type = $mime;
			$status->size = $file->getSize();
			$filesList[] = $status;
		}
		
		return $filesList;
	}
	
	protected function beforeValidator($id=null, &$validator=null){}
	
	protected function afterValidator($id=null, &$validator=null){}
	
	protected function beforeSave($model){}
	
	protected function afterSave($model){}
}
