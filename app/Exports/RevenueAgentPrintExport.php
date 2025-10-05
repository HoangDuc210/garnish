<?php

namespace App\Exports;

use App\Services\Export\Exportable;

class RevenueAgentPrintExport extends Exportable
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
        return view('export.print.revenue.agent', [
            'data' => $this->data,
        ]);
    }
}
