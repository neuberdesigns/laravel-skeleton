<?php
abstract class BaseAdminController extends BaseController {
	
	protected $user;
	protected $orderLink;
	protected $controller;
	protected $controllerTitle;
	protected $model;
	protected $orderField 		= 'position';
	protected $searchField 		= 'name';
	protected $slugSource 		= null;
	protected $viewIndex 		= 'index';
	protected $viewList 		= 'list';
	protected $viewAdd 			= 'add';
	protected $createdAt		= 'criado_em';
	protected $rules 			= array();
	protected $uploads 			= array();
	protected $except 			= array();
	protected $aditionalData 	= array();
	protected $belongsToUser 	= true;
	protected $json;
	
	const SEARCH_PARAMETER_NAME = 'term';
	const ACTION_HOME 	= 'index';
	const ACTION_SAVE 	= 'save';
	const ACTION_EDIT 	= 'editar';
	const ACTION_NEW 	= 'novo';
	const ACTION_DELETE	= 'remover';
	const ACTION_LIST	= 'listagem';
	const ACTION_SEARCH	= 'busca';
	
	public function __construct(){
		$this->start();
		$this->orderLink = new OrderLink();
		$this->json = new Json();
		
		View::share('isLoginPage', Request::is('admin/login') );
		View::share('controllerSegment', $this->controller);
		View::share('controllerTitle', $this->controllerTitle);
	}
	
	protected abstract function start();
	protected function hookBeforeSave($model){}
	protected function hookAfterSave($model){}
	
	public function anyIndex($id=null){
		return $this->handler($id);
	}
	
	public function getBusca(){
		return $this->handler(null, null, true);
	}
	
	public function anyEditar($id=null){
		return $this->handler($id);
	}
	
	public function postSave($id=null){
		return $this->handler($id);
	}
	
	public function getRemover($id=null){
		return $this->remove($id);
	}
	
	public static function getLangs(){
		if(is_null(self::$langs)){
			self::$langs = MdLang::all();
		}
		
		return self::$langs;
	}
	
	protected function handler($id=null, $isNew=false, $isSearch=false){
		if( Request::isMethod('post') ){
			return $this->save($id);
		}else{
			$langs = BaseAdminController::getLangs();
			$data = array(
				'controller'=>$this->controller,
				'perpage'=>$this->orderLink->perpage,
			);
			
			$dataForAdd 	= $this->add($id, $isNew);
			$dataForList 	= $this->getList($isSearch);
			
			$mergedData 	= array_merge($data, $dataForAdd, $dataForList);
			//var_dump($mergedData);exit;
			return View::make($this->getViewPath(), $mergedData );
		}
	}
	
	protected function add($id, $isNew=true){
		$formModel = $this->fetchEdit($id);
		$isEmpty = empty($formModel);
		$data = array(
			'model'=>$formModel,
			'isEmpty'=>$isEmpty,
			'isNew'=>$isNew,
		);
		
		$mergedData = array_merge($data, $this->aditionalData);
		
		//return View::make('admin.'.$this->controller.'.'.$this->viewAdd, $mergedData );
		return $mergedData;
	}
	
	protected function getList($isSearch=false){
		$paginationSizeDefault = 10;
		$paginationSize = $this->orderLink->perpage;
		
		if(!$paginationSize){
			$paginationSize = $paginationSizeDefault;
		}
		
		$list = $isSearch ? $this->fetchSearch() : $this->fetchList();
		
		
		if( $this->orderLink->hasOrder() ){
			$list = $list->orderBy( $this->orderLink->order, $this->orderLink->direction );
		}
		
		$paginator = $list->paginate($paginationSize);
		$paginator->appends( $this->orderLink->getParams() );
		
		$data = array(
			'list'=>$paginator->getItems(),
			'pagination'=>$paginator->links(),
			'paginator'=>$paginator,
		);
		$data = array_merge($data, $this->aditionalData); 
		//return View::make('admin.'.$this->controller.'.'.$this->viewList, $data);
		return $data;
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
			
			$files = array();
			foreach($this->uploads as $uploadFieldName){
				if(Input::hasFile($uploadFieldName)){
					$upload = FileUpload::make()->field($uploadFieldName)->destination(FileUpload::DESTINATION_TEMP)->receive();
					if($upload->isUploaded()){
						$files[] = $upload;
					}
				}
			}
			
			if( !empty($this->model) ){
				$this->deleteOldUploads($files);
			}
			$this->model->fill(Input::except($this->except));
			
			$this->moveUploads($files);
			$this->hookBeforeSave($this->model);
			if( $this->model->save() ){
				$this->hookAfterSave($this->model);
				Session::flash('save_success', trans('admin.save_success'));
			}else{
				Session::flash('save_fail', trans('admin.save_fail'));
			}
			
			return Redirect::to(self::urlToEdit($this->controller, $this->model->getKey()));
		}else{
			Input::flash();
			return Redirect::to( self::urlToIndex($this->controller) )->withErrors($validator);
		}
	}
	
	protected function getViewPath(){
		return 'admin.'.$this->controller.'.'.$this->viewIndex;
	}
	
	protected function remove($id){
		$row = $this->model->find($id);
		
		if( !empty($row) ){
			$this->deleteUploads($row);
			$row->delete();
		}
		
		return Redirect::to( self::urlToIndex($this->controller) );
	}
	
	protected function fetchList(){
		//$this->model = $this->model;
		return $this->model;
	}
	
	protected function fetchEdit($id){
		//$this->model = $this->model->find($id);
		return $this->model->find($id);
	}
	
	protected function fetchSearch(){
		$term = $this->orderLink->term;
		//$this->model = $this->model->where('name', 'like', "%$term%");
		$model = $this->addSearchParam($this->model, self::SEARCH_PARAMETER_NAME, 'like', $this->searchField);
		
		return $model;
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
	
	public static function buildSelectList($collection, $prop, $key='id', $allowEmpty=true, $allowEmptyText='-- Selecione --'){
		$list = array();
		if( $allowEmpty )
			$list[null] = $allowEmptyText;
		
		foreach( $collection as $k=>$v ){
			$list[$v->$key] = $v->$prop;
		}
		
		return $list;
	}
	
	public static function isActive($segment){
		return Request::is("admin/$segment*") ? 'active' : '';
	}
	
	protected function deleteOldUploads($files){
		if( !empty($this->model) ){
			if( !empty($files) ){
				foreach( $this->uploads as $k=>$v ){
					if( !empty($files[$k]) ){
						FileUpload::make($this->model->$v)->delete();
						$this->model->$v = null;
					}
				}
			}
		}
	}
	
	protected function deleteUploads($modelRow){
		if( !empty($modelRow) ){
			foreach( $this->uploads as $field ){
				if( !empty($modelRow->$field) ){
					FileUpload::make($modelRow->$field)->delete();
					$modelRow->$field = null;
				}
			}
		}
	}
	
	protected function moveUploads($uploads){
		foreach($uploads as $upload) {
			$fieldName = $upload->getFieldName();
			$moved = $upload->move();
			$this->model->$fieldName = $upload->getUploadedName();
		}
	}
	
	protected function toggleField($model, $field, $valueOn=1, $valueOff=0){
		ini_set('html_errors', 'off');
		if( Request::ajax() ){
			if(!empty($model)){
				if(isset($model->$field)){
					$model->$field = $model->$field==$valueOn ? $valueOff : $valueOn;
					$model->save();
					$this->json->success();
				}else{
					$this->json->fail(trans('admin.field_could_not_update'));
				}
			}else{
				$this->json->fail(trans('admin.entry_not_found'));
			}
		}
				
		return $this->json->finish();
	}
	
	/* ---------------------------------------- */
	public function postAjaxDeleteImage(){
		ini_set('html_errors', 'off');
		$id = (int)Input::get('id');
		$field = (string)Input::get('field');
		
		$row = $this->model->find($id);
		
		if( !empty($row) && (isset($row->$field) && !is_null($row->$field) ) ){
			FileUpload::make($row->$field)->delete();
			$row->$field = null;
			$row->save();
			$this->json->success();
		}else{
			$this->json->fail(trans('admin.image_was_not_removed'));
		}
		
		return $this->json->finish();
	}
	
	public function postAjaxUpdateOrder(){
		if(Request::ajax()){
			$list = Input::get('order');
			foreach($list as $position=>$key){
				$this->model->where('id', '=', $key)->update( array($this->orderField => $position) );
			}
			$this->json->success();
		}else{
			$this->json->fail('Request is not an ajax');
		}
		
		return $this->json->finish();
	}
	
	/* ---------------------------------------- */
	public static function urlTo($controller, $action=null, $id=null, $keepQueryString=true){
		$orderLink = new OrderLink();
		$root 		= Request::root().'/admin'.(!empty($controller)?'/':'').$controller;
		$actionSeg 	= !empty($action) ? '/'.$action : '';
		$idSeg 		= !empty($id) ? '/'.$id : '';
		$queryString = null;
		
		if($keepQueryString)
			$queryString = $orderLink->getQueryString();
		
		return $root.$actionSeg.$idSeg.$queryString;
	}
	
	public static function urlToIndex($controller){
		return self::urlTo($controller, null, null, false);
	}
	
	public static function urlToEdit($controller, $id=null){
		return self::urlTo($controller, self::ACTION_EDIT, $id);
	}
	
	public static function urlToSearch($controller){
		return self::urlTo($controller, self::ACTION_SEARCH);
	}
	
	public static function urlToDelete($controller, $id=null){
		return self::urlTo($controller, self::ACTION_DELETE, $id);
	}
	
	public static function urlToSave($controller, $id=null){
		return self::urlTo($controller, self::ACTION_SAVE, $id, false);
	}
} 
