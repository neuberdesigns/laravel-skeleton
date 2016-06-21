@extends('layout.site')

@section('content')
<h1>I'm working!</h1>

<button id="ajax-test" class="ajax-loader">Test Ajax 1</button>
<button id="ajax-test-2" class="loader-2">Test Ajax 2</button>

<?php
var_dump(Request::segments());

?>
<script src="js/AjaxRequest.js"></script>
<script>
	var ajaxRequest = new AjaxRequest();
	//ajaxRequest.setBaseUrl(baseUrlAdmin);
	
	$('#ajax-test').on('click', function(){
		ajaxRequest.get().endpoint('test-ajax').data({name:'Neuber', age:10}).request().then(
			function(resp){
				console.log('HAR!');
				console.log(resp);
			},
			function(resp){
				console.log('Oohh Boy :(');
				console.log(resp);
			}
		);
	});
	
	$('#ajax-test-2').on('click', function(){
		ajaxRequest.endpoint('test-ajax').data({name:'Lilian', age:20, gender:'female'}).request('.loader-2');
	});
</script>
@endsection
