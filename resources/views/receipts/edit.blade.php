@extends('layout')
@section('title') {{ trans('nav.receipt.update') }} @stop
@section('content')
    <x-top-page :name="trans('nav.receipt.update')"/>

    @include('receipts.form')

@endsection
