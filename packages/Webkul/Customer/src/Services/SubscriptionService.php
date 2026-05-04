<?php

namespace Webkul\Customer\Services;

use Carbon\Carbon;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\MerchantSubscription;
use Webkul\Customer\Models\SubscriptionPlan;

class SubscriptionService
{
    public function activate(Customer $customer, ?SubscriptionPlan $plan = null, bool $complimentary = false, ?string $note = null): MerchantSubscription
    {
        $days = $plan?->duration_days ?? 30;

        $subscription = MerchantSubscription::create([
            'customer_id'        => $customer->id,
            'plan_id'            => $plan?->id,
            'start_date'         => Carbon::today(),
            'end_date'           => Carbon::today()->addDays($days),
            'status'             => 'active',
            'is_complimentary'   => $complimentary,
            'complimentary_note' => $note,
        ]);

        $customer->update([
            'merchant_status'       => 'active',
            'subscription_status'   => 'active',
            'subscription_ends_at'  => Carbon::today()->addDays($days),
        ]);

        return $subscription;
    }

    public function suspend(Customer $customer): void
    {
        $customer->update([
            'merchant_status'     => 'suspended',
            'subscription_status' => 'expired',
        ]);

        MerchantSubscription::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);
    }

    public function checkAndExpire(): int
    {
        $expired = MerchantSubscription::where('status', 'active')
            ->where('end_date', '<', Carbon::today())
            ->get();

        foreach ($expired as $sub) {
            $sub->update(['status' => 'expired']);

            Customer::where('id', $sub->customer_id)
                ->update([
                    'merchant_status'     => 'suspended',
                    'subscription_status' => 'expired',
                ]);
        }

        return $expired->count();
    }
}
