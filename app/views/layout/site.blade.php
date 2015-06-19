<!doctype html>
<html lang="br">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<link rel="icon" type="image/png" href="{{URL::to('favicon.ico')}}" />
	{{HTML::style('styles/bootstrap.min.css')}}
	@yield('css')
	
	<script type="text/javascript">var baseUrl = '{{URL::to("/")}}/';</script>
	<script type="text/javascript">var baseUrlAdmin = '{{URL::to("/admin")}}/';</script>
	
	@yield('head')
</head>
<body>
	@yield('content')
	
	
	@yield('footer')
	
	@yield('scripts')
</body>
</html>
