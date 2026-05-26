<?php

namespace App\Listeners;

use App\Events\ProductOutOfStock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifySellerOutOfStock
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(ProductOutOfStock $event): void
    {
        // Simulate sending a notification or trigger AI alert
        \Illuminate\Support\Facades\Log::info("Product Out of Stock Alert triggered for product: {$event->product->id} (Seller: {$event->product->user_id})");
        
        // This is where one could plug the MachineLearningService if needed right on OOS
    }
}
