<?php

namespace App\Listeners;

use App\Services\ReceiptDetailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductReceiptListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * @var $data
     * @var \App\Repositories\ReceiptDetailService
     */
    protected $receiptDetailService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ReceiptDetailService $receiptDetailService)
    {
        $this->receiptDetailService = $receiptDetailService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //Update products of receipt
        $this->receiptDetailService->updateDetailReceipts($event->data["receipt_details"]);
        //Delete products of receipt
        if (!empty($event->data["product_remove"])) {
            $this->receiptDetailService->removeProducts($event->data["product_remove"]);
        }

        return;
    }
}
