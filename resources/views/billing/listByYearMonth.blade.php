@extends('layout')
@section('title') {{ trans('billing.listByYearMonth') }} @stop
@section('content')
    <x-alert />
    <x-top-page :name="trans('billing.listByYearMonth')">
    </x-top-page>
    <div class="billing">
        <div class="card mb-5 no-print">
            <div class="card-body">
                <form action="{{ route(BILLING_LIST_BY_YEAR_MONTH_ROUTE) }}" method="GET" class="enter-index-form mb-0">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label for="">{{ trans('billing.calculate_date') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::select name="calculate_date" select2 :options="$yearMonthOptions" :selected="request('calculate_date', $defaultYearMonth)" />
                        </div>
                        <div class="col-6">
                            <button type="submit" class="submit-btn btn btn-primary btn-icon">
                                <i data-acorn-icon="save"></i>
                                <span>{{ trans('billing.create') }}</span>
                            </button>
                            @if (count($billings) > 0)
                                <button type="button"
                                    class="btn btn-primary btn-icon " id="list-by-year-month-print">
                                    <i data-acorn-icon="print"></i>
                                    <span>{{ trans('billing.print') }}</span>
                                </button>
                                <button type="button" class="btn btn-primary btn-icon  export-csv-trigger"
                                    data-url="{{ route(BILLING_EXPORT_CSV_BY_YEAR_MONTH_ROUTE) }}">
                                    <i class="fa-solid fa-file-csv"></i>
                                    <span>{{ trans('billing.export') }}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if (count($billings) > 0)
            <div class="card mb-5">
                <div class="card-body">
                    <div class="preview-container">
                        <div class="header right-header">
                            <div class="title">請 求 一 覧 表</div>
                            <div class="time">作成日: {{ $createdDate }}</div>
                        </div>
                        <div class="info">
                            <div class="billing-date">請求年月:
                                {{ $defaultYearMonth }}
                            </div>
                        </div>
                        <table class="data-table table align-middle table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('billing.id') }}</th>
                                    <th>{{ trans('billing.billing_agent_name') }}</th>
                                    <th>{{ trans('billing.last_billed_amount') }}</th>
                                    <th>{{ trans('billing.deposit_amount') }}</th>
                                    <th>{{ trans('billing.carried_forward_amount') }}</th>
                                    <th>{{ trans('billing.receipt_amount') }}</th>
                                    <th>{{ trans('billing.tax_amount') }}</th>
                                    <th>{{ trans('billing.billing_amount') }}</th>
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
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

<script>
    const Page = {!! json_encode([
        'trans' => trans('billing'),
        'billings' => $billings,
        'totalBillings' => $totalBillings,
    ]) !!};
</script>
