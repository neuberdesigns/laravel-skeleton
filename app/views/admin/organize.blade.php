@extends('layout.admin')

@section('styles')
	{{HTML::style('scripts/jquery-ui/css/jquery-ui-1.10.3.custom.min.css')}}
@endsection

@section('scripts')
	{{HTML::script('scripts/jquery-ui/jquery-ui-1.10.3.custom.min.js')}}
@endsection

@section('main-content')
	<h3>Organizar</h3>
	
	<button type="button" class="btn btn-primary bt-save-order">
		Salvar Ordem
		<span class="ajaxrequest-loader"></span>
	</button>
	<br /><br />
	
	<ul class="sortable ui-sortable clearfix organize-itens">
	@foreach( $list as $k=>$item )
		@include('admin.'.$controllerName.'.organize-item')
	@endforeach
	</ul>
	
	<button type="button" class="btn btn-primary bt-save-order">
		Salvar Ordem
		<span class="ajaxrequest-loader"></span>
	</button>
@endsection