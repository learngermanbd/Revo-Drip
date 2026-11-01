<?php

use Illuminate\Support\Facades\Route;
use Webkul\RevoDrip\Http\Controllers\Admin\CheckoutSettingsController;
use Webkul\RevoDrip\Http\Controllers\Admin\DashboardController;
use Webkul\RevoDrip\Http\Controllers\Admin\DeliverySettingsController;
use Webkul\RevoDrip\Http\Controllers\Admin\GeneralSettingsController;

Route::prefix('revodrip')->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('revodrip.admin.dashboard.index');

    Route::get('checkout', [CheckoutSettingsController::class, 'edit'])->name('revodrip.admin.checkout.edit');
    Route::post('checkout', [CheckoutSettingsController::class, 'update'])->name('revodrip.admin.checkout.update');

    Route::get('delivery', [DeliverySettingsController::class, 'edit'])->name('revodrip.admin.delivery.edit');
    Route::post('delivery', [DeliverySettingsController::class, 'update'])->name('revodrip.admin.delivery.update');

    Route::get('general', [GeneralSettingsController::class, 'edit'])->name('revodrip.admin.general.edit');
    Route::post('general', [GeneralSettingsController::class, 'update'])->name('revodrip.admin.general.update');
});
