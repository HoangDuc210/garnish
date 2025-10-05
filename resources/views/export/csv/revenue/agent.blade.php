@extends('export.index')
@section('content')
    <table class="table-head">
        <tr>
            <td>{{ trans('revenue.aggregation_date') }}</td>
            <td>{{ $data['aggregation_date'] }}</td>
        </tr>
        <tr>
            <td>{{ trans('revenue.choose_agent') }}</td>
            <td>{{ $data['agent'] }}</td>
        </tr>
    </table>

    <table class="table-bordered">
        <thead>
            <tr>
                <th class="w-10">{{ trans('revenue.stt') }}</th>
                <th class="w-20">{{ trans('revenue.csv.agent.sales_amount') }}</th>
                <th class="w-20">{{ trans('revenue.csv.agent.quantity') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['agentRevenueList'] as $key => $revenue)
                <tr>
                    <td class="text-center">{{'="' .  sprintf('%03d', ++$key) . '"' }}</td>
                    <td class="text-end">
                        {{ count($revenue['receipt']) ? number_format(array_sum($revenue['receipt'])) : null }}
                    </td>
                    <td class="text-center">{{ count($revenue['receipt']) ? count($revenue['receipt']) : null }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center">{{ trans('app.total_amount') }}</td>
                <td class="text-end">{{ number_format($data['total_amount']) }}</td>
                <td class="text-center">{{ $data['total_receipt'] }}</td>
            </tr>
        </tfoot>
    </table>
@stop
