<?php

namespace Webkul\RevoDrip\Services;

use Webkul\RevoDrip\Contracts\OrderDelivery as OrderDeliveryContract;
use Webkul\RevoDrip\Repositories\OrderDeliveryRepository;

class OrderDeliveryService
{
    /**
     * Create a new service instance.
     */
    public function __construct(
        protected OrderDeliveryRepository $deliveries,
        protected DeliveryZoneService $zones
    ) {}

    /**
     * Persist the delivery details of an order, once per order.
     */
    public function record($order): ?OrderDeliveryContract
    {
        if (! $order || empty($order->id)) {
            return null;
        }

        $zoneCode = $this->zones->zoneForMethod($order->shipping_method);

        $address = $order->shipping_address ?: $order->billing_address;

        $customerName = trim(
            (string) ($address?->first_name ?? $order->customer_first_name).' '.
            (string) ($address?->last_name ?? $order->customer_last_name)
        );

        return $this->deliveries->updateOrCreate(
            ['order_id' => $order->id],
            [
                'zone_code'       => $zoneCode,
                'zone_label'      => $zoneCode ? $this->zones->label($zoneCode) : null,
                'delivery_charge' => (float) ($order->shipping_amount ?? 0),
                'full_address'    => $address?->address,
                'phone'           => $address?->phone,
                'customer_name'   => $customerName !== '' ? $customerName : null,
            ]
        );
    }

    /**
     * Delivery details already stored for an order.
     */
    public function forOrder(int $orderId): ?OrderDeliveryContract
    {
        return $this->deliveries->findOneWhere(['order_id' => $orderId]);
    }
}
