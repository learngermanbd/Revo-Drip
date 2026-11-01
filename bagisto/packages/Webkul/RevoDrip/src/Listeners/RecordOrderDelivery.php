<?php

namespace Webkul\RevoDrip\Listeners;

use Illuminate\Support\Facades\Log;
use Throwable;
use Webkul\RevoDrip\Services\OrderDeliveryService;

class RecordOrderDelivery
{
    /**
     * Create a new listener instance.
     */
    public function __construct(protected OrderDeliveryService $deliveries) {}

    /**
     * Store the delivery details of a freshly created order.
     */
    public function handle($order): void
    {
        try {
            $this->deliveries->record($order);
        } catch (Throwable $e) {
            Log::error('RevoDrip: unable to store the delivery details of an order. '.$e->getMessage());
        }
    }
}
