@extends('layout.admin')

@section('sidebar')@overwrite
@section('header')@overwrite
@section('footer')@overwrite

@section('main-content')

<div class="login-box">
  <div class="login-logo">
    <img src="{{asset('/images/cms-logo.png')}}">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
  	@if (Session::has('login_errors'))
		<p class="login-box-msg">Usuário e/ou Senha incorretos</p>
	@endif
	
	{{ Form::open(array('name'=>'login', 'method'=>'post', 'url'=>'admin/process-login', 'class'=>'') ) }}  
      <div class="form-group has-feedback">
        {{Form::email('email', null, array('class'=>'form-control', 'placeholder'=>'E-Mail'))}}
      </div>
      <div class="form-group has-feedback">
        {{Form::password('password', array('class'=>'form-control', 'placeholder'=>'Senha'))}}
      </div>
      <div class="row">
        <div class="col-xs-8">
          <!-- <div class="checkbox">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div> -->
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          {{Form::submit('Entrar', array('class'=>'btn btn-primary btn-block btn-flat'))}}
        </div>
        <!-- /.col -->
      </div>
    {{ Form::close() }}

    <a href="#" class="hide">I forgot my password</a><br>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?php /* ?>
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
<?php */ ?>

@stop
