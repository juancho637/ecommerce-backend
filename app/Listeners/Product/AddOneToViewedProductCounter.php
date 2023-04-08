<?php

namespace App\Listeners\Product;

use App\Events\Product\ProductViewed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddOneToViewedProductCounter implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Product\ProductViewed  $event
     * @return void
     */
    public function handle(ProductViewed $event)
    {
        $event->product->update([
            'amount_viewed' => $event->product->amount_viewed + 1,
        ]);
    }
}
