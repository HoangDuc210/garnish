@extends('layout')
@section('title') {{ trans('agent.edit_item') }} @stop
@section('content')
    <x-top-page :name="trans('agent.edit_item')"/>

    @include('agent.form')

@endsection
