@extends('layout')
@section('title') {{ trans('user.create_new') }} @stop
@section('content')
    <x-top-page :name="trans('user.create_new')"/>

    @include('user.form')

@endsection
