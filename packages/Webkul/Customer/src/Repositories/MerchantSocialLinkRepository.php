<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\MerchantSocialLink;

class MerchantSocialLinkRepository extends Repository
{
    public function model(): string
    {
        return MerchantSocialLink::class;
    }
}
