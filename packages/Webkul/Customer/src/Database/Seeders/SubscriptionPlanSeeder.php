<?php

namespace Webkul\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Customer\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'أساسي',    'price' => 99,  'duration_days' => 30,  'description' => 'باقة شهرية مناسبة للبداية'],
            ['name' => 'احترافي',  'price' => 249, 'duration_days' => 90,  'description' => 'باقة ربع سنوية للتجار النشطين'],
            ['name' => 'سنوي',    'price' => 799, 'duration_days' => 365, 'description' => 'باقة سنوية بأفضل سعر'],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(
                ['name' => $plan['name']],
                array_merge($plan, ['is_active' => true])
            );
        }
    }
}
