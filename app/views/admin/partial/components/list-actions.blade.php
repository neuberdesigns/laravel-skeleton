
<div class="">
	@if( isset($editButton) && $editButton==false )
	@else
		<a href="{{BaseAdminController::urlToEdit($controllerSegment, $row->getKey())}}" class="btn btn-primary">
			{{trans('admin.edit')}}
		</a>
	@endif
	
	
	@if( isset($deleteButton) && $deleteButton==false )
	@else
		<a href="{{BaseAdminController::urlToDelete($controllerSegment, $row->getKey())}}" class="btn btn-danger bt-delete-row">
			{{trans('admin.delete')}}
		</a>
	@endif
</div>
