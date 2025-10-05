<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Helpers
{
    /**
     * Get all roles of system
     *
     * @return \Illuminate\Support\Collection
     *
     */
    public function roles(): \Illuminate\Support\Collection
    {
        return collect([]);
    }

    /**
     * Product measurement unit
     *
     * @return \Illuminate\Support\Collection
     */
    public function measurements(): \Illuminate\Support\Collection
    {
        return collect(config('measurements'));
    }

    /**
     * Get unit of product form measurement
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProductUnit($unitId)
    {
        return $this->measurements()[$unitId];
    }

    /**
     * Currency display format
     *
     * @param float|int $number
     *
     * @return string
     */
    public function currencyFormat(float|int $number): string
    {
        return number_format($number, 2);
    }

    /**
     * Format money
     * Replace comma
     * @param string $money
     *
     * @return string
     */
    public function formattedPrice($money)
    {
        return str_replace(',', '', $money);
    }

    /**
     * Format name for receipt
    */
    public function formatMoneyPrint($money)
    {
        return implode("  ", str_split(str_replace(',', '', number_format($money))));
    }

    /**
     * Add param url receipt
    */
    public function addParamReceipt()
    {
        return Carbon::now()->subMonths('2')->format('Y/m/d');
    }

    //
    public function formattedDate($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        $arrDate = explode("-", $date);
        return $arrDate[0] . '年' . (int)$arrDate[1] . '月' . (int)$arrDate[2] . '日';
    }
}
