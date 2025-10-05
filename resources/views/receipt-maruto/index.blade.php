@extends('layout')
@section('title') {{ trans('nav.receipt_maruto.index') }} @stop
@section('content')
    <x-top-page :name="trans('nav.receipt_maruto.index')">
    </x-top-page>
    <x-alert />
    @include('receipt-maruto.filter')
    <div class="card">
        <div class="card-body py-3">
            {{ $receipts->onEachSide(ADD_PAGE_SIZE)->links() }}
            <x-table.table class="align-middle table-bordered mt-4" data-table-checkbox>
                <x-slot:thead>
                    <x-table.head :name="trans('receipt.transaction_date')" class="text-center" />
                    <x-table.head :name="trans('app.code')" class="w-10 text-center" />
                    <x-table.head :name="trans('receipt.agent.name')" class="text-center" />
                    <x-table.head :name="trans('receipt.total_amount')" class="text-center" />
                    <x-table.head :name="trans('receipt.print_status')" class="text-center" />
                    <x-table.head :name="trans('app.action')" class="w-10 text-center" />
                </x-slot:thead>
                <tbody>
                    @foreach ($receipts as $receipt)
                        <tr data-id="{{ $receipt->id }}">
                            <td class="text-center">
                                {{ $receipt->transaction_date_fm }}
                            </td>
                            <td class="text-center">{{ $receipt->code }}</td>
                            <td>
                                {{ $receipt->agent->name }}
                            </td>
                            <td class="text-end">
                                {{ number_format($receipt->total_receipt_amount) }}
                            </td>
                            <td class="text-center">
                                {{ $receipt->print }}
                            </td>
                            <td class="text-center">
                                <div class="status d-flex">
                                    <a href="{{ route(RECEIPT_MARUTO_DETAIL_ROUTE, $receipt->id) }}"
                                        class="btn btn-info btn-circle me-1 " data-toggle="tooltip" data-placement="top" title="{{ trans('app.detail') }}">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route(RECEIPT_MARUTO_EDIT_ROUTE, $receipt->id) }}"
                                        class="btn btn-primary btn-circle me-1" data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <button data-href="{{ route(RECEIPT_MARUTO_DELETE_ROUTE, $receipt->id) }}"
                                        data-content="「{{ $receipt->code }}」{{ trans('receipt.alert_delete') }}"
                                        class="btn btn-danger btn-circle" toggle-confirm-delete="confirm" data-toggle="tooltip" data-placement="top" title="{{ trans('app.delete') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @if (count($receipts) === 0)
                        <tr>
                            <td class="text-center" colspan="7">
                                {{ trans('receipt.list_empty_maruto') }}
                            </td>
                        </tr>
                    @endif
                </tfoot>
            </x-table.table>
            @if (count($receipts) > 0 && !empty($dataOptions))
                @include('_partials.option_export', $dataOptions)
            @endif
            {{ $receipts->onEachSide(ADD_PAGE_SIZE)->links() }}
        </div>
    </div>
@endsection
