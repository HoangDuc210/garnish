<div class="card mt-4">
    <div class="card-body">
        <table class="table table-bordered table-item-receipt" data-product="{{ count($receiptDetails) }}">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No.</th>
                    <th style="width: 15%;" class="text-center">{{ trans('product.code') }}</th>
                    <th class="text-center">{{ trans('product.name') }}</th>
                    <th class="text-center" style="width: 10%;">{{ trans('product.quantity') }}</th>
                    <th class="text-center" style="width: 15%;">{{ trans('product.unit') }}</th>
                    <th class="text-center" style="width: 15%;">{{ trans('product.price') }}</th>
                    <th class="text-center" style="width: 15%;">{{ trans('product.total_price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receiptDetails as $detail)
                    <tr>
                        <td class="text-center align-middle"><span class="stt">1</span></td>
                        <td class="text-center">
                            {{ $detail->code }}
                        </td>
                        <td>
                            {{ $detail->name }}
                        </td>
                        <td class="text-center">
                            {{ $detail->quantity }}
                        </td>
                        <td class="text-center">
                            {{ $detail->unit->name }}
                        </td>
                        <td class="text-end">
                            {{ $detail->price_fm }}
                        </td>
                        <td class="text-end">
                            {{ number_format($detail->amount) }}
                        </td>
                    </tr>
                @endforeach
                @for ($i = 0; $i < 12 - count($receiptDetails); $i++)
                    <tr >
                        <td class="text-center align-middle"><span class="stt">1</span></td>
                        <td class="text-center"></td>
                        <td></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-end"></td>
                        <td class="text-end"></td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <div class="row align-items-center justify-content-end mb-3">
            <div class="col-auto text-end">
                【{{ trans('receipt.maruto.total') }}】
            </div>
            <div class="col-2">
                <span  class="text-end form-control"> {{ number_format($receipt->price_total_product) }}</span>
            </div>
        </div>
        <div class="row align-items-center justify-content-end mb-3">
            <div class="col-auto text-end">
                【{{ trans('receipt.maruto.tax') }}】
            </div>
            <div class="col-2 d-flex align-items-center">
                <span name="tax" class="text-end form-control" > {{ number_format($receipt->tax) }} </span>
                <label style="padding-left: 5px;" for="">%</label>
            </div>
            <div class="col-auto text-end">
                【{{ trans('receipt.maruto.consumption_tax') }}】
            </div>
            <div class="col-2">
                <span name="tax" class="text-end form-control" > {{ number_format($receipt->consumption_tax) }} </span>
            </div>
        </div>
        <div class="row align-items-center justify-content-end mb-3">
            <div class="col-auto text-end">
                【{{ trans('receipt.maruto.total_amount') }}】
            </div>
            <div class="col-2">
                <span name="tax" class="text-end form-control" > {{ number_format($receipt->total_receipt_amount) }} </span>
            </div>
        </div>
        @include('_partials.option_export', $dataOptions)
    </div>
</div>
