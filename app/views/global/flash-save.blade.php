@if( Session::has('save_success') )
	<div class="alert alert-success alert-dismissable" role="alert">
		<button type="button" class="close" data-dismiss="alert">
			<span aria-hidden="true">&times;</span>
			<span class="sr-only">Close</span>
		</button>
		{{Session::get('save_success')}}
	</div>
@endif

@if( Session::has('save_fail') )
	<div class="alert alert-danger alert-dismissable" role="alert">
		<button type="button" class="close" data-dismiss="alert">
			<span aria-hidden="true">&times;</span>
			<span class="sr-only">Close</span>
		</button>
		{{Session::get('save_fail')}}
	</div>
@endif
