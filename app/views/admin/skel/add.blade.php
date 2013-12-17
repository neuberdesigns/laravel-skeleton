@extends('layout.admin')

@section('main-content')
	<h3>{{ucwords($controllerName)}}</h3>
	{{Form::model($model, array('class'=>'form-horizontal', 'files'=>true) )}}
	<fieldset>
		
		
		{{Form::submit('Salvar', array('class'=>'btn btn-primary pull-right') )}}
	</fieldset>
	{{Form::close()}}
@endsection