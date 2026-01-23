<?php

namespace App\Listeners;

use App\Events\ProductStockLow;
use App\Jobs\SendLowStockNotification;

class TriggerLowStockNotification
{
    /**
     * Handle the event.
     */
    public function handle(ProductStockLow $event): void
    {
        SendLowStockNotification::dispatch($event->product);
    }
}