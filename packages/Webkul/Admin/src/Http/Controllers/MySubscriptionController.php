<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\View\View;
use Webkul\Customer\Models\MerchantSubscription;
use Webkul\Customer\Models\SubscriptionPlan;

class MySubscriptionController extends Controller
{
    /**
     * Display the merchant's own subscription page.
     */
    public function index(): View
    {
        $admin = auth()->guard('admin')->user();

        $customerId = $admin->customer_id ?? null;

        $activeSubscription = null;
        $subscriptionHistory = collect();

        if ($customerId) {
            $activeSubscription = MerchantSubscription::with('plan')
                ->where('customer_id', $customerId)
                ->where('status', 'active')
                ->latest()
                ->first();

            $subscriptionHistory = MerchantSubscription::with('plan')
                ->where('customer_id', $customerId)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }

        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('admin::my-subscription.index', compact(
            'activeSubscription',
            'subscriptionHistory',
            'plans',
            'admin'
        ));
    }
}
