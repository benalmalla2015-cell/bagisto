<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'is_active',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'is_active'   => 'boolean',
        'duration_days' => 'integer',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MerchantSubscription::class, 'plan_id');
    }
}
