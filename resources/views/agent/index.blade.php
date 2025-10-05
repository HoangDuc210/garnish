@extends('layout')
@section('title') {{ trans('nav.agent_management') }} @stop
@section('content')
    <x-top-page :name="trans('nav.agent_management')">
    </x-top-page>
    <x-alert />
    @include('agent.filter')
    <div class="card mb-3">
        <div class="card-body py-3">
            {{ $agents->onEachSide(ADD_PAGE_SIZE)->links() }}
            <x-table.table class="align-middle table-bordered mt-3" data-table-checkbox>
                <x-slot:thead>
                    <x-table.head :name="trans('agent.code')" class="w-10 text-center" />
                    <x-table.head :name="trans('agent.name')" class="text-center w-10" />
                    <x-table.head :name="trans('agent.zip_code')" class="w-10 text-center" />
                    <x-table.head :name="trans('agent.address')" class="w-10" />
                    <x-table.head :name="trans('agent.address_more')" class="text-center" />
                    <x-table.head :name="trans('agent.tel')" class="w-10 text-center" />
                    <x-table.head :name="trans('agent.fax')" class="w-10 text-center" />
                    <x-table.head :name="trans('app.action')" class="w-10 text-center" />
                </x-slot:thead>
                <tbody>
                    @foreach ($agents as $agent)
                        <tr data-id="{{ $agent->id }}">
                            <td class="text-center">
                                {{ $agent->code }}
                            </td>
                            <td>
                                <a href="{{ route(AGENT_EDIT_ROUTE, $agent->id) }}">
                                    {{ $agent->name }}
                                </a>
                            </td>
                            <td>
                                {{ $agent->post_code }}
                            </td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($agent->address, 50) }}
                            </td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($agent->address_more, 50) }}
                            </td>
                            <td>
                                {{ $agent->tel }}
                            </td>
                            <td>
                                {{ $agent->fax }}
                            </td>
                            <td>
                                <div class="status d-flex justify-content-center">
                                    <a href="{{ route(AGENT_EDIT_ROUTE, $agent->id) }}"
                                        class="btn btn-primary btn-circle edit px-3 me-1" data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <button data-href="{{ route(AGENT_DELETE_ROUTE, $agent->id) }}"
                                        data-content="「{{ $agent->name }}」{{ trans('agent.mess_delete') }}"
                                        class="btn btn-danger btn-circle edit px-3" toggle-confirm-delete data-toggle="tooltip" data-placement="top" title="{{ trans('app.delete') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @if (count($agents) > 0 && !empty($dataOptions))
                @include('_partials.option_export', $dataOptions)
            @endif
            {{ $agents->onEachSide(ADD_PAGE_SIZE)->links() }}
        </div>
    </div>
@endsection
