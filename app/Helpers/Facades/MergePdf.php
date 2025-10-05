<?php

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection roles()
 * @method static \Illuminate\Support\Collection measurements()
 * @method static float|string currencyFormat()
 *
 * @see \App\Helpers\Helpers
 *
 */
class MergePdf extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MergePdf';
    }
}
