<x-form::open :action="route(ORDER_ROUTE)" method="GET" class="enter-index-form">
    <div class="card mb-3 mb-md-5">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('deposit.payment_date') }}</label>
                        </div>
                        <div class="col-4">
                            <x-form::input name="trading_start_date" :value="request('trading_start_date')" data-toggle="date" />
                        </div>
                        <div class="col-4">
                            <x-form::input name="trading_end_date" :value="request('trading_end_date')" data-toggle="date" />
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('agent.name_and_code_agent') }}</label>
                        </div>
                        <div class="col-4 readonly-custom">
                            <x-form::input name="agent[code]" :value="old('agent.code')" class="agent-code" required/>
                        </div>
                        <div class="col-4">
                            <select name="agent[id]" class="form-select agent-select">

                            </select>
                        </div>
                        <div class="col-2">
                            <span  class="btn btn-primary btn-circle" data-url-search="{{ route(AGENT_MODAL_SEARCH_AJAX_ROUTE) }}" 
                            data-bs-toggle="modal" data-bs-target="#modalSearchAgent">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </x-form::group>
                    <x-form::group class="row align-items-center">
                        <div class="col-2">
                            <label for="">{{ trans('app.address') }}</label>
                        </div>
                        <div class="col-10">
                            <x-form::input name="product_name" :value="request('product_name')" />
                        </div>
                    </x-form::group>
                </div>
                <div class="col-4">
                    <div class="d-grid gap-2 d-md-block">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="search"></i>
                            <span>{{ trans('app.search') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-form::open>
@include('modal_search.modal_search_agent')
