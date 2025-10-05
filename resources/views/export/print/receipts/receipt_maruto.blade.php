@extends('export.index')
@section('style')
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/receipt.css') }}">
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/receipt_n335.css') }}">
@endsection
@section('content')
    <div class="print paper-a5 m-auto" style="padding-top: 42px;">
        @include('export.print.receipts.n335.maruto.title')
        @include('export.print.receipts.n335.maruto.agent_company')

        <table class="table-bordered table-receipt-maruto-n335" style="margin-top: 72px">
            <tbody>
                @foreach ($receiptDetails as $detail)
                    <tr>
                        <td class="col-name">{{ $detail->name_for_receipt }}</td>
                        <td class="text-end col-quantity">{{ number_format($detail->quantity, 1, '.', '') }}</td>
                        <td class="text-end col-price letter-space">{{ Util::formatMoneyPrint($detail->price) }}</td>
                        <td class="text-end col-amount letter-space" style="padding-right: 5px;">{{ Util::formatMoneyPrint($detail->amount) }} </td>
                        <td class="col-memo"></td>
                    </tr>
                @endforeach
                @if(count($receiptDetails) < 12)
                @for($i = 0; $i < 12 - count($receiptDetails); $i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
                @endif
            </tbody>
        </table>
        <table class="table-bordered table-total-maruto table-receipt-maruto-n335" style="margin-top:10px">
            <tbody>
                <tr>
                    <td rowspan="3" class="col-name"></td>
                    <td rowspan="2" class="col-quantity"></td>
                    <td class="col-price"></td>
                    <td class="text-end letter-space col-amount">
                        @if ($receipt->tax < 10)
                            {{ Util::formatMoneyPrint($receipt->price_total_product) }}
                        @endif
                    </td>
                    <td class="vertical-bottom col-memo" style="padding: 0px; padding-top: 15px; padding-left: 15px;">
                        @if ($receipt->tax < 10 && $consumptionTax > 0 )
                            {{ $consumptionTax }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-end letter-space">
                        @if ($receipt->tax >= 10)
                        {{ Util::formatMoneyPrint($receipt->price_total_product) }}
                        @endif
                    </td>
                    <td class="vertical-bottom" style="padding: 0px; padding-top: 15px; padding-left: 15px;">
                        @if ($receipt->tax >= 10 && $consumptionTax > 0 )
                            {{ $consumptionTax }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-end letter-space p-0" >{{ Util::formatMoneyPrint($receipt->price_total_product) }} </td>
                </tr>
            </tbody>
        </table>
    </div>
@stop
