<?php

namespace App\Listeners;

use App\Services\ProductAgentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductAgentListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var \App\Services\ProductAgentService
     */
    protected $productAgentService;

    /**
     * @param \App\Services\ProductAgentService $productAgentService
     */
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ProductAgentService $productAgentService)
    {
        $this->productAgentService = $productAgentService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $data = [
            'receipt_details' => $event->data['receipt_details'],
            'agent_id' => $event->data['agent_id'],
        ];
        return $this->productAgentService->store($data);
    }
}
