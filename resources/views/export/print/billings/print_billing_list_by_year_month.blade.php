@extends('export.index')
@section('style')
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/billing.css') }}">
@endsection
@section('content')
    <table class="table mb-3">
        <tbody>
            <tr>
                <td class="text-end">
                    <p class="title">　請 求 一 覧 表　</p>
                </td>
            </tr>
            <tr>
                <td class="text-end" style="padding-top: 5px">
                    作成日: {{ $createdDate }}
                </td>
            </tr>
            <tr>
                <td class="text-start" style="padding-top: 5px">
                    請求年月: {{ $defaultYearMonth }}
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-deposit table-bordered mb-0">
        <thead>
            <tr>
                <th class="bg-ccc">{{ trans('billing.id') }}</th>
                <th class="bg-ccc">{{ trans('billing.billing_agent_name') }}</th>
                <th class="bg-ccc">{{ trans('billing.last_billed_amount') }}</th>
                <th class="bg-ccc">{{ trans('billing.deposit_amount') }}</th>
                <th class="bg-ccc">{{ trans('billing.carried_forward_amount') }}</th>
                <th class="bg-ccc">{{ trans('billing.receipt_amount') }}</th>
                <th class="bg-ccc">{{ trans('billing.tax_amount') }}</th>
                <th class="bg-ccc">{{ trans('billing.billing_amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($billings as $billing)
                <tr>
                    <td>{{ $billing->billingAgent?->code }}</td>
                    <td>{{ $billing->billingAgent?->name }}</td>
                    <td class="text-right">{{ number_format($billing->last_billed_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->deposit_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->carried_forward_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->final_receipt_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->tax_amount) }}</td>
                    <td class="text-right">{{ number_format($billing->billing_amount) }}</td>
                </tr>
            @endforeach
            <tr class="tr-bold">
                <td class="text-center"></td>
                <td>【 総 合 計 】</td>
                <td class="text-right">{{ number_format($totalBillings['last_billed_amount']) }}</td>
                <td class="text-right">{{ number_format($totalBillings['deposit_amount']) }}</td>
                <td class="text-right">{{ number_format($totalBillings['carried_forward_amount']) }}
                </td>
                <td class="text-right">{{ number_format($totalBillings['final_receipt_amount']) }}</td>
                <td class="text-right">{{ number_format($totalBillings['tax_amount']) }}</td>
                <td class="text-right">{{ number_format($totalBillings['billing_amount']) }}</td>
            </tr>
        </tbody>
    </table>
@stop
