@extends('layout')
@section('title') {{ trans('deposit.create_page') }} @stop
@section('content')
    <x-top-page :name="trans('deposit.create_page')"/>
    @include('deposits.form')
@endsection
