@extends('export.index')
@section('style')
    <link rel="stylesheet" media="print"  href="{{ asset('css/theme/export/receipt.css') }}">
@endsection
@section('content')
    <table class="mb-3">
        <tr>
            <td class="w-30"><p class="title">{{ $title }}</p></td>
            <td class="w-30 text-center ">
                <p class="mb-0 w-100 border-bottom">{{ $transaction_date }}</p>
            </td>
            <td class="w-30 text-end">
                <p class="mb-0 w-100 border-bottom">{{ trans('app.stt') }} {{ $receipt->code }}</p>
            </td>
        </tr>
    </table>

    <table class="table-info mb-3">
        <tr>
            <td class="vertical-middle w-55">
                <span class="mb-0 border-bottom">{{ $agent->name }}　　</span>
                <span >様</span>
            </td>
            <td class="w-45">
                <p>{{ $company->name }}</p>
                <p>{{ trans('app.zip_code') }} {{ $company->post_code }}</p>
                <p>{{ $company->address }}</p>
                <p>{{ trans('company.tel') }} {{ $company->tel }}</p>
                <p>{{ trans('company.fax') }} {{ $company->fax }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">{{ $description }}</td>
        </tr>
    </table>

    <table class="table table-bordered table-product repeater">
        <thead>
            <tr>
                <th style="width: 8%;">No.</th>
                <th>{{ trans('receipt.print.N335.product.name') }}</th>
                <th class="text-center" class="w-10">{{ trans('receipt.export.quantity') }}</th>
                <th class="text-center" class="w-10">{{ trans('product.unit') }}</th>
                <th class="text-center" class="w-15">{{ trans('product.price') }}</th>
                <th class="text-center" class="w-15">{{ trans('product.total_price') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receiptDetails as $key => $detail)
                <tr>
                    <td class="text-center">{{ sprintf('%02d', ++$key) }}</td>
                    <td>
                        {{ $detail->name_for_receipt }}
                    </td>
                    <td class="text-end">
                        {{ number_format((float) $detail->quantity, $precision = 1, '.', '') }}
                    </td>
                    <td class="text-center">
                        {{ $detail->unit->name }}
                    </td>
                    <td class="text-end">
                        {{ $detail->price_fm }}
                    </td>
                    <td class="text-end">
                        {{ number_format($detail->amount) }}
                    </td>
                </tr>
            @endforeach

            @if(count($receiptDetails) < 20)
            @for($i = 0; $i <= 19 - count($receiptDetails); $i++)
                <tr>
                    <td class="text-center">{{ sprintf('%02d', count($receiptDetails) + $i + 1) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-start" style="background-color:#ccc">{{ trans('receipt.print.total') }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-end">{{ number_format($total_amount) }}</td>
            </tr>
        </tfoot>
    </table>
@stop
