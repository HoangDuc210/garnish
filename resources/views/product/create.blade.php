@extends('layout')
@section('title') {{ trans('product.create_new') }} @stop
@section('content')
<x-top-page :name="trans('product.create_new')"/>

    @include('product.form')

@endsection
