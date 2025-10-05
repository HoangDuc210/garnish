@extends('export.index')
@section('content')
    <table class="table-head">
        <tr>
            <td>{{ trans('receipt.export.code') }}</td>
            <td>{{ $data->code }}</td>
        </tr>
        <tr>
            <td>{{ trans('receipt.export.trade_date') }}</td>
            <td>{{ $data->transaction_date_fm }}</td>
        </tr>
        <tr>
            <td>{{ trans('receipt.export.customer') }}</td>
            <td>{{ $data->agent->code }}：{{ $data->agent->name }}</td>
        </tr>
    </table>

    <table class="table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>{{ trans('product.code') }}</th>
                <th>{{ trans('product.name') }}</th>
                <th>{{ trans('product.quantity') }}</th>
                <th>{{ trans('product.unit') }}</th>
                <th>{{ trans('product.price') }}</th>
                <th>{{ trans('product.total_price') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->receiptDetails as $key => $receipt)
                <tr>
                    <td class="text-center">{{ sprintf('%02d', ++$key) }}</td>
                    <td>
                        {{ $receipt->code }}
                    </td>
                    <td>
                        {{ $receipt->name }}
                    </td>
                    <td>
                        {{ $receipt->quantity }}
                    </td>
                    <td>
                        {{ $receipt->unit->name }}
                    </td>
                    <td>
                        {{ $receipt->price_fm }}
                    </td>
                    <td>
                        {{ number_format($receipt->amount) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>【{{ trans('receipt.maruto.total') }}】</td>
                <td>{{ number_format($data->price_total_product) }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>【{{ trans('receipt.maruto.tax') }}】</td>
                <td>{{ $data->tax }}%</td>
                <td>【{{ trans('receipt.maruto.consumption_tax') }}】</td>
                <td>{{ number_format($data->consumption_tax) }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>【{{ trans('receipt.maruto.total_amount') }}】</td>
                <td>{{ number_format($data->total_receipt_amount) }}</td>
            </tr>
        </tfoot>
    </table>
@stop
