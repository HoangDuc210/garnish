@extends('layout')
@section('title') {{ trans('billing.listBillingAgentCollations') }} @stop
@section('content')
    <x-alert />
    <x-top-page :name="trans('billing.listBillingAgentCollations')">
    </x-top-page>
    <div class="billing">
        <div class="card mb-5 no-print">
            <div class="card-body">
                <x-form::open method="GET" class="enter-index-form mb-0">
                    <div class="row mb-3">
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
                    <div class="actions text-center">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="save"></i>
                            <span>{{ trans('billing.aggregate') }}</span>
                        </button>
                        @if ($billingAgent)
                            <button type="button"
                                class="btn btn-primary btn-icon btn-icon-start " id="preview-list-collations">
                                <img src="{{ asset('img/icon/preview.png') }}" alt="" width="16"
                                    class="filter-invert-1" />
                                <span>{{ trans('billing.preview') }}</span>
                            </button>
                            <button type="button"
                                class="btn btn-primary btn-icon btn-icon-start " id="list-collations">
                                <i data-acorn-icon="print"></i>
                                <span>{{ trans('billing.print') }}</span>
                            </button>
                            <button type="button" class="btn btn-primary btn-icon btn-icon-start export-csv-trigger"
                                data-url="{{ route(BILLING_EXPORT_CSV_BILLING_AGENT_COLLATIONS_ROUTE) }}">
                                <i class="fa-solid fa-file-csv"></i>
                                <span>{{ trans('billing.export') }}</span>
                            </button>
                        @endif
                    </div>
                </x-form::open>
            </div>
        </div>
        @if ($billingAgent)
            <div class="card mb-5">
                <div class="card-body">
                    <div class="preview-container">

                        <div class="page-index data-table-pagination-info">
                            Page: <span class="page-current"></span>/<span class="page-count"></span>
                        </div>

                        <div class="header text-center">
                            <div class="title">請 求 照 合 表</div>
                        </div>
                        <div class="info billing-agent">
                            {{ $billingAgent->name }} 御中
                        </div>
                        <div class="intro">
                            <span>いつも大変お世話になっております。</span>
                            <span class="calculate-date-month"></span>
                            <span>月の照合表を送りいたします。</span>
                            <br>
                            ご確認の程、宜しくお願い致します。
                        </div>
                        <div class="company company-title text-center">{{ $company->name }}</div>
                        <table class="data-table table align-middle table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('billing.transaction_date') }}</th>
                                    <th>{{ trans('billing.receipt_id_full') }}</th>
                                    <th>{{ trans('billing.receipt_total_amount_full') }}</th>
                                    <th>{{ trans('billing.receipt_memo') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collations as $key => $collation)
                                    <tr class="
                                        @if ($collation['is_total']) tr-bold @endif
                                        @if ($collation['is_agent_title']) agent-title @endif">
                                            <td class="text-center">
                                                {{ $collation['is_exception'] ? $collation['transaction_date'] : date_format($collation->transaction_date, 'Y/m/d') }}
                                            </td>
                                            <td class="text-center">
                                                {{ $collation['is_exception'] ? $collation['code'] : $collation->code }}
                                            </td>
                                            <td class="text-right">
                                                {{ $collation['is_total'] || !$collation['is_exception'] ? number_format($collation['total_amount']) : $collation['total_amount'] }}
                                            </td>
                                            <td>
                                                {{ $collation['is_exception'] ? $collation['memo'] : $collation->memo }}
                                            </td>
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
        'collations' => $collations,
    ]) !!};
</script>
