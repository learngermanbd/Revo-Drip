<?php

namespace Webkul\RevoDrip\Services;

class DeliveryZoneService
{
    /**
     * Zone code for deliveries inside Dhaka.
     */
    public const INSIDE = 'inside_dhaka';

    /**
     * Zone code for deliveries outside Dhaka.
     */
    public const OUTSIDE = 'outside_dhaka';

    /**
     * Prefix used by every delivery zone shipping method.
     */
    public const METHOD_PREFIX = 'revodrip';

    /**
     * Create a new service instance.
     */
    public function __construct(protected SettingsService $settings) {}

    /**
     * Whether delivery zone selection is switched on.
     */
    public function isEnabled(): bool
    {
        return (bool) $this->settings->get('delivery.enabled', true);
    }

    /**
     * Every configured zone code.
     */
    public function codes(): array
    {
        return array_keys((array) $this->settings->get('delivery.zones', []));
    }

    /**
     * Whether the given code is a configured zone.
     */
    public function exists(?string $code): bool
    {
        return $code !== null && in_array($code, $this->codes(), true);
    }

    /**
     * Server side delivery charge for a zone.
     */
    public function charge(string $code): float
    {
        if (! $this->exists($code)) {
            return 0.0;
        }

        return round(max(0, (float) $this->settings->get("delivery.zones.{$code}.charge", 0)), 4);
    }

    /**
     * Admin editable label for a zone.
     */
    public function label(string $code): string
    {
        $label = (string) $this->settings->get("delivery.zones.{$code}.label", $code);

        return $label !== '' ? $label : $code;
    }

    /**
     * Shipping method code Bagisto stores on the cart and the order.
     */
    public function method(string $code): string
    {
        return self::METHOD_PREFIX.'_'.$code;
    }

    /**
     * Zone code carried by a stored shipping method, if any.
     */
    public function zoneForMethod(?string $method): ?string
    {
        if (! $method || ! str_starts_with($method, self::METHOD_PREFIX.'_')) {
            return null;
        }

        $code = substr($method, strlen(self::METHOD_PREFIX) + 1);

        return $this->exists($code) ? $code : null;
    }

    /**
     * Zones ready for the storefront and admin screens.
     */
    public function zones(): array
    {
        $zones = [];

        foreach ($this->codes() as $code) {
            $charge = $this->charge($code);

            $zones[$code] = [
                'code'             => $code,
                'label'            => $this->label($code),
                'charge'           => $charge,
                'formatted_charge' => core()->currency($charge),
                'method'           => $this->method($code),
            ];
        }

        return $zones;
    }

    /**
     * Zone preselected on the checkout form.
     */
    public function defaultZone(): ?string
    {
        $default = (string) $this->settings->get('delivery.default_zone', self::INSIDE);

        if ($this->exists($default)) {
            return $default;
        }

        return $this->codes()[0] ?? null;
    }
}
