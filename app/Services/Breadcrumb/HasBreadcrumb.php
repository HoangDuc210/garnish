<?php

namespace App\Services\Breadcrumb;

trait HasBreadcrumb
{
    /**
     * @return \App\Services\Breadcrumb\Handler
     */
    public function breadcrumb()
    {
        return app('breadcrumb');
    }
}
