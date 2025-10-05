<div class="card mt-4 overflow-hidden">
    <div class="card-body">
        <table class="table table-bordered table-product" data-table="receipt-maruto">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;" >No.</th>
                    <th class="text-center" style="width: 15%;">{{ trans('product.code') }}</th>
                    <th class="text-center" style="width: 20%;">{{ trans('product.name') }}</th>
                    <th class="text-center" style="width: 10%;">{{ trans('receipt.quantity') }}</th>
                    <th class="text-center">{{ trans('product.unit') }}</th>
                    <th class="text-center" style="width: 13%;">{{ trans('product.price') }}</th>
                    <th class="text-center" style="width: 20%;">{{ trans('product.total_price') }}</th>
                    <th class="text-center" style="width: 8%;"></th>
                </tr>
            </thead>
            <tbody>
                @if (old('products'))
                    @foreach (old('products') as $key => $pro)
                        <tr data-receipt-detail-id="{{ old('products.' . $key . '.id') }}" data-sort={{ $key }}>
                            <td style="width: 5%;" class="align-middle text-center">
                                <span class="stt">1</span>
                            </td>
                            <td style="width: 15%;">
                                <x-form::input name="products[{{$key}}][code]" :value="old('products.' . $key . '.code')" class="product-code text-end" />
                            </td>
                            <td style="width: 20%;">
                                <x-form::input name="products[{{$key}}][name]" :value="old('products.' . $key . '.name')" data-product-id="{{ old('products.' . $key . '.product_id')}}" class="product-name text-end" />
                            </td>
                            <td style="width: 10%;">
                                <x-form::input name="products[{{$key}}][quantity]" :value="old('products.' . $key . '.quantity')"  class="product-quantity input-mark-decimal text-end" />
                            </td>
                            <td >
                                <select name="products[{{$key}}][unit_code]" id=""
                                    class="form-select unit-select" data-id="{{ old('products.' . $key . '.unit.id') }}"
                                    data-name="{{ old('products.' . $key . '.unit.name') }}">
                                    @foreach(Util::measurements() as $keyCode => $unitCode)
                                        <option value="{{ $keyCode }}">{{ $unitCode }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="width: 13%;">
                                <x-form::input name="products[{{$key}}][price]" :value="number_format(old('products.' . $key . '.price'))"
                                    class="product-price input-mark-currency text-end" />
                            </td>
                            <td style="width: 20%;" class="readonly-custom">
                                <x-form::input name="products[{{$key}}][price_total]" :value="old('products.' . $key . '.price_total')"
                                    class="product-price-total input-number-validate text-end" readonly />
                            </td>
                            <td style="width: 8%;" class="text-center vertical-middle p-0">
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary btn-add-item btn-circle me-1" data-toggle="tooltip" data-placement="top" title="追加クリア">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-remove btn-circle" data-toggle="tooltip" data-placement="top" title=" 行のクリア">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                @for ($i = count(old('products', [])); $i < 12; $i++)
                    <tr data-receipt-detail-id="" data-sort="{{$i}}">
                        <td style="width: 5%;" class="vertical-middle text-center"><span class="stt">1</span></td>
                        <td style="width: 15%;">
                            <x-form::input name="products[{{$i}}][code]" class="product-code text-end" />
                        </td>
                        <td style="width: 20%;">
                            <x-form::input name="products[{{$i}}][name]" class="product-name text-end" data-product-id="" />
                        </td>
                        <td style="width: 10%;">
                            <x-form::input name="products[{{$i}}][quantity]" class="product-quantity input-mark-decimal text-end" />
                        </td>
                        <td>
                            <select name="products[{{$i}}][unit_code]" id="" class="form-select unit-select w-100">
                                @foreach(Util::measurements() as $key => $unitCode)
                                <option value="{{ $key }}">{{ $unitCode }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="width: 13%;">
                            <x-form::input name="products[{{$i}}][price]" class="input-mark-currency product-price text-end" />
                        </td>
                        <td style="width: 20%;" class="readonly-custom">
                            <x-form::input name="products[{{$i}}][price_total]" class="product-price-total input-number-validate text-end" readonly />
                        </td>
                        <td style="width: 8%;" class="text-center vertical-middle p-0">
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-primary btn-add-item btn-circle me-1" data-toggle="tooltip" data-placement="top" title="追加クリア">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-remove btn-circle" data-toggle="tooltip" data-placement="top" title=" 行のクリア">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <div class="row align-items-center justify-content-end mb-3">
            <div class="col-auto text-end pe-0">
                【{{ trans('receipt.maruto.total') }}】
            </div>
            <div class="col-3 readonly-custom">
                <x-form::input name="total" :value="number_format(old('price_total_product', ZERO_MONEY))" class="text-end" readonly />
            </div>
        </div>
        <div class="row align-items-center justify-content-end mb-3">
            <div class="col-auto text-end pe-0">
                【{{ trans('receipt.maruto.tax') }}】
            </div>
            <div class="col-3">
                <x-form::input name="tax" :value="number_format(old('tax', CONSUMPTION_TAX))" class="text-end input-mark-decimal" />
            </div>
            <div class="col-auto ps-0">
                <label style="padding-left: 5px;" for="">%</label>
            </div>
            <div class="col-auto text-end pe-0">
                【{{ trans('receipt.maruto.consumption_tax') }}】
            </div>
            <div class="col-3 readonly-custom">
                <x-form::input name="consumption_tax" :value="number_format(old('consumption_tax', ZERO_MONEY))" class="text-end input-number-validate"
                    readonly />
            </div>
        </div>
        <div class="row align-items-center justify-content-end">
            <div class="col-auto text-end pe-0">
                【{{ trans('receipt.maruto.total_amount') }}】
            </div>
            <div class="col-3 readonly-custom">
                <x-form::input name="total_amount" :value="number_format(old('total_receipt_amount', ZERO_MONEY))" class="text-end" readonly />
            </div>
        </div>

        <div class="d-grid gap-2 d-md-block grid-submit w-100 text-center mt-4">
            <a href="{{ route(RECEIPT_ROUTE) }}" title=""
                class="btn btn-outline-info btn-icon btn-icon-start">
                <span>{{ trans('app.back_to_list') }}</span>
            </a>
            <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                <i data-acorn-icon="save"></i>
                <span>{{ trans('app.save_changes') }}</span>
            </button>
        </div>
    </div>
</div>
