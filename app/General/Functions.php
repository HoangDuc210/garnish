<?php

namespace App\General;
use Carbon\Carbon;

class Functions
{
    /**
     * Get year month options
     */
    public static function makeYearMonthOptions()
    {
        $today = Carbon::now();
        $year3Ago = $today->copy()->subYears(3);

        $start = strtotime($year3Ago->format('Y-m'));
        $end = strtotime($today->format('Y-m'));
        $range = array(date('Y/m/d', $start) => date('Y/m', $start));
        while ( $start <= strtotime('-1 month', $end) ) {
            $start = strtotime('+1 month', $start);
            $yearMonth = date('Y/m', $start);
            $yearMonthDay = date('Y-m-d', $start);
            $range[$yearMonthDay] = $yearMonth;
        }

        krsort($range);

        return $range;
    }

    /**
     * Make option by list
     */
    public static function makeOptionsByList($list, $keyFieldName, $valueFieldName)
    {
        $result = [];

        foreach($list as $item) {
            $key = $item[$keyFieldName];
            $value = $item[$valueFieldName];

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * get date string with days
     * @param int $days
     *
     * @return string
     */
    public static function getDateStringWithDays($days, $withTime = false)
    {
        $today = Carbon::now();
        $resultDate = $today->copy()->addDays($days);

        if ($withTime) {
            return $resultDate->format('Y年m月d日 H:s:i');
        }
        else {
            return $resultDate->format('Y年m月d日');
        }
    }

    /**
     * get date string with days
     * @param string $yearMonth
     *
     * @return string
     */
    public static function getYearMonthInfoString($yearMonth)
    {
        if (!$yearMonth) {
            return 'Y/m/d ~ Y/m/d';
        }

        $calculateDate = Carbon::parse($yearMonth);
        $firstDate = $calculateDate->copy()->firstOfMonth();  
        $lastDate = $calculateDate->copy()->lastOfMonth();

        return $firstDate->format('Y/m/d').' ~ '.$lastDate->format('Y/m/d');
    }

    /**
     * make query string parameters
     * @param array $payload
     *
     * @return string
     */
    public static function makeQueryStringParameters($payload)
    {
        $result = [];

        foreach($payload as $key => $value) {
            $newValue = $value;

            if (str_contains($key, 'in___')) {
                $newValue =  $value ? explode(",", $value) : [];
            }

            $result[$key] = $newValue;
        }

        return $result;
    }
}