<?php

namespace App\Exports\Billing;

use App\Helpers\Facades\Util;
use DateTime;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class BillingAgentCollations implements FromCollection, WithHeadings, WithMapping, WithCustomCsvSettings
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
      trans('billing.receipt_id_full'),
      trans('billing.receipt_total_amount_full'),
      trans('billing.receipt_memo'),
    ];
  }

  /**
   * CSV content
   */
  public function map($data): array
  {
    $date = !$this->validateDate($data['transaction_date']) ? $data['transaction_date'] : Carbon::parse($data['transaction_date'])->format('Y/m/d');
    return [
      '="' . $date . '"',
      '="' . $data['code'] . '"',
      $data['is_total'] || !$data['is_exception'] ? number_format($data['total_amount']) : $data['total_amount'],
      $data['memo'],
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

  /** Check date*/
  protected function validateDate($date, $format = 'Y-m-d H:i:s')
  {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) == $date;
  }
}
