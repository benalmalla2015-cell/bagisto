<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelPaymentAccount extends Model
{
    protected $fillable = [
        'channel_id',
        'company_name',
        'logo_path',
        'account_number',
        'account_holder',
        'is_active',
        'sort_order',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? \Illuminate\Support\Facades\Storage::url($this->logo_path)
            : null;
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
