@extends('layout')
@section('title') {{ trans('nav.receipt_maruto.update') }} @stop
@section('content')
    <x-top-page :name="trans('nav.receipt_maruto.update')"/>

    @include('receipt-maruto.form')

@endsection
