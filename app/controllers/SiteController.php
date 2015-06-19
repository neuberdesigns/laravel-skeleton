<?php
class SiteController extends BaseController {
	public function getIndex(){
		return View::make('site.index');
	}
} 
