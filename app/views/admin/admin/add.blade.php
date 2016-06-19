@extends('layout.admin')

@section('main-content')
	<a href="{{URL::to('/admin/'.$controllerSegment.'/listagem')}}" class="btn btn-primary btn-lg">Voltar a Listagem</a>
	<h3>{{ucwords( str_replace('-', ' ', $controllerSegment) )}}</h3>
	
	{{Form::model($model, array('class'=>'form-horizontal', 'files'=>true) )}}
	<fieldset>
		{{InputFactory::create('text')->name('name', 'Nome')->size(4)->build()}}
		{{InputFactory::create('text')->name('email', 'E-mail')->size(4)->build()}}
		{{InputFactory::create('password')->name('password', 'Senha')->size(3)->build()}}
				
		{{Form::submit('Salvar', array('class'=>'btn btn-primary pull-right') )}}
	</fieldset>
	{{Form::close()}}
@endsection
