@extends('layout')
@section('title') {{ trans('accessHistory.title') }} @stop
@section('content')
    <x-alert />
    <x-top-page :name="trans('accessHistory.title')">
    </x-top-page>
    @include('access-history.filter')
    <div class="card mb-5">
        <div class="card-body py-3">
            {{ $accessHistories->onEachSide(ADD_PAGE_SIZE)->links() }}
            <x-table.table class="align-middle table-bordered mt-3">
                <x-slot:thead>
                    <x-table.head :name="trans('accessHistory.id')" class="w-10" />
                    <x-table.head :name="trans('accessHistory.name')" class="w-10" />
                    <x-table.head :name="trans('accessHistory.login_at')" class="w-10" />
                    <x-table.head :name="trans('accessHistory.logout_at')" class="w-10" />
                </x-slot:thead>
                <tbody>
                    @foreach ($accessHistories as $accessHistory)
                        <tr>
                            <td class="text-uppercase">{{ $accessHistory->id }}</td>
                            <td>{{ $accessHistory->user->name }}</td>
                            <td>{{ $accessHistory->formatted_login_at }}</td>
                            <td>{{ $accessHistory->formatted_logout_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            {{ $accessHistories->onEachSide(ADD_PAGE_SIZE)->links() }}
        </div>
    </div>
@endsection
