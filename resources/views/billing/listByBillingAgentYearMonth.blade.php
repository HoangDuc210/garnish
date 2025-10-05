@extends('layout')
@section('title') {{ trans('billing.listByBillingAgentYearMonth') }} @stop
@section('content')
    <x-top-page :name="trans('billing.listByBillingAgentYearMonth')" />

    <x-alert />

    <div class="billing">
        <div class="card mb-5 no-print">
            <div class="card-body ">
                <x-form::open :action="route(BILLING_STORE_BY_BILLING_AGENT_YEAR_MONTH_ROUTE)" class="enter-index-form mb-0">
                    <div class="row align-items-center mb-3">
                        <div class="col-2">
                            <label for="">{{ trans('billing.billing_agent') }}</label>
                        </div>
                        <div class="col-4">
                            <input class="form-control" name="input_billing_agent_id" />
                        </div>
                        <div class="col-6">
                            <select name="billing_agent_id" class="form-select"
                                data-selectedValue="{{ request('billing_agent_id') }}">
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-2">
                            <label for="">{{ trans('billing.calculate_date') }}</label>
                        </div>

                        <div class="col-4">
                            <x-form::select name="calculate_date" select2 :options="$yearMonthOptions" :selected="request('calculate_date', $defaultYearMonth)" />
                        </div>

                        <div class="col-6">
                            <div class="calculate-date-info"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start submit-btn">
                            <i data-acorn-icon="save"></i>
                            <span>{{ trans('billing.aggregate') }}</span>
                        </button>

                        @if ($billingAgent)
                            <button type="button"
                                class="btn btn-primary btn-icon btn-icon-start " id="print-billing-agent-year-month">
                                <i data-acorn-icon="print"></i>
                                <span>{{ trans('billing.print') }}</span>
                            </button>

                            <button type="button" class="btn btn-primary btn-icon btn-icon-start export-csv-trigger"
                                data-url="{{ route(BILLING_EXPORT_CSV_BY_BILLING_AGENT_YEAR_MONTH_ROUTE) }}">
                                <i class="fa-solid fa-file-csv"></i>
                                <span>{{ trans('billing.export') }}</span>
                            </button>
                        @endif
                    </div>
                </x-form::open>
            </div>
        </div>
        @if ($billingAgent && $billing)
            <div class="card mb-5 card-print">
                <div class="card-body">
                    <div class="preview-container">
                        <div class="row align-items-center">
                            <div class="header text-right col-10">
                                <div class="d-inline-block text-center">
                                    <div class="text-center"><span class="title p-0 px-4 border border-dark">請　求　書</span> </div>
                                    <div class="deadline"><span class="formatted-deadline-date"></span> 締切分</div>
                                </div>
                            </div>
                            <div class="page-index data-table-pagination-info col-2 pb-1">
                                Page : <span class="page-current"></span> / <span class="page-count"></span>
                            </div>
                        </div>
                        <div class="info row mt-5">
                            <div class="billing-agent col-8 item">
                                <div class="post-code">〒{{ $billingAgent?->post_code }}</div>
                                <div class="address">{{ $billingAgent?->address }}{{ $billingAgent?->address_more }}</div>
                                <div class="name">{{ $billingAgent?->name }} 御中</div>
                            </div>
                            <div class="company col-4 item" >
                                <div class="address">{{ $company->address }} {{ $company->address_more }}</div>
                                <div class="name">{{ $company->name }}</div>
                                <div class="tel text-center">TEL {{ $company->tel }}</div>
                                <div class="fax text-center">FAX {{ $company->fax }}</div>
                                <div class="bank-account d-flex">
                                    <p>【お振込先】</p>
                                    <p class="w-60">{{ $company->bank_account }}</p>
                                </div>
                                <img src="{{ asset('img/icon/condau.png') }}" class="position-image" alt="">
                            </div>
                        </div>
                        <div class="sub-info ">
                            <div>毎度ありがとうございます。</div>
                            <div>下記の通り御請求申し上げます。</div>
                        </div>
                        <div class="row table-container">
                            <div class="col-9">
                                <table class="table align-middle table-bordered table-deposit">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('billing.last_billed_amount') }}</th>
                                            <th>{{ trans('billing.deposit_amount') }}</th>
                                            <th>{{ trans('billing.carried_forward_amount') }}</th>
                                            <th>{{ trans('billing.receipt_amount') }}</th>
                                            <th>{{ trans('billing.tax_amount') }}</th>
                                            <th>{{ trans('billing.billing_amount') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td class="text-right">{{ number_format($lastBilledAmount) }}</td>
                                            <td class="text-right">{{ number_format($billing?->deposit_amount) }}</td>
                                            <td class="text-right">{{ number_format($lastBilledAmount - $billing?->deposit_amount) }}
                                            </td>
                                            <td class="text-right">{{ number_format($billing?->final_receipt_amount) }}
                                            </td>
                                            <td class="text-right">{{ number_format($billing?->tax_amount) }}</td>
                                            <td class="text-right">{{ number_format($lastBilledAmount - $billing?->deposit_amount + ($billing?->final_receipt_amount + $billing?->tax_amount)) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-3 row stamp">
                                <div class="col-6 first">
                                </div>
                                <div class="col-6 second">
                                </div>
                            </div>
                        </div>


                        <table class="data-table table align-middle  ">
                            <thead>
                                <tr>
                                    <th class="w-15">{{ trans('billing.transaction_date') }}</th>
                                    <th class="w-15">{{ trans('billing.receipt_id') }}</th>
                                    <th class="w-15">{{ trans('billing.deposit_amount') }}</th>
                                    <th class="w-15">{{ trans('billing.receipt_total_amount') }}</th>
                                    <th>{{ trans('billing.receipt_memo') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($billingReceipts as $billingReceipt)
                                    <tr>
                                        <td class="text-center">{{ $billingReceipt['transaction_date'] }}</td>
                                        <td class="text-center">{{ $billingReceipt['code'] }}</td>
                                        <td class="text-right">
                                            {{ $billingReceipt['is_deposit'] || $billingReceipt['is_total']
                                                ? number_format($billingReceipt['deposit_amount'])
                                                : '' }}
                                        </td>
                                        <td class="text-right">
                                            {{ $billingReceipt['is_receipt'] || $billingReceipt['is_total'] || $billingReceipt['is_sub_total']
                                                ? number_format($billingReceipt['total_amount'])
                                                : '' }}
                                        </td>
                                        <td>{{ $billingReceipt['memo'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

<script>
    const Page = {!! json_encode([
        'trans' => trans('billing'),
        'company' => $company,
        'billingAgent' => $billingAgent,
        'billing' => $billing,
        'billingReceipts' => $billingReceipts,
    ]) !!};
</script>
