<?php

namespace App\Services\Breadcrumb;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static \App\Services\Breadcrumb\Handler push($name, $link = null)
 * @method static pushGenealogy(array $data)
 * @method static array | \Illuminate\Support\Collection render()
 * @method static bool isEmpty()
 * @method static string jsonLdListItem()
 *
 * @see \App\Services\Breadcrumb\Handler
 *
 */
class Breadcrumb extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'breadcrumb';
    }
}
