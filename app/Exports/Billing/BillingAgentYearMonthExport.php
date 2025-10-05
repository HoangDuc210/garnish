<?php

namespace App\Exports\Billing;

use App\Helpers\Facades\Util;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BillingAgentYearMonthExport implements FromView, WithHeadings, WithMapping, WithCustomCsvSettings, WithColumnFormatting
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
      trans('billing.transaction_date'),
      trans('billing.receipt_id'),
      trans('billing.deposit_amount'),
      trans('billing.receipt_total_amount'),
      trans('billing.receipt_memo'),
    ];
  }

  /**
   * CSV content
   */
  public function map($data): array
  {
    return [
      '="' . $data->transaction_date . '"',
      '="' . $data->code . '"',
      $data->deposit_amount,
      $data->total_amount,
      $data->memo,
    ];
  }

  /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    /**
   * View
   */
  public function view(): View
  {
    return view('billing.csvByBillingAgentYearMonth', [
      'data' => $this->data,
      'headings' => $this->headings(),
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
