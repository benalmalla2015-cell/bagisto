<?php

namespace Webkul\Admin\Http\Controllers\Merchant;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Repositories\MerchantSocialLinkRepository;

class MerchantLinksController extends Controller
{
    public function __construct(
        protected MerchantSocialLinkRepository $socialLinkRepository
    ) {}

    public function index(): View
    {
        $merchants = Customer::with('socialLinks')
            ->whereIn('merchant_status', ['active', 'pending', 'suspended'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin::merchants.index', compact('merchants'));
    }

    public function edit(Customer $customer): View
    {
        $socialLinks = $customer->socialLinks;

        $storeUrl = $customer->merchant_slug
            ? route('shop.merchant.store.show', $customer->merchant_slug)
            : null;

        $qrApiUrl = $storeUrl
            ? 'https://api.qrserver.com/v1/create-qr-code/?data='.urlencode($storeUrl).'&size=300x300&format=png&margin=10'
            : null;

        return view('admin::merchants.edit', compact('customer', 'socialLinks', 'storeUrl', 'qrApiUrl'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'whatsapp'  => 'nullable|url|max:500',
            'facebook'  => 'nullable|url|max:500',
            'instagram' => 'nullable|url|max:500',
            'tiktok'    => 'nullable|url|max:500',
            'twitter'   => 'nullable|url|max:500',
        ]);

        $this->socialLinkRepository->updateOrCreate(
            ['customer_id' => $customer->id],
            array_merge(['customer_id' => $customer->id], $request->only(['whatsapp', 'facebook', 'instagram', 'tiktok', 'twitter']))
        );

        return redirect()->back()->with('success', 'تم حفظ روابط التواصل الاجتماعي بنجاح.');
    }

    public function regenerateSlug(Customer $customer): RedirectResponse
    {
        $base = \Illuminate\Support\Str::slug($customer->first_name.'-'.$customer->last_name);
        $slug = ($base ?: 'store').'-'.$customer->id.'-'.time();
        $customer->merchant_slug = $slug;
        $customer->save();

        return redirect()->back()->with('success', 'تم إعادة توليد رمز QR بنجاح.');
    }
}
