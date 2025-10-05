<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ProductExport implements FromCollection, WithHeadings, WithMapping, WithCustomCsvSettings
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
            trans('product.code'),
            trans('product.name'),
            trans('product.unit'),
            trans('product.quantity'),
            trans('product.price'),
        ];
    }

    /**
     * CSV content
     */
    public function map($data): array
    {
        return [
            $data->code,
            $data->name,
            $data->unit ? $data->unit->name : "",
            $data->quantity,
            $data->formatted_price,
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
