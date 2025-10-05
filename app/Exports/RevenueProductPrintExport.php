<?php

namespace App\Exports;

use App\Services\Export\Exportable;

class RevenueProductPrintExport extends Exportable
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
        return view('export.print.revenue.product', [
            'data' => $this->data,
        ]);
    }
}
