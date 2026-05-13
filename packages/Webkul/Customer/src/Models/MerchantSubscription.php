<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantSubscription extends Model
{
    protected $fillable = [
        'customer_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'transfer_number',
        'receipt_path',
        'is_complimentary',
        'complimentary_note',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'is_complimentary' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
