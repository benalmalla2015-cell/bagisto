<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\PaymentAccountController;

Route::prefix('payment-accounts')->controller(PaymentAccountController::class)->group(function () {
    Route::get('', 'index')->name('admin.payment-accounts.index');
    Route::post('', 'store')->name('admin.payment-accounts.store');
    Route::put('{id}', 'update')->name('admin.payment-accounts.update');
    Route::post('{id}/toggle', 'toggle')->name('admin.payment-accounts.toggle');
    Route::delete('{id}', 'destroy')->name('admin.payment-accounts.destroy');
});
