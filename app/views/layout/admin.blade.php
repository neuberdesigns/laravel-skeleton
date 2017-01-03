<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>@yield('title')</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	{{HTML::style('/bootstrap/css/bootstrap.min.css')}}
	
	<!-- Font Awesome -->
	{{HTML::style('/css/font-awesome.min.css')}}
	
	<!-- Ionicons -->
	{{HTML::style('/css/ionicons.min.css')}}
	
	<!-- Theme style -->
	{{HTML::style('/css/AdminLTE.min.css')}}
	
	<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
	{{HTML::style('/css/skins/skin-blue.min.css')}}
	
	<!-- iCheck -->
	{{-- HTML::style('/plugins/iCheck/flat/blue.css') --}}
	
	<!-- Morris chart -->
	{{--HTML::style('/plugins/morris/morris.css')--}}
	
	<!-- jvectormap -->
	{{-- HTML::style('/plugins/jvectormap/jquery-jvectormap-1.2.2.css') --}}
	
	<!-- bootstrap data tables - text editor -->
	{{HTML::style('/bootstrap/css/dataTables.bootstrap.css')}}
	
	<!-- bootstrap toogle - text editor -->
	{{HTML::style('/plugins/bootstrap-toggle/bootstrap-toggle.min.css')}}
	
	{{HTML::style('/css/app-admin.css')}}
	@yield('css')
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<script type="text/javascript">var baseUrl = '{{URL::to("/")}}/';</script>
	<script type="text/javascript">var baseUrlAdmin = '{{URL::to("/admin")}}/';</script>
	@yield('head')
	
</head>
<body class="hold-transition skin-blue sidebar-mini {{$isLoginPage?'login-page':''}}">
	<div class="{{!$isLoginPage?'wrapper':''}}">
		@section('header')
		<header class="main-header">
			<!-- Logo -->
			<a href="{{URL::to('/admin/dashboard')}}" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"></span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg"><img src="{{asset('/images/cms-logo.png')}}"></span>
			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Menu</span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						
						<li class="user user-menu">
							{{HTML::link('admin/sair', trans('admin.logout'), array('class'=>'btn btn-danger btn-flat'))}}
						</li>
					</ul>
				</div>
			</nav>
		</header>
		@show
		
		@section('sidebar')
		<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
			@section('navigation')
				<!-- sidebar menu: : style can be found in sidebar.less -->
				@include('layout.menu')
			@show
			</section>
			<!-- /.sidebar -->
		</aside>
		@show
		
		<!-- Content Wrapper. Contains page content -->
		<div class="{{$isLoginPage?'content':'content-wrapper'}}">
			@if(!$isLoginPage)
			<section class="content-header">
				<h1>{{$controllerTitle or ''}}</h1>
				<ol class="breadcrumb hide">
					<li><a href="#"><i class="fa fa-dashboard"></i> {{trans('admin.home')}}</a></li>
					<li class="active">{{trans('admin.dashboard')}}</li>
				</ol>
			</section>
			@endif
			
			<!-- Main content -->
			<section class="content">
				@include('global.errors')
				@include('global.flash-save')
				
				@yield('main-content')
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
		
		@section('footer')
		<footer class="main-footer text-right">
			{{trans('admin.copyrights', array('year'=>date('Y'), 'link'=>'http://www.nibler.com.br', 'company'=>'Agência Nibler'))}}
		</footer>
		@show
	</div><!-- ./wrapper -->

	<!-- jQuery 2.2.0 -->
	{{HTML::script('/plugins/jQuery/jQuery-2.2.0.min.js')}}
	
	
	{{HTML::script('/bootstrap/js/bootstrap.min.js')}}

	<!-- Morris.js charts -->
	{{--HTML::script('/plugins/morris/raphael-min.js')--}}
	{{--HTML::script('/plugins/morris/morris.min.js')--}}

	<!-- Sparkline -->
	{{HTML::script('/plugins/sparkline/jquery.sparkline.min.js')}}

	<!-- jvectormap -->
	{{-- HTML::script('/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') --}}
	{{-- HTML::script('/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') --}}

	<!-- jQuery Knob Chart -->
	{{-- HTML::script('/plugins/knob/jquery.knob.js') --}}

	<!-- daterangepicker -->
	{{-- HTML::script('/js/moment.min.js') --}}
	{{-- HTML::script('/plugins/daterangepicker/daterangepicker.js') --}}

	<!-- datepicker -->
	{{-- HTML::script('/plugins/datepicker/bootstrap-datepicker.js') --}}

	
	<!-- Slimscroll -->
	{{-- HTML::script('/plugins/slimScroll/jquery.slimscroll.min.js') --}}

	<!-- FastClick -->
	{{-- HTML::script('/plugins/fastclick/fastclick.js') --}}

	<!-- AdminLTE App -->
	{{HTML::script('/js/appAdminLTE.js')}}

	{{HTML::script('/plugins/input-mask/jquery.inputmask.js')}}
	{{HTML::script('/plugins/input-mask/jquery.inputmask.extensions.js')}}
	
	<!-- bootstrap toogle -->
	{{HTML::script('/plugins/bootstrap-toggle/bootstrap-toggle.min.js')}}
	
	{{HTML::script('/plugins/tinymce/tinymce.min.js')}}
	{{HTML::script('/js/tinyMceConfig.js')}}
	
	{{HTML::script('/js/AjaxRequest.js')}}
	@yield('scripts')
	
	{{HTML::script('/js/interface-adm.js')}}
	{{HTML::script('/js/interface-custom-adm.js')}}
	
	@yield('endbody')
</body>
</html>
