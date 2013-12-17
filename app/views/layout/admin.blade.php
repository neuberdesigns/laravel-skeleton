<!doctype html>
<html lang="br">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<link rel="icon" type="image/png" href="{{URL::to('favicon.ico')}}" />
	{{HTML::style('styles/bootstrap.min.css')}}
	{{HTML::style('styles/bootstrap-glyphicons.css')}}
	{{HTML::style('styles/style-adm.css')}}
	
	<script type="text/javascript">var baseUrl = '{{URL::to("/")}}/';</script>
	<script type="text/javascript">var baseUrlAdmin = '{{URL::to("/admin")}}/';</script>
	{{HTML::script('scripts/jquery-2.0.3.min.js')}}
	{{HTML::script('scripts/tinymce/tinymce.min.js')}}
	
	{{HTML::script('scripts/bootstrap.min.js')}}
	{{HTML::script('scripts/maskedinput.min.js')}}
	{{HTML::script('scripts/tinyMceConfig.js')}}
	
	{{HTML::script('scripts/multi-image.js')}}
	{{HTML::script('scripts/ajaxRequest.js')}}
	{{HTML::script('scripts/default.js')}}
	
	{{HTML::script('scripts/interface-adm.js')}}
</head>
<body>
	<div id="header" class="container">
		<header class="row">
			<nav class="col-lg-12">
				<ul class="nav navbar-nav">
				@section('navigation')
					<li class="dropdown {{Request::is('admin/home*') ? 'active' : ''}}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							Home
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								{{HTML::link('admin','Inicio')}}
							</li>
						</ul>
					</li>
					
					<li class="dropdown {{Request::is('admin/slider*') ? 'active' : ''}}">
						{{HTML::link('admin/slider/adicionar','Slider')}}
					</li>
					
					<li class="dropdown {{Request::is('admin/blog*') ? 'active' : ''}}">
						{{HTML::link('admin/blog/adicionar','Blog')}}
					</li>
					
					<li class="dropdown {{Request::is('admin/evento*') ? 'active' : ''}}">
						{{HTML::link('admin/evento/adicionar','Evento')}}
					</li>
					
					<li class="dropdown {{Request::is('admin/pagina*') ? 'active' : ''}}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							Pagina
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								{{HTML::link('admin/pagina/editar/sobre','Sobre')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/ensino-fundamental','Ensino Fundamental')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/ensino-medio-regular', 'Ensino Médio Regular')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/ensino-profissionalizante', 'Ensino Profissionalizante')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/administracao', 'Administração')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/automação-industrial', 'Automação Industrial')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/contabilidade', 'Contabilidade')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/cursos-livres', 'Cursos Livres')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/logistica', 'Logística')}}
							</li>
							<li>
								{{HTML::link('admin/pagina/editar/recursos-humanos', 'Recursos Humanos')}}
							</li>
						</ul>
					</li>
					
					<li class="dropdown {{Request::is('admin/blog*') ? 'active' : ''}}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							Seção
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								{{HTML::link('admin/','Item')}}
							</li>
						</ul>
					</li>
					<li>
						{{HTML::link('admin/logout', 'Sair')}}
					</li>
				@show
				</ul>
			</nav>
		</header>	
	</div><!-- END #header -->
	
	<div id="main-content" class="container">
		@if( $errors->count()>0 )
		<div class="alert alert-danger">
			@foreach ($errors->all('<p>:message</p>') as $message)
				{{$message}}
			@endforeach
		</div>
		@endif
		
		<div class="main-content-inner col-lg-12">
			@yield('main-content')
		</div><!-- END #main-content-inner -->
	</div><!-- END #main-content -->
	
	<div id="footer" class="container">
		<footer class="row">
			<div class="copyrights col-lg-6">
				{{HTML::entities('&copy;')}} {{date('Y')}} - Todos os direitos reservados
			</div>
			
			<div class="designedby col-lg-6">
				{{HTML::link('http://neuberdesigns.com.br','Neuber Designs', array('target'=>'_blank') )}}
			</div>
		</footer>
	</div><!-- END #footer
</body>
</html>