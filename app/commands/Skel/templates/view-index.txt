@extends('layout.admin')

@section('main-content')
	@include('admin.'.$viewSegment.'.add')
	@include('admin.'.$viewSegment.'.list')
@endsection
