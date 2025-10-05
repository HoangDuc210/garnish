@extends('layout')
@section('title') {{ trans('nav.receipt.create') }} @stop
@section('content')
    <x-top-page :name="trans('nav.receipt.create')"/>

    @include('receipts.form')

@endsection
