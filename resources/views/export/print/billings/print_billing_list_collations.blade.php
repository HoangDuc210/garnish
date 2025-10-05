@extends('export.index')
@section('style')
    <link rel="stylesheet" media="print" href="{{ asset('css/theme/export/billing.css') }}">
@endsection
@section('content')
<table class="table mb-5">
    <tbody>
        <tr>
            <td class="text-end pb-3" colspan="4">
                @foreach($pages as $key => $page)
                    Page : {{ $page ? $key : 0 }} / {{ $page }}
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="text-center pb-3" colspan="4">
                <p class="title">　請 求 照 合 表　</p>
            </td>
        </tr>
        <tr colspan="4" >
            <td class="pb-3">{{ $billingAgent->name }} 御中</td>
        </tr>
        <tr>
            <td colspan="4" class="pb-3">
                <p>いつも大変お世話になっております。 {{ $month }} 月の照合表を送りいたします。</p>
                <p>ご確認の程、宜しくお願い致します。</p>
            </td>
        </tr>
        <tr>
            <td  class="text-center" colspan="4" style="font-size: 20px">
                {{ $company->name }}
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>{{ trans('billing.transaction_date') }}</th>
            <th>{{ trans('billing.receipt_id_full') }}</th>
            <th>{{ trans('billing.receipt_total_amount_full') }}</th>
            <th>{{ trans('billing.receipt_memo') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collations as $key => $collation)
            <tr class="
                @if ($collation['is_total']) tr-bold @endif
                @if ($collation['is_agent_title']) agent-title @endif">
                    <td class="text-center">
                        {{ $collation['is_exception'] ? $collation['transaction_date'] : date_format($collation->transaction_date, 'Y/m/d') }}
                    </td>
                    <td class="text-center">
                        {{ $collation['is_exception'] ? $collation['code'] : $collation->code }}
                    </td>
                    <td class="text-right">
                        {{ $collation['is_total'] || !$collation['is_exception'] ? number_format($collation['total_amount']) : $collation['total_amount'] }}
                    </td>
                    <td>
                        {{ $collation['is_exception'] ? $collation['memo'] : $collation->memo }}
                    </td>
                </tr>
            @endforeach
    </tbody>
</table>
@stop
