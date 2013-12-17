@extends('layout.email')
@section('content')
	@foreach( $fields as $name=>$value)
	<tr>
		<td><strong>{{ucwords($name)}}</strong></td>
		<td colspan="2" valign="top">{{nl2br($value)}}</td>
	</tr>
	@endforeach
@endsection