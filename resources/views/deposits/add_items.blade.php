<x-alert class="" />

<div class="card mt-3">
    <div class="card-body">
        <table class="table table-bordered  table-deposit repeater">
            <thead>
                <tr>
                    <th class="col-auto">{{ trans('app.stt') }}</th>
                    <th class="col-2">{{ trans('deposit.day_receive') }}</th>
                    <th class="col-2">{{ trans('deposit.type') }}</th>
                    <th class="col-2">{{ trans('deposit.input_money') }}</th>
                    <th class="col-5">{{ trans('deposit.note') }}</th>
                    <th class="col-auto"></th>
                </tr>
            </thead>
            <tbody data-repeater-list="deposits" @if (!empty($deposits)) data-deposit="{{ count($deposits) }} @endif">
                @if (old('deposits'))
                    @foreach (old('deposits') as $key => $deposit)
                    <tr data-repeater-item>
                        <td class="align-middle text-center"><span class="stt">1</span></td>
                        <td>
                            <select name="payment_date" class="select-day form-select" required>
                            </select>
                        </td>
                        <td>
                            <select name="type_code" class="form-select deposit-currency currency" required>
                                
                            </select>
                        </td>
                        <td>
                            <x-form::input name="amount" :value="$deposit->amount" class="amount-input input-number-validate input-make-money text-end" required/>
                        </td>
                        <td>
                            <x-form::input name="memo" :value="$deposit->memo" class="memo" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-remove btn-circle" data-repeater-delete
                            data-toggle="tooltip" data-placement="top" title="{{ trans('app.delete_line') }}">
                                <i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr data-repeater-item>
                        <td class="align-middle text-center"><span class="stt">1</span></td>
                        <td>
                            <select name="payment_date" class="select-day form-select" required>
                            </select>
                        </td>
                        <td>
                            <select name="type_code" class="form-select deposit-currency" required>
                            </select>
                        </td>
                        <td>
                            <x-form::input name="amount" :value="old('amount')" class="amount-input input-number-validate input-make-money text-end" required/>
                        </td>
                        <td>
                            <x-form::input name="memo" :value="old('memo')" class="memo" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-remove btn-circle" data-repeater-delete
                            data-toggle="tooltip" data-placement="top" title="{{ trans('app.delete_line') }}">
                                <i class="fa fa-minus"></i>
                            </button>
                        </td>
                    </tr>
                @endif

            </tbody>
            <tfoot>
                <td colspan="5" style="width: 95%;">
                </td>
                <td style="width: 5%;">
                    <button type="button" class="btn btn-primary btn-add btn-circle" data-repeater-create
                    data-toggle="tooltip" data-placement="top" title="{{ trans('app.plus_line') }}">
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </tfoot>
        </table>
        <div class="row align-items-center justify-content-end mb-2">
            <div class="col-2 text-end">
                {{ trans('deposit.total_deposit_amount') }}
            </div>
            <div class="col-3 readonly-custom">
                <x-form::input name="total_amount" :value="old('total_amount')" class="text-end" required readonly />
            </div>
        </div>
        <div class="row align-items-center justify-content-end">
            <div class="col-2 text-end">
                {{ trans('deposit.receipt_balance') }}
            </div>
            <div class="col-3 readonly-custom">
                <x-form::input name="receipt_balance" :value="old('receipt_balance')" class="text-end" required readonly />
            </div>
        </div>
    </div>
</div>
