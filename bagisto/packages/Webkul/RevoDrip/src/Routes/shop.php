<?php

use Illuminate\Support\Facades\Route;
use Webkul\RevoDrip\Http\Controllers\Shop\RevoDripCheckoutController;

Route::controller(RevoDripCheckoutController::class)->prefix('checkout')->group(function () {
    Route::post('address', 'storeAddress')->name('revodrip.checkout.address');

    Route::post('delivery-zone', 'storeDeliveryZone')->name('revodrip.checkout.delivery-zone');
});
