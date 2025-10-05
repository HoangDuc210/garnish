@extends('layout')
@section('title') {{ trans('nav.revenue.agent') }} @stop
@section('content')
    <x-top-page :name="trans('nav.revenue.agent')" />

    <x-form::open :action="route(REVENUE_AGENT_ROUTE)" method="GET" class="enter-index-form">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <x-form::group class="row align-items-center">
                            <div class="col-2">
                                <label for="">{{ trans('app.select_month') }}</label>
                            </div>
                            <div class="col-4">
                                <x-form::input name="month" :value="request('month', $currentMonth)" data-toggle="month" />
                            </div>
                        </x-form::group>

                        <div class="row align-items-center">
                            <div class="col-2">
                                <label for="">{{ trans('agent.name_and_code_agent') }}</label>
                            </div>
                            <div class="col-4 ">
                                <x-form::input name="agent[code]" :value="request('agent.code')" class="agent-code" />
                            </div>
                            <div class="col-4">
                                <select name="agent[id]" class="form-select agent-select" required
                                    @if (request('agent.id')) data-agent-selected="{{ request('agent.id') }}" @endif>
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
                    <x-table.head :name="trans('app.day')" class="col-4 col-sm-2 text-center" />
                    <x-table.head :name="trans('revenue.sales_amount')" class="col-2  text-center" />
                    <x-table.head :name="trans('revenue.agent.quantity')" class="col-2 text-center" />
                    <x-table.head :name="trans('revenue.remarks')" class="text-center" />
                </x-slot:thead>
                <tbody>
                    @foreach ($revenueAgent as $key => $revenue)
                        <tr>
                            <td class="text-center">{{ sprintf('%02d', ++$key) }}</td>
                            <td class="text-end">
                                {{ count($revenue['receipt']) ? number_format(array_sum($revenue['receipt'])) : null }}
                            </td>
                            <td class="text-center">{{ count($revenue['receipt']) ? count($revenue['receipt']) : null }}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center">{{ trans('app.total_amount') }}</td>
                        <td class="text-end">{{ number_format($total_amount) }}</td>
                        <td class="text-center">{{ $total_receipt }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </x-table.table>
            @if(count($revenueAgent) === 0)
            <p class="text-center">データーがない。</p>
            @endif
            @if(count($revenueAgent) > 0 && !empty($dataOptions))
                @include('_partials.option_export', $dataOptions)
            @endif
        </div>
    </div>
    @include('modal_search.modal_search_agent')
@endsection
