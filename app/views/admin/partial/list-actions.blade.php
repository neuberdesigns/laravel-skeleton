
<div class="btn-group">
	<a href="{{Request::root().BasePath::getPath('deletar/'.$row->$primaryName)}}" class="btn btn-default">
		<span class="icon icon-remove"></span>
		Remover
	</a>
	
	<a href="{{Request::root().BasePath::getPath('', 3).'/'.$row->$primaryName}}" class="btn btn-default">
		<span class="icon icon-edit"></span>
		Editar
	</a>
</div> 
