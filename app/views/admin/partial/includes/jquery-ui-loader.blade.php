@section('styles')
	{{HTML::style('plugins/jQueryUI/jquery-ui.min.css')}}
@append

@section('scripts')
	<!-- jQuery UI 1.11.4 -->
	{{HTML::script('/plugins/jQueryUI/jquery-ui.min.js')}}
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button);
	</script>
@append
