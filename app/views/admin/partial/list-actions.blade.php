
<div class="btn-group">
	@if( isset($editButton) && $editButton==false )
	@else
		<a href="{{Request::root().BasePath::getPath('adicionar/'.$row->getKey() ) }}" class="btn btn-default">
			<span class="glyphicon glyphicon-edit"></span>
			Editar
		</a>
	@endif
	
	
	
	@if( isset($deleteButton) && $deleteButton==false )
	@else
		<a href="{{Request::root().BasePath::getPath('deletar/'.$row->getKey() )}}" class="btn btn-default">
			<span class="glyphicon glyphicon-remove"></span>
			Remover
		</a>
	@endif
</div> 
