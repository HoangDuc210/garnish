@extends('export.index')
@section('style')
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/receipt.css') }}">
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/receipt_n335.css') }}">
@endsection
@section('content')
    <div class="print m-auto n335 receipt-normal" style="padding-top: 125px">
        @include('export.print.receipts.n335.receipt.title')
        @include('export.print.receipts.n335.receipt.agent_company')
        <table class="table-bordered table-receipt-normal" style="margin-top: 94px">
            <tbody>
                @foreach ($receiptDetails as $key => $detail)
                    <tr>
                        <td class=" col-name">{{ $detail->name_for_receipt }}</td>
                        <td class="text-end  col-quantity pr-9px">{{ number_format($detail->quantity, 1, '.', '') }}</td>
                        <td class="text-end  col-price pr-9px">{{ $detail->price_fm }}</td>
                        <td class="text-end  col-amount letter-space " style="padding-right: 20px">
                            {{ Util::formatMoneyPrint($detail->amount) }}
                        </td>
                        <td class="text-center" style="width: 8%"></td>
                        <td class=" col-unit text-center" >{{ $detail->unit->name }}　</td>
                    </tr>
                @endforeach
                @for($i = count($receiptDetails); $i < 12; $i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <table class="table-bordered table-receipt-normal">
            <tbody>
                <tr>
                    <td rowspan="2" style="width: 37%;">
                        <img src="{{ asset('img/background/background-circle.png') }}"  class="img-circle"
                            @if(!$receipt->is_tax)
                            style="margin-left: 13%; padding-bottom: 30px"
                            @elseif($receipt->is_tax)
                            style="margin-left: 22%; padding-bottom: 30px"
                            @endif
                        >
                    </td>
                    <td colspan="2" class="text-end" style="width: 20%; padding-bottom: 30px;
                        @if(!$receipt->is_tax)
                            padding-right: 45px;
                        @endif
                    ">
                        @if($receipt->tax < 10)
                        {{ $receipt->tax }}
                        @endif
                    </td>
                    <td class="text-end  col-amount letter-space" style="width: 28%; padding-bottom: 30px; padding-right: -5px;
                        @if(!$receipt->is_tax)
                            padding-right: 35px;
                        @endif
                    ">
                        @if($receipt->tax < 10 && $receipt->is_tax)
                            {{ Util::formatMoneyPrint($receipt->total_receipt_amount) }}
                        @elseif($receipt->tax < 10 && !$receipt->is_tax)
                            {{ Util::formatMoneyPrint($receipt->price_total_product) }}
                        @endif
                    </td>
                    <td colspan="2" style="width: 12%; padding-bottom: 30px" class="text-center ">
                        @if($receipt->tax < 10 && $receipt->is_tax)
                        {{ number_format($receipt->consumptionTax) }}
                        @elseif(!$receipt->is_tax)
                        {{ '' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end "  style="padding-bottom: 30px;
                        @if(!$receipt->is_tax)
                            padding-right: 45px;
                        @endif
                    ">
                        @if($receipt->tax >= 10)
                        {{ $receipt->tax }}　
                        @endif
                    </td>
                    <td class="text-end  letter-space" style="padding-bottom: 30px; padding-right: 8px;
                        @if(!$receipt->is_tax)
                            padding-right: 45px;
                        @endif
                    ">
                        @if($receipt->tax >= 10 && $receipt->is_tax)
                            {{ Util::formatMoneyPrint($receipt->total_receipt_amount) }}
                        @elseif($receipt->tax >= 10 && !$receipt->is_tax)
                            {{ Util::formatMoneyPrint($receipt->price_total_product) }}
                        @endif
                    </td>
                    <td colspan="2" class="text-center " style="padding-bottom: 30px">
                        @if($receipt->is_tax && $receipt->tax >= 10)
                        {{ number_format($receipt->consumptionTax) }}
                        @elseif(!$receipt->is_tax)
                        {{ '' }}
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@stop
