<?php

namespace Webkul\Admin\Http\Controllers\Subscription;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Models\SubscriptionPlan;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::orderBy('price')->get();

        return view('admin::subscription.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin::subscription.plans.create');
    }

    public function store(): RedirectResponse
    {
        request()->validate([
            'name'         => 'required|string|max:191',
            'price'        => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'description'  => 'nullable|string',
        ]);

        SubscriptionPlan::create(request()->only(['name', 'price', 'duration_days', 'description', 'is_active']));

        session()->flash('success', 'تم إنشاء الباقة بنجاح.');

        return redirect()->route('admin.subscription.plans.index');
    }

    public function edit(SubscriptionPlan $plan): View
    {
        return view('admin::subscription.plans.edit', compact('plan'));
    }

    public function update(SubscriptionPlan $plan): RedirectResponse
    {
        request()->validate([
            'name'         => 'required|string|max:191',
            'price'        => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        $plan->update(request()->only(['name', 'price', 'duration_days', 'description', 'is_active']));

        session()->flash('success', 'تم تحديث الباقة بنجاح.');

        return redirect()->route('admin.subscription.plans.index');
    }

    public function toggle(SubscriptionPlan $plan): JsonResponse
    {
        $plan->update(['is_active' => ! $plan->is_active]);

        return new JsonResponse(['status' => $plan->is_active]);
    }
}
