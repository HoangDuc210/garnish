@extends('layout')
@section('title') {{ trans('nav.receipt_maruto.detail') }} @stop
@section('content')
    <x-top-page :name="trans('nav.receipt_maruto.detail')"/>
    <x-alert />
    @include('receipt-maruto.detail.agent')
    @include('receipt-maruto.detail.product')

@endsection
