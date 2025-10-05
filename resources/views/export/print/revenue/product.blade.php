@extends('export.index')
@section('title')
    {{ trans('revenue.product.preview_title') }}
@endsection
@section('style')
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/revenue.css') }}">
@endsection
@section('content')
<div class="revenue fs-14">
    <table class="table-head">
        <tr>
            <th class="text-start vertical-bottom">
                {{ trans('revenue.aggregation_period') }}：
                <span class="font-normal">{{ $data['revenue_date'] }}</span>
            </th>
            <th class="text-center title w-45" style="font-weight: bold; font-size: 18px">
                {{ $data['title_print'] }}
            </th>
        </tr>
        <tr>
            <th class="text-start pt-10">
                {{ trans('revenue.choose_agent') }}
                <span class="font-normal"> {{ $data['agent'] }}</span>
            </th>
            <th class="text-end pt-10">
                {{ trans('revenue.created_date') }}
                <span class="font-normal"> {{ $data['created_date'] }}</span>
            </th>
        </tr>
    </table>
    <table class="table-bordered">
        <thead>
            <tr>
                <th>{{ trans('app.stt') }}</th>
                <th>{{ trans('product.name') }}</th>
                <th>{{ trans('revenue.quantity') }}</th>
                <th>{{ trans('app.unit') }}</th>
                <th>{{ trans('revenue.sales_amount') }}</th>
                <th>{{ trans('revenue.average_price') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['productRevenueList'] as $key => $revenue)
                <tr>
                    <td class="text-center">{{ sprintf('%03d', ++$key) }}</td>
                    <td>{{ $revenue['name'] }}</td>
                    <td class="text-center">{{ $revenue['quantity'] }}</td>
                    <td class="text-center">{{ $revenue['unit'] }}</td>
                    <td class="text-end">{{ $revenue['amount'] }}</td>
                    <td class="text-end">{{ $revenue['price'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
