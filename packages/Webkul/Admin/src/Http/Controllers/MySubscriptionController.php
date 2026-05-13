<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
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

    public function renew(): View
    {
        $admin = auth()->guard('admin')->user();
        $customerId = $admin->customer_id ?? null;
        $planId = request('plan_id');

        $plan = $planId ? SubscriptionPlan::find($planId) : null;
        $accounts = \Webkul\Core\Models\ChannelPaymentAccount::where('is_active', true)->get();

        return view('admin::my-subscription.renew', compact('plan', 'accounts', 'admin', 'customerId'));
    }

    public function renewStore(): RedirectResponse
    {
        $admin = auth()->guard('admin')->user();
        $customerId = $admin->customer_id ?? null;

        if (! $customerId) {
            return back()->with('error', 'لا يوجد حساب عميل مرتبط بهذا المستخدم.');
        }

        request()->validate([
            'plan_id'         => 'required|exists:subscription_plans,id',
            'transfer_number' => 'required|string|max:100',
            'receipt'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $plan = SubscriptionPlan::find(request('plan_id'));

        $receiptPath = null;
        if (request()->hasFile('receipt')) {
            $receiptPath = request()->file('receipt')->store('subscription-receipts', 'public');
        }

        MerchantSubscription::create([
            'customer_id'      => $customerId,
            'plan_id'          => $plan->id,
            'start_date'       => now()->toDateString(),
            'end_date'         => now()->addDays($plan->duration_days)->toDateString(),
            'status'           => 'pending',
            'transfer_number'  => request('transfer_number'),
            'receipt_path'     => $receiptPath,
        ]);

        session()->flash('success', 'تم إرسال طلب الاشتراك، سيتم مراجعته من قِبَل الإدارة قريباً.');

        return redirect()->route('admin.my-subscription.index');
    }
}
