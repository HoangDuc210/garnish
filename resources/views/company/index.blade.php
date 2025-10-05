@extends('layout')
@section('title') {{ trans('nav.company_management') }} @stop
@section('content')
    <x-top-page :name="trans('nav.company_management')" />

    @include('company.form')
@endsection
