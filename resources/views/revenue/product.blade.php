@extends('layout')
@section('title') {{ trans('nav.revenue.product') }} @stop
@section('content')
    <x-top-page :name="trans('nav.revenue.product')" />

    <x-form::open :action="route(REVENUE_PRODUCT_ROUTE)" method="GET" class="enter-index-form">
        <div class="card mb-4 ">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <x-form::group class="row align-items-center">
                            <div class="col-2">
                                <label for="">{{ trans('revenue.aggregation_period') }}</label>
                            </div>
                            <div class="col-4">
                                <x-form::input name="month_start" :value="request('month_start', $firstMonth)" data-toggle="date"  required/>
                            </div>
                            <div class="col-auto p-0">
                                ～
                            </div>
                            <div class="col-4">
                                <x-form::input name="month_end" :value="request('month_end', $lastMonth)" data-toggle="date" required/>
                            </div>
                        </x-form::group>

                        <div class="row align-items-center">
                            <div class="col-2">
                                <label for="">{{ trans('agent.name_and_code_agent') }}</label>
                            </div>
                            <div class="col-4 ">
                                <x-form::input name="agent[code]" :value="request('agent.code')" class="agent-code" required/>
                            </div>
                            <div class="col-4">
                                <select name="agent[id]" class="form-select agent-select" required
                                    @if(request('agent.id')) data-agent-selected="{{ request('agent.id') }}" @endif
                                >
                                </select>
                            </div>
                            <div class="col-2">
                                <span  class="btn btn-primary btn-circle" data-url-search="{{ route(AGENT_MODAL_SEARCH_AJAX_ROUTE) }}" data-bs-toggle="modal" data-bs-target="#modalSearchAgent">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-end">
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
    <div class="card mb-4">
        <div class="card-body">
            <x-table.table class="align-middle table-bordered">
                <x-slot:thead>
                    <x-table.head :name="trans('app.stt')" class="text-center"/>
                    <x-table.head :name="trans('product.name')" class="text-center"/>
                    <x-table.head :name="trans('revenue.quantity')" class="text-center"/>
                    <x-table.head :name="trans('revenue.unit')" class="text-center"/>
                    <x-table.head :name="trans('revenue.sales_amount')" class="text-center"/>
                    <x-table.head :name="trans('revenue.average_price')"  class="text-center" />
                </x-slot:thead>
                <tbody>
                    @foreach($productRevenue as $key => $revenue)
                    <tr>
                        <td class="text-center">{{ sprintf('%03d',  ++$key); }}</td>
                        <td >{{ $revenue['name'] }}</td>
                        <td class="text-center">{{ $revenue['quantity'] }}</td>
                        <td class="text-center">{{ $revenue['unit'] }}</td>
                        <td class="text-end">{{ $revenue['amount'] }}</td>
                        <td class="text-end">{{ $revenue['price'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @if(count($productRevenue) === 0)
            <p class="text-center">データーがない。</p>
            @endif
            @if(count($productRevenue) > 0 && !empty($dataOptions))
                @include('_partials.option_export', $dataOptions)
            @endif
        </div>
    </div>

    @include('modal_search.modal_search_agent')
@endsection
