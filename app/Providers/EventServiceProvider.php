<?php

namespace App\Providers;

use App\Events\ProductAgentEvent;
use App\Events\UpdateProductReceiptEvent;
use App\Listeners\LoggedIn;
use App\Listeners\LoggedOut;
use App\Listeners\ProductAgentListener;
use App\Listeners\UpdateProductReceiptListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LoggedIn::class,
        ],
        Logout::class => [
            LoggedOut::class,
        ],
        UpdateProductReceiptEvent::class => [
            UpdateProductReceiptListener::class,
        ],
        ProductAgentEvent::class => [
            ProductAgentListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
