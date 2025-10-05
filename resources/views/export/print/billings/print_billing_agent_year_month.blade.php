@extends('export.index')
@section('style')
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/billing.css') }}">
@endsection
@section('content')
    <table class="table-title table ">
        <tbody>
            <tr>
                <td style="width: 80%; padding-right: 10px" class="text-end">
                    <p class="title">　請　求　書　</p>
                </td>
                <td rowspan="2" style="width: 20%" class="text-end">
                    @foreach($pages as $key => $page)
                    Page : {{ $page ? $key : 0 }} / {{ $page }}
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="text-end" style="padding-top: 5px">
                    <p class="date">{{ $lastDateOfMonth }} 締切分</p>
                </td>
            </tr>
        </tbody>
    </table>
    <div  class="position-image">
        <img src="{{ asset('img/icon/condau.png')}}" alt="">
    </div>
    <table class="table-company-agent" >
        <tbody>
            <tr>
                <td style="width: 60%">{{ trans('agent.zip_code') }} {{ $agent->post_code }}</td>
                <td style="width: 40%">
                    {{ $company->address }}

                </td>
            </tr>
            <tr>
                <td>{{ $agent->address }} {{ $agent->address_more }}</td>
                <td class="name">
                    {{ $company->name }}
                </td>
            </tr>
            <tr>
                <td>{{ $agent->name }} 御中</td>
                <td style="padding-left: 50px">{{ trans('company.tel') }} {{ $company->tel }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 50px">
                    {{ trans('company.fax') }} {{ $company->fax }}
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table-bank">
        <tr>
            <td>
                <p>毎度ありがとうございます。</p>
                <p>下記の通り御請求申し上げます。</p>
            </td>
            <td style="width: 17%">
                【お振込先】
            </td>
            <td style="width: 24%">
                {{ $company->bank_account }}
            </td>
        </tr>
    </table>

    <table class="table table-deposit table-bordered mb-4">
        <thead>
            <tr>
                <th style="width: 15%" class="bg-ccc">{{ trans('billing.last_billed_amount') }}</th>
                <th style="width: 13%" class="bg-ccc">{{ trans('billing.deposit_amount') }}</th>
                <th style="width: 13%" class="bg-ccc">{{ trans('billing.carried_forward_amount') }}</th>
                <th style="width: 13%" class="bg-ccc">{{ trans('billing.receipt_amount') }}</th>
                <th style="width: 13%" class="bg-ccc">{{ trans('billing.tax_amount') }}</th>
                <th style="width: 15%" class="bg-ccc">{{ trans('billing.billing_amount') }}</th>
                <th style="width: 2%; border-top: 0px; border-bottom: 0px; "></th>
                <th style="border-bottom: 0px; width: 15%"></th>
                <th style="border-bottom: 0px; width: 15%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-end">{{ number_format($billing?->last_billed_amount) }}</td>
                <td class="text-end">{{ number_format($billing?->deposit_amount) }}</td>
                <td class="text-end">{{ number_format($billing?->carried_forward_amount) }}</td>
                <td class="text-end">{{ number_format($billing?->final_receipt_amount) }}</td>
                <td class="text-end">{{ number_format($billing?->tax_amount) }}</td>
                <td class="text-end">{{ number_format($billing?->billing_amount) }}</td>
                <td style="border-top: 0px; border-bottom: 0px;"></td>
                <td style="border-top: 0px; 0px"></td>
                <td style="border-top: 0px; 0px"></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-item table-bordered">
        <thead>
            <tr>
                <th class="w-15 bg-ccc">{{ trans('billing.transaction_date') }}</th>
                <th class="w-15 bg-ccc">{{ trans('billing.receipt_id') }}</th>
                <th class="w-15 bg-ccc">{{ trans('billing.deposit_amount') }}</th>
                <th class="w-15 bg-ccc">{{ trans('billing.receipt_total_amount') }}</th>
                <th class="bg-ccc">{{ trans('billing.receipt_memo') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($billingReceipts as $billingReceipt)
                <tr>
                    <td class="text-center">{{ $billingReceipt['transaction_date'] }}</td>
                    <td class="text-center">{{ $billingReceipt['code'] }}</td>
                    <td class="text-end">
                        {{ $billingReceipt['is_deposit'] || $billingReceipt['is_total']
                            ? number_format($billingReceipt['deposit_amount'])
                            : '' }}
                    </td>
                    <td class="text-end">
                        {{ $billingReceipt['is_receipt'] || $billingReceipt['is_total'] || $billingReceipt['is_sub_total']
                            ? number_format($billingReceipt['total_amount'])
                            : '' }}
                    </td>
                    <td>{{ $billingReceipt['memo'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
