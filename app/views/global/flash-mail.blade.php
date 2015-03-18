@if( Session::has('mailsend') )
	<div data-alert class="alert alert-info alert-dismissable alert-box info">
		
		{{Session::get('mailsend')}}
	</div>
@endif
