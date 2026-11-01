<?php

namespace Webkul\RevoDrip\Shipping\Carriers;

use Webkul\Checkout\Models\CartShippingRate;
use Webkul\RevoDrip\Services\DeliveryZoneService;
use Webkul\Shipping\Carriers\AbstractShipping;

class DeliveryZone extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     *
     * @var string
     */
    protected $code = 'revodrip';

    /**
     * Shipping method code.
     *
     * @var string
     */
    protected $method = 'revodrip_inside_dhaka';

    /**
     * The zone service, resolved lazily because Bagisto instantiates carriers without arguments.
     */
    protected function zones(): DeliveryZoneService
    {
        return app(DeliveryZoneService::class);
    }

    /**
     * Whether any delivery zone is available.
     */
    public function isAvailable()
    {
        $zones = $this->zones();

        return $zones->isEnabled() && $zones->codes() !== [];
    }

    /**
     * Rate title shown to the customer.
     */
    public function getTitle()
    {
        return trans('revodrip::app.delivery.carrier-title');
    }

    /**
     * Rate description shown to the customer.
     */
    public function getDescription()
    {
        return trans('revodrip::app.delivery.carrier-description');
    }

    /**
     * One rate per enabled delivery zone, priced from the server side settings.
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $rates = [];

        foreach ($this->zones()->codes() as $zoneCode) {
            $rates[] = $this->rateFor($zoneCode);
        }

        return $rates === [] ? false : $rates;
    }

    /**
     * Build the shipping rate of one zone.
     */
    protected function rateFor(string $zoneCode): CartShippingRate
    {
        $zones = $this->zones();

        $rate = new CartShippingRate;

        $rate->carrier = $this->getCode();

        $rate->carrier_title = trans('revodrip::app.delivery.carrier-title');

        $rate->method = $zones->method($zoneCode);

        $rate->method_title = $zones->label($zoneCode);

        $rate->method_description = trans('revodrip::app.delivery.carrier-description');

        $rate->price = core()->convertPrice($zones->charge($zoneCode));

        $rate->base_price = $zones->charge($zoneCode);

        return $rate;
    }
}
