<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMerchantSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            return $next($request);
        }

        if ($customer->merchant_status === 'suspended') {
            return redirect()->route('shop.subscription.renew')
                ->with('warning', 'حسابك موقوف. يرجى تجديد اشتراكك للمتابعة.');
        }

        if ($customer->subscription_status === 'expired') {
            return redirect()->route('shop.subscription.renew')
                ->with('warning', 'انتهت مدة اشتراكك. يرجى التجديد للمتابعة.');
        }

        return $next($request);
    }
}
