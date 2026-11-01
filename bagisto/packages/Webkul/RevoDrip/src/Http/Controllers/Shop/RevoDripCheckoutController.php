<?php

namespace Webkul\RevoDrip\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Facades\Payment;
use Webkul\RevoDrip\Http\Requests\CheckoutAddressRequest;
use Webkul\RevoDrip\Services\AddressMapper;
use Webkul\RevoDrip\Services\DeliveryZoneService;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Shop\Http\Resources\CartResource;

class RevoDripCheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected AddressMapper $addressMapper,
        protected DeliveryZoneService $zones
    ) {}

    /**
     * Save the four customer fields, apply the delivery zone and hand back payment methods.
     */
    public function storeAddress(CheckoutAddressRequest $request): JsonResponse
    {
        if (Cart::hasError() || ! Cart::getCart()) {
            return $this->cartRedirect();
        }

        if (Cart::requiresSignInToCheckout()) {
            session()->put('shop.url.intended', route('shop.checkout.onepage.index'));

            return response()->json([
                'redirect'     => true,
                'redirect_url' => route('shop.customer.session.index'),
            ]);
        }

        $data = $request->validated();

        /**
         * Signed in customers keep their account as the order contact. The checkout field is
         * read only, so this only defends against a tampered request.
         */
        if ($customer = auth()->guard('customer')->user()) {
            $data['email'] = $customer->email;
        }

        $zone = $this->resolveZone($data);

        Cart::saveAddresses($this->addressMapper->toCartAddresses($data));

        Cart::collectTotals();

        if ($cart = Cart::getCart()) {
            if ($cart->haveStockableItems()) {
                $this->applyDeliveryZone($zone);

                Cart::collectTotals();
            }
        }

        $cart = Cart::getCart();

        if (! $cart) {
            return $this->cartRedirect();
        }

        $charge = $zone ? $this->zones->charge($zone) : 0.0;

        return response()->json([
            'redirect'                  => false,
            'zone'                      => $zone,
            'zone_label'                => $zone ? $this->zones->label($zone) : null,
            'delivery_charge'           => $charge,
            'formatted_delivery_charge' => core()->currency($charge),
            'data'                      => Payment::getSupportedPaymentMethods(),
            'cart'                      => new CartResource($cart),
        ]);
    }

    /**
     * Re-apply a delivery zone so the cart totals update as soon as the shopper switches area.
     */
    public function storeDeliveryZone(Request $request): JsonResponse
    {
        $data = $request->validate([
            'delivery_zone' => ['required', 'string', Rule::in($this->zones->codes())],
        ]);

        if (! Cart::getCart() || Cart::hasError()) {
            return $this->cartRedirect();
        }

        $this->applyDeliveryZone($data['delivery_zone']);

        Cart::collectTotals();

        $cart = Cart::getCart();

        if (! $cart) {
            return $this->cartRedirect();
        }

        return response()->json([
            'zone'                      => $data['delivery_zone'],
            'zone_label'                => $this->zones->label($data['delivery_zone']),
            'delivery_charge'           => $this->zones->charge($data['delivery_zone']),
            'formatted_delivery_charge' => core()->currency($this->zones->charge($data['delivery_zone'])),
            'shipping_amount'           => (float) $cart->shipping_amount,
            'formatted_shipping_amount' => core()->currency($cart->shipping_amount),
            'grand_total'               => (float) $cart->grand_total,
            'formatted_grand_total'     => core()->currency($cart->grand_total),
        ]);
    }

    /**
     * Store the zone as the cart shipping method, priced by the server side carrier.
     */
    protected function applyDeliveryZone(?string $zone): void
    {
        if (! $zone || ! $this->zones->exists($zone) || ! $this->zones->isEnabled()) {
            return;
        }

        if (! Cart::getCart()?->haveStockableItems()) {
            return;
        }

        if (! Shipping::collectRates()) {
            return;
        }

        $method = $this->zones->method($zone);

        if (Shipping::isMethodCodeExists($method)) {
            Cart::saveShippingMethod($method);
        }
    }

    /**
     * The zone this submission is charged for.
     *
     * When the store hides the delivery area selector the shopper has no choice to make, so the
     * cart's existing zone wins over anything posted, which keeps the selection unambiguous.
     */
    protected function resolveZone(array $data): ?string
    {
        $cartZone = $this->zones->zoneForMethod(Cart::getCart()?->shipping_method);

        if (! $this->zones->selectionVisible()) {
            return $cartZone ?? $this->zones->defaultZone();
        }

        return $data['delivery_zone'] ?? $cartZone ?? $this->zones->defaultZone();
    }

    /**
     * Send the shopper back to the cart when the cart is not usable.
     */
    protected function cartRedirect(): JsonResponse
    {
        return response()->json([
            'redirect'     => true,
            'redirect_url' => route('shop.checkout.cart.index'),
        ]);
    }
}
