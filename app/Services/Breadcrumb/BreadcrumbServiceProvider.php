<?php

namespace App\Services\Breadcrumb;

use Illuminate\Support\ServiceProvider;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('breadcrumb', function () {
            return new Handler();
        });
    }
}
