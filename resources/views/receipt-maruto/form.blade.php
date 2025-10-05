<x-alert />
<x-form::open :action="route(RECEIPT_MARUTO_STORE_ROUTE)" class="enter-index-form form-order">
    <div class="card">
        <div class="card-body">
            <input type="hidden" name="id" value="{{ old('id', 0) }}">
            <div class="row">
                <div class="col-10">
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('receipt.code') }}</label>
                        </div>
                        <div class="col-4 readonly-custom">
                            <input type="text" name="code" class="form-control receipt-code" value="{{ old('code') }}" @if(old('id', 0)) required="required" @endif>
                        </div>
                        <div class="col-3">
                            <select name="" id="" class="form-select">
                                <option value="" selected>売上</option>
                            </select>
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('receipt.transaction_date') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::input name="transaction_date" :value="old('transaction_date', $transactionDate)" required data-toggle="date" />
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('agent.name_and_code_agent') }}</label>
                        </div>
                        <div class="col-4 ">
                            <x-form::input name="agent[code]" :value="old('agent.code')" class="agent-code" required/>
                        </div>
                        <div class="col-4">
                            <select name="agent[id]" class="form-select agent-select" required
                                @if(old('agent.id'))
                                    data-agent-name="{{ old('agent.name') }}"
                                    data-agent-id="{{ old('agent.id') }}"
                                    data-tax-fraction-rounding-code="{{ old('agent.tax_fraction_rounding_code') }}"
                                    data-fraction-rounding-code="{{ old('agent.fraction_rounding_code') }}"
                                @endif
                            >

                            </select>
                        </div>
                        <div class="col-2">
                            <span  class="btn btn-primary btn-circle" data-url-search="{{ route(AGENT_MODAL_SEARCH_AJAX_ROUTE) }}" data-bs-toggle="modal" data-bs-target="#modalSearchAgent">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('agent.zip_code') }}</label>
                        </div>
                        <div class="col-4 readonly-custom">
                            <x-form::input name="agent[post_code]" :value="old('agent.post_code')" class="agent-post_code"
                                readonly />
                        </div>
                        <div class="col-2 text-end">
                            <label for="">{{ trans('agent.tel') }}</label>
                        </div>
                        <div class="col-4 readonly-custom">
                            <x-form::input name="agent[tel]" :value="old('agent.tel')" class="agent-tel" readonly />
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('agent.fax') }}</label>
                        </div>
                        <div class="col-4 readonly-custom">
                            <x-form::input name="agent[fax]" :value="old('agent.fax')" class="agent-fax" readonly />
                        </div>
                        <div class="col-2 text-end">
                            <label for="">{{ trans('agent.billing_cycle') }}</label>
                        </div>
                        <div class="col-4 readonly-custom">
                            <x-form::input name="agent[closing_date]" :value="old('agent.closing_date')" class="agent-closing_date"
                             readonly />
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('app.address') }}</label>
                        </div>
                        <div class="col-10 readonly-custom">
                            <x-form::input name="agent[address]" :value="old('agent.address')" class="agent-address"
                                readonly />
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('receipt.agent_name') }}</label>
                        </div>
                        <div class="col-10 readonly-custom">
                            <x-form::input name="agent[name]" :value="old('agent.name')" class="agent-name"
                                readonly />
                        </div>
                    </x-form::group>
                </div>
            </div>
        </div>
    </div>
    @include('receipt-maruto.products')
</x-form::open>

@include('modal_search.modal_search_agent')
