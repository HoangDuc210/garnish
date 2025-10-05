<?php

namespace App\Exports\Billing;

use App\Helpers\Facades\Util;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class YearMonthExport implements FromView, WithHeadings, WithMapping, WithCustomCsvSettings
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
      trans('billing.id'),
      trans('billing.billing_agent_name'),
      trans('billing.last_billed_amount'),
      trans('billing.deposit_amount'),
      trans('billing.carried_forward_amount'),
      trans('billing.receipt_amount'),
      trans('billing.tax_amount'),
      trans('billing.billing_amount'),

    ];
  }


  /**
   * View
   */
  public function view(): View
  {
    return view('billing.csvByYearMonth', [
      'data' => $this->data,
      'headings' => $this->headings(),
    ]);
  }

  /**
   * CSV content
   */
  public function map($data): array
  {
    return [
      $data->id,
      $data->billing_agent_name,
      $data->last_billed_amount,
      $data->deposit_amount,
      $data->carried_forward_amount,
      $data->receipt_amount,
      $data->tax_amount,
      $data->billing_amount,
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
