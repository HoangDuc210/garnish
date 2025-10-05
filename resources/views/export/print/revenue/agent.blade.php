@extends('export.index')
@section('title')
    {{ trans('revenue.agent_export_title') }}
@endsection
@section('style')
    <link rel="stylesheet" media="print"  href="{{ asset('css/theme/export/revenue.css') }}">
@endsection
@section('content')
    <div class="revenue fs-14">
        <table class="table-head" style="margin-bottom: 10px;">
            <tr>
                <th class="text-start vertical-bottom" >
                    {{ trans('revenue.aggregation_date') }}
                    <span class="font-normal">{{ $data['aggregation_date'] }}</span>
                </th>
                <th class="text-center title w-45" style="font-weight: bold; font-size: 18px">{{ trans('revenue.agent_export_title') }}</th>
            </tr>
            <tr>
                <th class="text-start pt-10">
                    {{ trans('revenue.choose_agent') }}
                    <span class="font-normal"> {{ $data['agent'] }}</span>
                </th>
                <th class="text-end pt-10">
                    {{ trans('revenue.created_date') }}
                    <span class="font-normal"> {{ $data['created_date'] }}</span>
                </th>
            </tr>
        </table>
        <table class="table-bordered table-revenue-agent">
            <thead>
                <tr>
                    <th class="w-10">{{ trans('revenue.stt') }}</th>
                    <th class="w-20">{{ trans('revenue.print.price') }}</th>
                    <th class="w-20">{{ trans('revenue.print.quantity') }}</th>
                    <th>{{ trans('revenue.remarks') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['agentRevenueList'] as $key => $revenue)
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
                    <td class="text-end">{{ number_format($data['total_amount']) }}</td>
                    <td class="text-center">{{ $data['total_receipt'] }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
@stop
