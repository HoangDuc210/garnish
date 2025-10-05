<x-form::open :action="route(RECEIPT_MARUTO_ROUTE)" method="GET" class="enter-index-form">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('receipt.filter.code') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::input name="code" :value="request('code')" class="agent-code" />
                        </div>
                    </x-form::group>

                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('agent.name_and_code_agent') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::input name="agent_code" :value="request('agent_code')" class="agent-code" />
                        </div>
                        <div class="col-4">
                            <x-form::input name="agent_name" :value="request('agent_name')" class="agent-name" />
                        </div>
                        <div class="col-2">
                            <span  class="btn btn-primary btn-circle" data-url-search="{{ route(AGENT_MODAL_SEARCH_AJAX_ROUTE) }}" data-bs-toggle="modal" data-bs-target="#modalSearchAgent">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </x-form::group>

                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('receipt.transaction_date') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::input name="transaction_start_date" :value="request('transaction_start_date')" data-toggle="date" />
                        </div>
                        <div class="col-auto p-0">
                            ～
                        </div>
                        <div class="col-4">
                            <x-form::input name="transaction_end_date" :value="request('transaction_end_date')" data-toggle="date" />
                        </div>
                    </x-form::group>

                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('product.no_in_list') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::input name="product_code" class="product-code" :value="request('product_code')" />
                        </div>
                        <div class="col-4">
                            <x-form::input name="product_name" class="product-name" :value="request('product_name')" />
                        </div>
                        <div class="col-2">
                            <span  class="btn btn-primary btn-circle" data-url-search="{{ route(PRODUCT_MODAL_SEARCH_AJAX_ROUTE) }}" data-bs-toggle="modal" data-bs-target="#modalSearchProduct">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </x-form::group>

                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('product.name') }}</label>
                        </div>
                        <div class="col-10">
                            <x-form::input name="product_name" :value="request('product_name')" />
                        </div>
                    </x-form::group>

                    <div class="form-group row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('receipt.filter.sort') }}</label>
                        </div>
                        <div class="col-4">
                            <input class="form-check-input" type="radio" name="sort_transaction_date" value="" id="transaction_date_desc"
                                checked>
                            <label class="form-check-label" for="transaction_date_desc">
                                {{ trans('app.asc') }}
                            </label>
                        </div>
                        <div class="col-4">
                            <input class="form-check-input" type="radio" name="sort_transaction_date" value="ASC"
                                id="transaction_date_asc" @if (request('sort_transaction_date')) checked @endif>
                            <label class="form-check-label" for="transaction_date_asc">
                                {{ trans('app.desc') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4 align-self-end">
                    <div class="d-grid gap-2 d-md-block">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="search"></i>
                            <span>{{ trans('app.search') }}</span>
                        </button>
                        <a href="{{ route(RECEIPT_MARUTO_ROUTE) }}" title=""
                            class="btn btn-outline-info btn-icon btn-icon-start">
                            <span>{{ trans('app.cancel') }}</span>
                        </a>
                        <a href="{{ route(RECEIPT_MARUTO_CREATE_ROUTE) }}" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto">
                            <i data-acorn-icon="plus"></i>
                            <span>{{ trans('app.create_btn') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-form::open>

@include('modal_search.modal_search_agent')

@include('modal_search.modal_search_product')
