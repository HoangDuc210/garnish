@extends('layout')
@section('title') {{ trans('nav.product_management') }} @stop
@section('content')
    <x-top-page :name="trans('nav.product_management')">
    </x-top-page>
    <x-alert />
    @include('product.filter')
    <div class="card mb-3 mt-4">
        <div class="card-body py-3">
            {{ $products->onEachSide(ADD_PAGE_SIZE)->links() }}
            <x-table.table class="align-middle table-bordered mt-3" data-table-checkbox>
                <x-slot:thead>
                    <x-table.head :name="trans('product.code')" class="w-10" />
                    <x-table.head :name="trans('product.name')" />
                    <x-table.head :name="trans('product.unit')" />
                    <x-table.head :name="trans('product.quantity')" class="w-10 text-center" />
                    <x-table.head :name="trans('product.price')" class="text-center" />
                    <x-table.head :name="trans('app.action')" class="w-10" />
                </x-slot:thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr data-id={{ $product->id }}>
                            <td class="text-uppercase">{{ $product->code }}</td>
                            <td>
                                <a href="{{ route(PRODUCT_EDIT_ROUTE, $product->id) }}">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td>
                                {{ $product->unit ? $product->unit->name : '' }}
                            </td>
                            <td class="text-end">
                                {{ number_format($product->quantity) }}
                            </td>
                            <td class="text-end fw-bold">
                                {{ number_format($product->price) }}
                            </td>
                            <td>
                                <div class="status d-flex justify-content-center">
                                    <a href="{{ route(PRODUCT_EDIT_ROUTE, $product->id) }}"
                                        class="btn btn-primary edit px-3 btn-circle me-2" data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <button data-href="{{ route(PRODUCT_DELETE_ROUTE, $product->id) }}"
                                        data-content="{{ trans('app.alert_delete') }}"
                                        class="btn btn-danger edit px-3 btn-circle" toggle-confirm-delete data-toggle="tooltip" data-placement="top" title="{{ trans('app.delete') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @if (count($products) > 0 && !empty($dataOptions))
                @include('_partials.option_export', $dataOptions)
            @endif
            {{ $products->onEachSide(ADD_PAGE_SIZE)->links() }}
        </div>
    </div>
@endsection
