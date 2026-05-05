<?php

namespace Webkul\Shop\Http\Controllers\Customer\Merchant;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Customer\Repositories\MerchantSocialLinkRepository;
use Webkul\Shop\Http\Controllers\Controller;

class SocialLinksController extends Controller
{
    public function __construct(
        protected MerchantSocialLinkRepository $socialLinkRepository
    ) {}

    public function edit(): View
    {
        $customer = auth()->guard('customer')->user();
        $socialLinks = $customer->socialLinks;

        return view('shop::customers.account.merchant.social-links.edit', compact('socialLinks'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'whatsapp'  => 'nullable|url|max:500',
            'facebook'  => 'nullable|url|max:500',
            'instagram' => 'nullable|url|max:500',
            'tiktok'    => 'nullable|url|max:500',
            'twitter'   => 'nullable|url|max:500',
        ]);

        $customer = auth()->guard('customer')->user();

        $this->socialLinkRepository->updateOrCreate(
            ['customer_id' => $customer->id],
            array_merge(['customer_id' => $customer->id], $request->only(['whatsapp', 'facebook', 'instagram', 'tiktok', 'twitter']))
        );

        return redirect()->back()->with('success', trans('shop::app.customers.account.merchant.social-links.success'));
    }
}
