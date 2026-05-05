<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Merchant\MerchantLinksController;
use Webkul\Admin\Http\Controllers\MySubscriptionController;
use Webkul\Admin\Http\Controllers\Subscription\MerchantSubscriptionController;
use Webkul\Admin\Http\Controllers\Subscription\PlanController;

// @merchant-view: Merchant's own subscription page
Route::get('my-subscription', [MySubscriptionController::class, 'index'])->name('admin.my-subscription.index');

// @saas-admin-only — Routes below are reserved for the future SaaS Admin panel.
// They are kept in codebase but will be protected by 'saas-admin' middleware in a future phase.
Route::prefix('subscriptions')->group(function () {
    Route::get('plans', [PlanController::class, 'index'])->name('admin.subscription.plans.index');
    Route::get('plans/create', [PlanController::class, 'create'])->name('admin.subscription.plans.create');
    Route::post('plans', [PlanController::class, 'store'])->name('admin.subscription.plans.store');
    Route::get('plans/{plan}/edit', [PlanController::class, 'edit'])->name('admin.subscription.plans.edit');
    Route::put('plans/{plan}', [PlanController::class, 'update'])->name('admin.subscription.plans.update');
    Route::post('plans/{plan}/toggle', [PlanController::class, 'toggle'])->name('admin.subscription.plans.toggle');

    Route::get('merchants', [MerchantSubscriptionController::class, 'index'])->name('admin.subscription.merchants.index');
    Route::post('merchants/{customer}/activate', [MerchantSubscriptionController::class, 'activate'])->name('admin.subscription.merchants.activate');
    Route::post('merchants/{customer}/complimentary', [MerchantSubscriptionController::class, 'grantComplimentary'])->name('admin.subscription.merchants.complimentary');
    Route::post('merchants/{customer}/suspend', [MerchantSubscriptionController::class, 'suspend'])->name('admin.subscription.merchants.suspend');
});

/**
 * Merchant social links & QR management.
 */
Route::prefix('merchants')->controller(MerchantLinksController::class)->group(function () {
    Route::get('', 'index')->name('admin.merchants.index');
    Route::get('{customer}/edit', 'edit')->name('admin.merchants.edit');
    Route::put('{customer}', 'update')->name('admin.merchants.update');
    Route::post('{customer}/regenerate-slug', 'regenerateSlug')->name('admin.merchants.regenerate-slug');
});
