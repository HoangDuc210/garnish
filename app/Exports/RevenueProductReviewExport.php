<?php

namespace App\Exports;

use App\Services\Export\Exportable;

class RevenueProductReviewExport extends Exportable
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
        return view('export.preview.revenue.product', [
            'data' => $this->data,
        ]);
    }
}
