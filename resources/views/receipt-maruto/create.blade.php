@extends('layout')
@section('title') {{ trans('nav.receipt_maruto.create') }} @stop
@section('content')
    <x-top-page :name="trans('nav.receipt_maruto.create')"/>
    @include('receipt-maruto.form')
@endsection
