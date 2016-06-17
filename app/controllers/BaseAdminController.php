<?php
class BaseAdminController extends BaseController {
	protected $user;
	protected $orderLink;
	protected $controller;
	protected $model;
	protected $viewList 		= 'list';
	protected $viewAdd 			= 'add';
	protected $createdAt		= 'criado_em';
	protected $rules 			= array();
	protected $uploads 			= array();
	protected $except 			= array();
	protected $aditionalData 	= array();
	protected $belongsToUser 	= true;
	
	const ACTION_EDIT 	= 'editar';
	const ACTION_NEW 	= 'novo';
	const ACTION_DELETE	= 'remover';
	const ACTION_LIST	= 'listagem';
	const ACTION_SEARCH	= 'busca';
	
	public function __construct(){
		if( Session::has(SESSION_USER) ){
			$this->user = $this->getUserFromSession();
		}
		$this->orderLink = new OrderLink();
		View::share('me', $this->getUser() );
	}
	
	public function getIndex($id=null){
		return $this->route($id, self::ACTION_LIST);
	}
	
	public function getBusca(){
		return $this->route(null, self::ACTION_SEARCH);
	}
	
	public function anyEditar($id=null){
		return $this->route($id, self::ACTION_EDIT);
	}
	
	public function anyNovo($id=null){
		return $this->route($id, self::ACTION_NEW);
	}
	
	public function getRemover($id=null){
		return $this->route($id, self::ACTION_DELETE);
	}
	
	protected function route($id, $action=null){
		$response = null;
		if( $action==self::ACTION_NEW || $action==self::ACTION_EDIT ){
			if( Request::isMethod('post') ){
				$response = $this->save($id);
			}else{
				$response = $this->add($id, $action==self::ACTION_NEW);
			}
			
		}else if( $action==self::ACTION_DELETE ){
			$response = $this->remove($id);
		
		}else if( $action==self::ACTION_LIST){
			$response = $this->getlist(false);
		
		}else if( $action==self::ACTION_SEARCH){
			$response = $this->getlist(true);
		}
		
		return $response;
	}
	
	protected function add($id, $isNew){
		$formModel = $this->fetchEdit($id);
		$isEmpty = empty($formModel);
		$data = array(
			'controller'=>$this->controller,
			'model'=>$formModel,
			'isEmpty'=>$isEmpty,
			'isNew'=>$isNew,
			'belongsToMe'=>(!$isEmpty ? $formModel->usuario_id == $this->getUser()->id : false),
		);
		$mergedData = array_merge($data, $this->aditionalData);
		
		return View::make('site.'.$this->controller.'.'.$this->viewAdd, $mergedData );
	}
	
	protected function save($id){
		$isNew = true;
		$validator = Validator::make(Input::all(), $this->rules );
		if( $validator->passes() ){
			if( !empty($id) ){
				//$row = $this->model->byUser( $this->getUser()->id )->find($id);
				$row = $this->fetchEdit($id);
				
				if( !empty($row) ){
					$this->model = $row;
					$isNew = false;
				}
			}
			
			$files = FileUpload::batch($this->uploads, 'temp');
			
			if( !empty($this->model) ){
				$this->deleteOldUploads($files);
			}
			$this->model->fill( Input::except($this->except) );
			
			if( $this->belongsToUser )
				$this->model->usuario_id = $this->getUser()->id;
			
			$this->makeUploads($files);
			
			if( $isNew && !empty($this->createdAt) ){
				$field = $this->createdAt;
				$this->model->$field = date('Y-m-d H:i:s');
			}
			
			$this->hookBeforeSave($this->model);
			if( $this->model->save() ){
				$this->hookAfterSave($this->model);
				Session::flash('save_success', 'Salvo com sucesso');
			}else{
				Session::flash('save_fail', 'Ops! Ocorreu um erro ao salvar');
			}
			
			return Redirect::to( self::urlToEdit($this->controller, $this->model->getKey()) );
		}else{
			Input::flash();
			return Redirect::to( URL::current() )->withErrors($validator);
		}
	}
	
	protected function remove($id){
		$row = $this->model->find($id);
		
		if( !empty($row) ){
			$row->delete();
		}
		
		return Redirect::to( self::urlToList($this->controller) );
	}
	
	protected function getlist($isSearch=false){
		$list = $isSearch ? $this->fetchSearch() : $this->fetchList();
		
		if( $this->orderLink->hasOrder() ){
			$list = $list->orderBy( $this->orderLink->order, $this->orderLink->direction );
		}
		
		$paginator = $list->paginate(30);
		$paginator->appends( $this->orderLink->getParams() );
		
		$data = array(
			'list'=>$paginator->getItems(),
			'pagination'=>$paginator->links(),
			'paginator'=>$paginator,
			'controller'=>$this->controller,
		);
		$data = $data+$this->aditionalData; 
		return View::make('site.'.$this->controller.'.'.$this->viewList, $data);
	}
	
	protected function fetchList(){
		$this->model = $this->model->byUser( $this->getUser()->id );
		return $this->model;
	}
	
	protected function fetchEdit($id){
		$this->model = $this->model->byUser($this->getUser()->id)->find($id);
		return $this->model;
	}
	
	protected function fetchSearch(){
		$term = $this->orderLink->term;
		$this->model = $this->model->byUser( $this->getUser()->id )->where('nome', 'like', "%$term%");
		
		return $this->model;
	}
	
	protected function addSearchParam($model, $field, $operator='like', $column=null, $isOr=true){
		if( $this->orderLink->hasParam($field) ){
			$term = $this->orderLink->$field;
			
			if( $operator=='like' )
				$value = "%$term%";
			else
				$value = $term;
			
			$field = !empty($column) ? $column : $field;
			if( $isOr )
				$model = $model->orWhere($field, $operator, $value);
			else
				$model = $model->where($field, $operator, $value);
		}
		
		return $model;
	}
	
	protected function hookBeforeSave($model){}
	protected function hookAfterSave($model){}
	
	public static function buildSelectList($collection, $prop, $key='id', $allowEmpty=true, $allowEmptyText='-- Selecione --'){
		$list = array();
		if( $allowEmpty )
			$list[null] = $allowEmptyText;
		
		foreach( $collection as $k=>$v ){
			$list[$v->$key] = $v->$prop;
		}
		
		return $list;
	}
	
	protected function deleteOldUploads($files){
		if( !empty($this->model) ){
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
	
	protected function makeUploads($files){
		foreach ($files as $key => $filename) {
			if( !empty($filename) ){
				$fieldName = $this->uploads[$key];
				$this->model->$fieldName = $filename;
			}
		}
		FileUpload::moveBatch($files);
	}
	
	/* ---------------------------------------- */
	
	public function postAjaxDeleteImage(){
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
	
	
	/* ---------------------------------------- */
	protected function getUserFromSession(){
		return unserialize( Session::get(SESSION_USER) );
	}
	
	protected function getUser(){
		return $this->user;
	}
	
	public static function urlTo($controller, $action, $id=null){
		$orderLink = new OrderLink();
		$root 		= Request::root().'/'.$controller;
		$actionSeg 	= !empty($action) ? '/'.$action : '';
		$idSeg 		= !empty($id) ? '/'.$id : '';
		$queryString = $orderLink->getQueryString();
		
		return $root.$actionSeg.$idSeg.$queryString;
	}
	
	public static function urlToNew($controller, $id=null){
		return self::urlTo($controller, self::ACTION_NEW, $id);
	}
	
	public static function urlToEdit($controller, $id=null){
		return self::urlTo($controller, self::ACTION_EDIT, $id);
	}
	
	public static function urlToList($controller){
		return self::urlTo($controller, null);
	}
	
	public static function urlToSearch($controller){
		return self::urlTo($controller, self::ACTION_SEARCH);
	}
	
	public static function urlToDelete($controller, $id=null){
		return self::urlTo($controller, self::ACTION_DELETE, $id);
	}
} 
