<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Contracts\MerchantSocialLink as MerchantSocialLinkContract;

class MerchantSocialLink extends Model implements MerchantSocialLinkContract
{
    protected $table = 'merchant_social_links';

    protected $fillable = [
        'customer_id',
        'whatsapp',
        'facebook',
        'instagram',
        'tiktok',
        'twitter',
    ];
}
