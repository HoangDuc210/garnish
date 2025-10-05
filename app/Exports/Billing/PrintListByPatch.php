<?php

namespace App\Exports\Billing;

use App\Services\Export\Exportable;

class PrintListByPatch extends Exportable
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
        return view('export.print.billings.print_billing_list_by_patch', $this->data);
    }
}
