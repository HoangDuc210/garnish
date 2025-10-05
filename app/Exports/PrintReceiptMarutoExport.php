<?php

namespace App\Exports;

use App\Enums\TaxCategory;
use App\Services\Export\Exportable;
use Illuminate\Support\Carbon;

class PrintReceiptMarutoExport extends Exportable
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
        $receiptDetails = $this->data['receiptDetails'];
        $consumptionTax = $this->data['consumptionTax'];
        $totalReceiptAmount = $this->data['totalReceiptAmount'];
        $numberPage = $this->data['numberPage'];
        $agent = $receipt->agent;

        if ($agent->tax_type_code === TaxCategory::TAX_INCLUDED()) {
            $receipt['is_tax'] = true;
        }else{
            $receipt['is_tax'] = false;
        }

        return view('export.print.receipts.receipt_maruto', [
            'numberPage' => $numberPage,
            'company' => $company,
            'receipt' => $receipt,
            'receiptDetails' => $receiptDetails,
            'consumptionTax' => $consumptionTax,
            'agent' => $receipt->agent,
            'year'  => Carbon::parse($receipt->transaction_date)->format('Y'),
            'month'  => Carbon::parse($receipt->transaction_date)->format('m'),
            'day'  => Carbon::parse($receipt->transaction_date)->format('d'),
        ]);
    }
}
