<?php

namespace App\Exports;

use App\Services\Export\Exportable;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class PrintPaymentRequestReceiptExport extends Exportable
{
    /**
     * @var \App\Models\Model
     */
    protected $data;

    /**
     * @param \App\Models\data $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    protected function view()
    {
        $company = $this->data['company'];
        $receipt = $this->data['receipt'];

        return view('export.print.receipts.receipt', [
            'title' => '請求書',
            'company' => $company,
            'receipt' => $receipt,
            'receiptDetails' => $receipt->receiptDetails,
            'agent' => $receipt->agent,
            'total_amount' => $receipt->totalAmount,
            'transaction_date'  => Carbon::parse($receipt->transaction_date)->format('Y年m月d日'),
            'description' => trans('receipt.description_payment_request'),

        ]);
    }
}
