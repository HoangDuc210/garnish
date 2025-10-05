@extends('layout')
@section('title') {{ trans('nav.user_management') }} @stop
@section('content')
    <x-top-page :name="trans('nav.user_management')">
    </x-top-page>
    <x-alert />
    @include('user.filter')
    <div class="card mb-5">
        <div class="card-body py-3">
            {{ $users->onEachSide(ADD_PAGE_SIZE)->links() }}
            <x-table.table class="align-middle table-bordered mt-3">
                <x-slot:thead>
                    <x-table.head :name="trans('user.no')" class="w-10 text-center" />
                    <x-table.head :name="trans('user.username')" class="text-center" />
                    <x-table.head :name="trans('user.name')" class="text-center" />
                    <x-table.head :name="trans('user.role')" class="text-center" />
                    <x-table.head :name="trans('app.action')" class="text-center" />
                </x-slot:thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-center">
                                {{ $users->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <a href="{{ route(USER_EDIT_ROUTE, $user->id) }}">
                                    {{ $user->username }}
                                </a>
                            </td>
                            <td>
                                {{ $user->name }}
                            </td>
                            <td class="text-center">
                                {{ $user->role_name }}
                            </td>
                            <td class="text-center">
                                <div class="status d-flex justify-content-center">
                                    <a href="{{ route(USER_EDIT_ROUTE, $user->id) }}"
                                        class="btn btn-primary btn-circle me-1" data-toggle="tooltip" data-placement="top" title="{{ trans('app.edit') }}">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <button data-href="{{ route(USER_DELETE_ROUTE, $user->id) }}"
                                        data-content="「{{ $user->name }}」{{ trans('user.deleted') }}"
                                        class="btn btn-danger btn-circle" toggle-confirm-delete="confirm" data-toggle="tooltip" data-placement="top" title="{{ trans('app.delete') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            {{ $users->onEachSide(ADD_PAGE_SIZE)->links() }}
        </div>
    </div>
@endsection
