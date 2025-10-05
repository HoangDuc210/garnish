@extends('export.index')
@section('content')
    <table class="table-bordered">
        <thead>
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data['billings'] as $billing)
                <tr>
                    <td>{{ '="' .  $billing->billingAgent?->code . '"'}}</td>
                    <td>{{ $billing->billingAgent?->name }}</td>
                    <td class="text-right">{{ number_format($billing->last_billed_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->deposit_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->carried_forward_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->final_receipt_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->tax_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->billing_amount) }}</td>
                </tr>
            @endforeach

            <tr>
                <td class="text-center"></td>
                <td>【 総 合 計 】</td>
                <td class="text-right">{{ number_format($data['totalBillings']['last_billed_amount']) }}</td>
                <td class="text-right">{{ number_format($data['totalBillings']['deposit_amount']) }}</td>
                <td class="text-right">{{ number_format($data['totalBillings']['carried_forward_amount']) }}</td>
                <td class="text-right">{{ number_format($data['totalBillings']['final_receipt_amount']) }}</td>
                <td class="text-right">{{ number_format($data['totalBillings']['tax_amount']) }}</td>
                <td class="text-right">{{ number_format($data['totalBillings']['billing_amount']) }}</td>
            </tr>

        </tbody>
    </table>
@stop
