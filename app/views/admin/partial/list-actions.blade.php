
<div class="btn-group">
	<a href="{{Request::root().BasePath::getPath('adicionar/'.$row->getKey() ) }}" class="btn btn-default">
		<span class="glyphicon glyphicon-edit"></span>
		Editar
	</a>
	
	<a href="{{Request::root().BasePath::getPath('deletar/'.$row->getKey() )}}" class="btn btn-default">
		<span class="glyphicon glyphicon-remove"></span>
		Remover
	</a>
</div> 
