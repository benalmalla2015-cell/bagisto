<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\View\View;
use Webkul\Customer\Models\Customer;

class MerchantStoreController extends Controller
{
    public function show(string $slug): View
    {
        $merchant = Customer::where('merchant_slug', $slug)
            ->where('merchant_status', 'active')
            ->where('subscription_status', 'active')
            ->firstOrFail();

        $socialLinks = $merchant->socialLinks;

        return view('shop::merchant.store', compact('merchant', 'socialLinks'));
    }
}
