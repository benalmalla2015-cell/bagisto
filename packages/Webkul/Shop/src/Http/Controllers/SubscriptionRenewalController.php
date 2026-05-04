<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Customer\Models\MerchantSubscription;

class SubscriptionRenewalController extends Controller
{
    public function show(): View
    {
        return view('shop::subscription.renew');
    }

    public function store(): RedirectResponse
    {
        request()->validate([
            'transfer_number' => 'required|string|max:100',
            'receipt'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $customer = auth()->guard('customer')->user();

        $receiptPath = null;
        if (request()->hasFile('receipt')) {
            $receiptPath = request()->file('receipt')->store('subscription-receipts', 'public');
        }

        MerchantSubscription::create([
            'customer_id' => $customer->id,
            'plan_id'     => null,
            'start_date'  => now()->toDateString(),
            'end_date'    => now()->addMonth()->toDateString(),
            'status'      => 'pending',
        ]);

        session()->flash('success', 'تم إرسال طلب التجديد، سيتم مراجعته من قِبَل الإدارة قريباً.');

        return back();
    }
}
