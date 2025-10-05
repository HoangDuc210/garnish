<?php

namespace App\Exports;

use App\Helpers\Facades\Util;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class AgentExport implements FromCollection, WithHeadings, WithMapping, WithCustomCsvSettings
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
            trans('agent.code'),
            trans('agent.name'),
            trans('agent.zip_code'),
            trans('agent.address'),
            trans('agent.address_more'),
            trans('agent.tel'),
            trans('agent.fax')

        ];
    }

    /**
     * CSV content
     */
    public function map($data): array
    {
        return [
            '="' . $data['code'] . '"',
            $data['name'],
            $data['post_code'],
            $data['address'],
            $data['address_more'],
            '="' . $data['tel'] . '"',
            '="' . $data['fax'] . '"',
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
