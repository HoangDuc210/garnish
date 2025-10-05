<?php

namespace App\Providers;

use App\Helpers\Helpers;
use App\Services\Export\MergePdf;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('util', Helpers::class);
        $this->app->bind('MergePdf', MergePdf::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('_partials.pagination');
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        //Check request is contain export in router
        $fullUrl = request()->fullUrl();
        if (strpos($fullUrl, 'export') !== false) {
            //Set Japanese language
            $this->setLocale('ja');
        }
    }

    private function setLocale($language)
    {
        app()->setLocale($language);

        //Change language
        //session()->put('locale', $language);
        //$request->session()->put('locale', $language);
        session(['locale' => $language]);
        setlocale(LC_TIME, $language);

        Carbon::setLocale($language);
        App::setLocale($language);
    }
}
