<x-form::open :action="route(DEPOSIT_STORE_ROUTE)" class="enter-index-form form-order">
    <div class="card mb-3">
        <div class="card-body">
            <input type="hidden" name="id" value="{{ old('id', 0) }}">
            <div class="row">
                <div class="col-8">
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.payment_month') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::input name="payment_date" :value="old('payment_date', $paymentFirst)" class="payment-month" required data-toggle="month" />
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.name_and_code_agent') }}</label>
                        </div>
                        <div class="col-4 ">
                            <x-form::input name="agent[code]" :value="old('agent.code')" class="agent-code" required/>
                        </div>
                        <div class="col-4">
                            <select name="agent[id]" class="form-select agent-select" data-parent="parent" required>
                            </select>
                        </div>
                        <div class="col-2">
                            <span  class="btn btn-primary btn-circle" data-url-search="{{ route(AGENT_MODAL_SEARCH_AJAX_ROUTE) }}" data-bs-toggle="modal" data-bs-target="#modalSearchAgent">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </x-form::group>
                    <div class="row form-group align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.payment_first') }}</label>
                        </div>
                        <div class="col-10 readonly-custom">
                            <x-form::input name="payment_first" :value="old('payment_first', ZERO_MONEY)" readonly disabled/>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-end">
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route(DEPOSIT_ROUTE) }}" title=""
                            class="btn btn-outline-info btn-icon btn-icon-start">
                            <span>{{ trans('app.cancel') }}</span>
                        </a>
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="save"></i>
                            <span>{{ trans('app.save_changes') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('deposits.add_items')
</x-form::open>
@include('modal_search.modal_search_agent')
