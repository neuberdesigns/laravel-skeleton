<!doctype html>
<html lang="br">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<link rel="icon" type="image/png" href="{{URL::to('favicon.ico')}}" />
	{{HTML::style('styles/bootstrap.min.css')}}
	{{HTML::style('styles/bootstrap-glyphicons.css')}}
	{{HTML::style('styles/style-adm.css')}}
	@yield('styles')
	
	<script type="text/javascript">var baseUrl = '{{URL::to("/")}}/';</script>
	<script type="text/javascript">var baseUrlAdmin = '{{URL::to("/admin")}}/';</script>
	
	@yield('head')
</head>
<body>
	@section('navigation')
	<div id="header" class="container">
		<header class="row">
			<nav class="navbar navbar-default" role="navigation">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Alterar Navegação</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						
						<li class="dropdown {{Request::is('admin/home*') ? 'active' : ''}}">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								Home
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li>
									{{HTML::link('/','Ver Site', array('target'=>'_blank') )}}
								</li>
								
								<li>
									{{HTML::link('admin','Inicio')}}
								</li>
							</ul>
						</li>
						
						<li class="dropdown {{Request::is('admin/') ? 'active' : ''}}">
							{{HTML::link('admin/controller/listagem','Title')}}
						</li>
						
						<li class="dropdown {{Request::is('admin/') ? 'active' : ''}}">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								Section
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li>
									{{HTML::link('admin/controller/listagem','Title')}}
								</li>
							</ul>
						</li>
						
						<li class="dropdown {{Request::is('admin/admin*') ? 'active' : ''}}">
							{{HTML::link('admin/admin/listagem','Admin')}}
						</li>
						
						<li class="">
							{{HTML::link('admin/logout', 'Sair')}}
						</li>
					</ul>
				</div>
			</nav>
		</header>	
	</div><!-- END #header -->
	@show
	
	<div id="main-content" class="container">
		<div class="page-header"/>
		
		@include('global.errors')
		@include('global.flash-save')
		
		@yield('main-content')
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
	</div><!-- END #footer -->
	
	{{HTML::script('scripts/jquery-2.1.4.min.js')}}
	{{HTML::script('scripts/tinymce/tinymce.min.js')}}
	
	{{HTML::script('scripts/bootstrap.min.js')}}
	{{HTML::script('scripts/maskedinput.min.js')}}
	{{HTML::script('scripts/tinyMceConfig.js')}}
	
	{{HTML::script('scripts/ajaxRequest.js')}}
	{{HTML::script('scripts/default-admin.js')}}
	@yield('scripts')
	{{HTML::script('scripts/interface-adm.js')}}
	{{HTML::script('scripts/interface-custom-adm.js')}}
	
	@yield('footer')
</body>
</html>
