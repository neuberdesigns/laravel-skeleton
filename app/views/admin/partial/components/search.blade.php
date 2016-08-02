<div id="search-box" class="dataTables_filter">
	{{Form::open(array('method'=>'get', 'url'=>BaseAdminController::urlToSearch($controllerSegment)))}}
		<label>{{trans('admin.search')}}
			{{Form::input('search', 'term', Input::get('term'), array('class'=>'form-control input-sm', 'aria-controls'=>'search'))}}
		</label>
	{{Form::close()}}
</div>
