<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ReceiptCsvExport implements FromView, WithCustomCsvSettings
{
    /**
     * Data to export
     */
    protected $data;

    /**
     * Constructor
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * View
     */
    public function view(): View
    {
        return view('export.csv.receipt.receipt', [
            'data' => $this->data
        ]);
    }

    /**
     * Setting csv
     */
    public function getCsvSettings(): array
    {
        return [
            'use_bom' => true,
            'output_encoding' => 'UTF-8',
        ];
    }
}
