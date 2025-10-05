@extends('export.index')
@section('content')
    <table class="table-bordered">
        <thead>
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data['billingReceipts'] as $billingReceipt)
                <tr>
                    <td class="text-end">{{ '="' . Util::formattedDate($billingReceipt['transaction_date']) . '"' }}</td>
                    <td class="text-end">{{ '="' . $billingReceipt['code'] . '"' }}</td>
                    <td class="text-right">
                        {{
                            $billingReceipt['is_deposit'] ||
                            $billingReceipt['is_total'] ?
                            number_format($billingReceipt['deposit_amount']) : ''
                        }}
                    </td>
                    <td class="text-right">
                        {{
                            $billingReceipt['is_receipt'] ||
                            $billingReceipt['is_total'] ||
                            $billingReceipt['is_sub_total'] ?
                            number_format($billingReceipt['total_amount']) : ''
                        }}
                    </td>
                    <td>{{ $billingReceipt['memo'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
