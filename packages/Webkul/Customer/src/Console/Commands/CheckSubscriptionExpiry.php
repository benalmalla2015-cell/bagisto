<?php

namespace Webkul\Customer\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Customer\Services\SubscriptionService;

class CheckSubscriptionExpiry extends Command
{
    protected $signature = 'subscriptions:check-expiry';

    protected $description = 'Mark expired merchant subscriptions and suspend their accounts';

    public function handle(SubscriptionService $service): int
    {
        $count = $service->checkAndExpire();

        $this->info("Expired {$count} subscription(s).");

        return Command::SUCCESS;
    }
}
