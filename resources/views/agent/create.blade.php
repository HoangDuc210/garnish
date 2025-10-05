@extends('layout')
@section('title') {{ trans('agent.create_new') }} @stop
@section('content')
    <x-top-page :name="trans('agent.create_new')"/>

    @include('agent.form')

@endsection
