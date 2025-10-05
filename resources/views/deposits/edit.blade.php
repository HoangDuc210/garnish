@extends('layout')
@section('title') {{ trans('deposit.edit') }} @stop
@section('content')
    <x-top-page :name="trans('deposit.edit')" />
    <x-alert />
    <div class="deposit card mb-5">
        <div class="card-body">
            <div class="no-print">
                <x-form::open :action="route(DEPOSIT_UPDATE_ROUTE, $deposit->id)" class="enter-index-form mb-0">
                    <input type="hidden" name="id" value="{{ $deposit->id }}" />
                    <input type="hidden" name="billing_agent_id" value="{{ request('billing_agent_id') }}" />
                    <input type="hidden" name="payment_year_month" value="{{ request('payment_year_month') }}" />
                    <div class="row align-items-center mb-3">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.billing_agent') }}</label>
                        </div>
                        <div class="col-4">
                            <label for="">{{ $deposit->billingAgent?->name }}</label>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.payment_date') }}</label>
                        </div>
                        <div class="col-4">
                            <div class="payment-date">
                                <x-form::input name="payment_date" :value="$deposit->payment_date" required data-toggle="date" />
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.type_code') }}</label>
                        </div>

                        <div class="col-4">
                            <select name="type_code" class="form-select">
                                @foreach (App\Enums\Deposit\Type::asSelectArray() as $code => $label)
                                    <option value="{{ $code }}" @if ($code == $deposit->type_code) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.amount') }}</label>
                        </div>

                        <div class="col-4">
                            <x-form::input name="amount" :value="$deposit->amount" required />
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.memo') }}</label>
                        </div>

                        <div class="col-4">
                            <x-form::input name="memo" :value="$deposit->memo" />
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start submit-btn">
                            <i data-acorn-icon="save"></i>
                            <span>{{ trans('deposit.update') }}</span>
                        </button>
                    </div>
                </x-form::open>
            </div>
        </div>
    </div>
@endsection
<script>
    const Page = {!! json_encode([
        'trans' => trans('deposit'),
    ]) !!};
</script>
