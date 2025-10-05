<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\AccessHistoryService;

class LoggedOut
{
    /**
     * @var \App\Services\AccessHistoryService
     */
    protected $accessHistoryService;

    /**
     * @param \App\Services\AccessHistoryService
     */
    public function __construct(AccessHistoryService $accessHistoryService)
    {
        $this->accessHistoryService = $accessHistoryService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->accessHistoryService->setLogoutLog();
    }
}
