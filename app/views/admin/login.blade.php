@extends('layout.admin')

@section('navigation')
@overwrite

@section('main-content')

{{ Form::open(array('name'=>'login', 'method'=>'post', 'url'=>'admin/process-login', 'class'=>'row login col-lg-3') ) }}  
	<fieldset>
			<legend>Login</legend>
		@if (Session::has('login_errors'))
			<p class="text-error text-center"><b>Usuário e/ou Senha incorretos</b></p>            
		@endif 
		<ul>
			<li>
				{{ Form::label('email', 'E-mail') }}
				{{ Form::text('email', null, array('class'=>'span3 form-control')) }}
			</li>
			
			<li>
				{{ Form::label('password', 'Senha') }}
				{{ Form::password('password', array('class'=>'span3 form-control')) }}
			</li>
			
			<li>
				{{Form::submit('Entrar', array('class'=>'btn btn-primary pull-right') )}}
			</li>
		</ul>
	</fieldset>
{{ Form::close() }}

@stop