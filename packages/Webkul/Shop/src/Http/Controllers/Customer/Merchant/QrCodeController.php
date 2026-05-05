<?php

namespace Webkul\Shop\Http\Controllers\Customer\Merchant;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Webkul\Shop\Http\Controllers\Controller;

class QrCodeController extends Controller
{
    public function show(): View
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer->merchant_slug) {
            $this->ensureSlug($customer);
        }

        $storeUrl = route('shop.merchant.store.show', $customer->merchant_slug);
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?data='.urlencode($storeUrl).'&size=400x400&format=png&margin=10';

        return view('shop::customers.account.merchant.qr', compact('customer', 'storeUrl', 'qrApiUrl'));
    }

    public function regenerate(): RedirectResponse
    {
        $customer = auth()->guard('customer')->user();
        $this->ensureSlug($customer, true);

        return redirect()->route('shop.customers.account.merchant.qr')->with('success', trans('shop::app.customers.account.merchant.qr.regenerated'));
    }

    private function ensureSlug($customer, bool $force = false): void
    {
        if ($force || ! $customer->merchant_slug) {
            $base = Str::slug($customer->first_name.'-'.$customer->last_name);
            if (! $base) {
                $base = 'store';
            }
            $slug = $base.'-'.$customer->id;
            $customer->merchant_slug = $slug;
            $customer->save();
        }
    }
}
