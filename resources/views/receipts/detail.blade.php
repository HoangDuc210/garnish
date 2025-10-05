@extends('layout')
@section('title') {{ trans('nav.receipt.detail') }} @stop
@section('content')
    <x-top-page :name="trans('nav.receipt.detail')"/>
    <x-alert />
    @include('receipts.detail.agent')
    @include('receipts.detail.product')
@endsection
