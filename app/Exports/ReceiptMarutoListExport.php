<?php

namespace App\Exports;

use App\Enums\PrintStatus;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ReceiptMarutoListExport implements FromCollection, WithHeadings, WithMapping, WithCustomCsvSettings
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
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return (collect($this->data));
    }

    /**
     * CSV Header
     */
    public function headings(): array
    {
        return [
            trans('receipt.transaction_date'),
            trans('app.code'),
            trans('receipt.agent.name'),
            trans('receipt.total_amount'),
            trans('receipt.print_status')
        ];
    }

    /**
     * CSV content
     */
    public function map($data): array
    {
        return [
            '="' . $data->transaction_date_fm . '"',
            '="' . $data->code . '"',
            $data->agent->name,
            number_format($data->total_receipt_amount),
            PrintStatus::tryFrom($data->print_status)->label()
        ];
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
