<?php

namespace App\Exports;

use App\Enums\TaxCategory;
use App\Services\Export\Exportable;
use Illuminate\Support\Carbon;

class PrintN335ReceiptExport extends Exportable
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
        $agent = $receipt->agent;

        if ($agent->tax_type_code === TaxCategory::TAX_INCLUDED()) {
            $receipt['is_tax'] = true;
        }else{
            $receipt['is_tax'] = false;
        }

        return view('export.print.receipts.n335.receipt', [
            'company' => $company,
            'receipt' => $receipt,
            'receiptDetails' => $receiptDetails,
            'agent' => $agent,
            'year' => Carbon::parse($receipt->transaction_date)->format('Y'),
            'month' => Carbon::parse($receipt->transaction_date)->format('m'),
            'day' => Carbon::parse($receipt->transaction_date)->format('d'),
        ]);
    }
}
