@extends('layout.admin')

@section('main-content')
	@include('admin.'.$controllerSegment.'.add')
	@include('admin.'.$controllerSegment.'.list')
@endsection
