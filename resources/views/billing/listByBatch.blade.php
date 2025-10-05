@extends('layout')
@section('title') {{ trans('billing.listByBatch') }} @stop
@section('content')
    <x-alert />
    <x-top-page :name="trans('billing.listByBatch')">
    </x-top-page>
    <div class="billing">
        <div class="card mb-5 no-print">
            <div class="card-body">
                <x-form::open :action="route(BILLING_STORE_BY_BATCH_ROUTE)" class="enter-index-form mb-0">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('billing.calculate_date') }}</label>
                        </div>
                        <div class="col-4">
                            <select class="fetch-trigger form-select" name="calculate_date">
                                @foreach ($yearMonthOptions as $key => $yearMonthOption)
                                    <option value={{ $key }} @if (request('calculate_date', $defaultYearMonth) === $key) selected @endif>
                                        {{ $yearMonthOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 text-end">
                            <div class="d-grid gap-2 d-md-block  text-end">
                                <button type="submit" class="submit-btn btn btn-primary btn-icon btn-icon-start">
                                    <i data-acorn-icon="save"></i>
                                    <span>{{ trans('billing.create') }}</span>
                                </button>
                                @if ($billingAgents)
                                    <button type="button"
                                        class="btn btn-primary btn-icon btn-icon-start table-check-display" id="preview-list-by-batch">
                                        <img src="{{ asset('img/icon/preview.png') }}" alt="" width="16"
                                            class="filter-invert-1" />
                                        <span>{{ trans('billing.preview') }}</span>
                                    </button>
                                    <button type="button"
                                        class="btn btn-primary btn-icon btn-icon-start table-check-display" id="print-list-by-batch">
                                        <i data-acorn-icon="print"></i>
                                        <span>{{ trans('billing.print') }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-form::open>
            </div>
        </div>
        @if ($billingAgents)
            <div class="card">
                <div class="card-body">
                    <div class="no-print mb-3 text-right">
                        <button type="button" class="btn btn-outline-primary table-check-all-trigger">
                            <span>{{ trans('billing.check_all') }}</span>
                        </button>
                        <button type="button" class="btn btn-outline-primary table-uncheck-all-trigger">
                            <span>{{ trans('billing.uncheck_all') }}</span>
                        </button>
                    </div>
                    <div class="preview-container">
                        <table class="data-table table align-middle table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ trans('billing.billing_agent_id') }}</th>
                                    <th class="text-center">{{ trans('billing.agent_name') }}</th>
                                    <th class="text-center">{{ trans('billing.print') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($billingAgents as $billingAgent)
                                    <tr>
                                        <td class="text-center">{{ $billingAgent->code }}</td>
                                        <td>{{ $billingAgent->name }}</td>
                                        <td class="text-center">
                                            <input class="form-check-input table-check input-item" type="checkbox"
                                                name="billing_agent_ids[]" value="{{ $billingAgent->id }}">
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
    ]) !!};
</script>
