@extends('layout')
@section('title') {{ trans('nav.measurements') }} @stop
@section('content')
    <x-top-page :name="trans('nav.measurements')"/>
    <div class="row">
        <div class="col-12 col-md-7">
            <div class="card mb-5">
                <div class="card-body p-3">
                    <x-table.table class="align-middle table-bordered mb-0">
                        <x-slot:thead>
                            <x-table.head :name="trans('measurement.code')" class="w-10 text-center"/>
                            <x-table.head :name="trans('measurement.label')" class="text-center"/>
                        </x-slot:thead>
                        <tbody>
                        @foreach($units as $unit)
                            <tr>
                                <td class="text-center">
                                    {{ $unit->id }}
                                </td>
                                <td class="text-center">
                                    {{ $unit->name }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </x-table.table>
                </div>
            </div>
        </div>
    </div>
@endsection
