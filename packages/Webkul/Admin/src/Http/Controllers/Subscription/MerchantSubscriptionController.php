<?php

namespace Webkul\Admin\Http\Controllers\Subscription;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\MerchantSubscription;
use Webkul\Customer\Models\SubscriptionPlan;
use Webkul\Customer\Services\SubscriptionService;

class MerchantSubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $service) {}

    public function index(): View
    {
        $customers = Customer::with(['subscriptions.plan'])
            ->whereIn('merchant_status', ['pending', 'active', 'suspended'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('admin::subscription.merchants.index', compact('customers', 'plans'));
    }

    public function activate(Customer $customer): RedirectResponse
    {
        $plan = SubscriptionPlan::find(request('plan_id'));

        $this->service->activate($customer, $plan);

        session()->flash('success', "تم تفعيل اشتراك {$customer->name} بنجاح.");

        return back();
    }

    public function grantComplimentary(Customer $customer): RedirectResponse
    {
        request()->validate(['note' => 'nullable|string|max:500']);

        $plan = SubscriptionPlan::find(request('plan_id'));

        $this->service->activate($customer, $plan, true, request('note'));

        session()->flash('success', "تم منح اشتراك مجاني لـ {$customer->name}.");

        return back();
    }

    public function suspend(Customer $customer): RedirectResponse
    {
        $this->service->suspend($customer);

        session()->flash('success', "تم إيقاف حساب {$customer->name}.");

        return back();
    }
}
