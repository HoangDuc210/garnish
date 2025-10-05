@extends('layout')
@section('title') {{ trans('deposit.index') }} @stop
@section('content')
    <x-top-page :name="trans('deposit.index')">
        <a href="{{ route(DEPOSIT_CREATE_ROUTE) }}" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto">
            <i data-acorn-icon="plus"></i>
            <span>{{ trans('app.create_btn') }}</span>
        </a>
    </x-top-page>
    <x-alert />
    <div class="deposit">
        <div class="card mb-5 no-print">
            <div class="card-body">
                <form action="{{ route(DEPOSIT_ROUTE) }}" method="GET" class="enter-index-form mb-0">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.billing_agent') }}</label>
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
                    <br>
                    <div class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.payment_date') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::select name="payment_year_month" select2 :options="$yearMonthOptions" :selected="request('payment_year_month', $defaultYearMonth)" />
                        </div>
                        <div class="col-6">
                            <div class="date-info payment-year-month-info"></div>
                        </div>
                    </div>

                    <br>
                    <div class="actions text-center">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start submit-btn">
                            <i data-acorn-icon="search"></i>
                            <span>{{ trans('deposit.search') }}</span>
                        </button>
                        <a href="{{ route(DEPOSIT_ROUTE) }}" class="btn btn-outline-info btn-icon btn-icon-start">
                            <span>{{ trans('app.cancel') }}</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        @if ($deposits)
            <div class="card mb-5">
                <div class="card-body">
                    {{ $deposits->onEachSide(ADD_PAGE_SIZE)->links() }}
                    <br>
                    <div class="preview-container">
                        <table class="data-table table align-middle table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('deposit.id') }}</th>
                                    <th>{{ trans('deposit.billing_agent') }}</th>
                                    <th>{{ trans('deposit.payment_date') }}</th>
                                    <th>{{ trans('deposit.type_code') }}</th>
                                    <th>{{ trans('deposit.amount') }}</th>
                                    <th class="w-30">{{ trans('deposit.memo') }}</th>
                                    <th>{{ trans('deposit.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $deposit)
                                    <tr>
                                        <td class="text-center">{{ $deposit->id }}</td>
                                        <td class="text-center">{{ $deposit->billingAgent?->name }}</td>
                                        <td class="text-right">{{ $deposit->formatted_payment_date }}</td>
                                        <td class="text-center">
                                            {{ App\Enums\Deposit\Type::fromValue($deposit->type_code)->description }}</td>
                                        <td>{{ number_format($deposit->amount) }}</td>
                                        <td>{{ $deposit->memo }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route(DEPOSIT_EDIT_ROUTE, [
                                                    'id' => $deposit->id,
                                                    'billing_agent_id' => $deposit->billing_agent_id,
                                                    'payment_year_month' => $deposit->payment_date,
                                                ]) }}"
                                                    class="btn btn-primary btn-circle edit" data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <button
                                                    data-href="{{ route(DEPOSIT_DELETE_ROUTE, [
                                                        'id' => $deposit->id,
                                                        'billing_agent_id' => $deposit->billing_agent_id,
                                                        'payment_year_month' => $deposit->payment_date,
                                                    ]) }}"
                                                    data-content="{{ trans('app.alert_delete') }}"
                                                    class="btn btn-danger btn-circle" toggle-confirm-delete="confirm" data-toggle="tooltip" data-placement="top" title="{{ trans('app.delete') }}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @if (count($deposits) === 0)
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            {{ trans('deposit.no_data') }}
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                    {{ $deposits->onEachSide(ADD_PAGE_SIZE)->links() }}
                </div>
            </div>
        @endif
    </div>

    <script>
        const Page = {!! json_encode([
            'trans' => trans('deposit'),
        ]) !!};
    </script>
    
@endsection