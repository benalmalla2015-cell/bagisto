<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Core\Models\ChannelPaymentAccount;
use Illuminate\Support\Facades\Storage;

class PaymentAccountController extends Controller
{
    /**
     * Display all bank/exchange accounts for the current channel.
     */
    public function index(): View
    {
        $channel = core()->getCurrentChannel();

        $accounts = ChannelPaymentAccount::where('channel_id', $channel->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $accountsData = $accounts->map(function ($a) {
            return [
                'id'             => $a->id,
                'company_name'   => $a->company_name,
                'account_number' => $a->account_number,
                'account_holder' => $a->account_holder,
                'is_active'      => (bool) $a->is_active,
                'sort_order'     => (int) $a->sort_order,
                'logo_url'       => $a->logo_url,
                'has_logo'       => (bool) $a->logo_path,
                'update_url'     => route('admin.payment-accounts.update', $a->id),
                'toggle_url'     => route('admin.payment-accounts.toggle', $a->id),
            ];
        })->values()->toArray();

        return view('admin::payment-accounts.index', compact('accounts', 'channel', 'accountsData'));
    }

    /**
     * Store a new payment account.
     */
    public function store(): RedirectResponse
    {
        $channel = core()->getCurrentChannel();

        request()->validate([
            'company_name'   => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'logo'           => 'nullable|image|max:2048',
        ]);

        $logoPath = null;
        if (request()->hasFile('logo') && request()->file('logo')->isValid()) {
            $logoPath = request()->file('logo')->store('payment-logos', 'public');
        }

        ChannelPaymentAccount::create([
            'channel_id'     => $channel->id,
            'company_name'   => request('company_name'),
            'account_number' => request('account_number'),
            'account_holder' => request('account_holder'),
            'logo_path'      => $logoPath,
            'is_active'      => request()->boolean('is_active', true),
            'sort_order'     => ChannelPaymentAccount::where('channel_id', $channel->id)->count(),
        ]);

        session()->flash('success', 'تم إضافة الحساب بنجاح.');

        return redirect()->route('admin.payment-accounts.index');
    }

    /**
     * Update an existing payment account.
     */
    public function update(int $id): RedirectResponse
    {
        $channel = core()->getCurrentChannel();

        $account = ChannelPaymentAccount::where('id', $id)
            ->where('channel_id', $channel->id)
            ->firstOrFail();

        request()->validate([
            'company_name'   => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'logo'           => 'nullable|image|max:2048',
        ]);

        $logoPath = $account->logo_path;
        if (request()->hasFile('logo') && request()->file('logo')->isValid()) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = request()->file('logo')->store('payment-logos', 'public');
        } elseif (request()->boolean('remove_logo') && $logoPath) {
            Storage::disk('public')->delete($logoPath);
            $logoPath = null;
        }

        $account->update([
            'company_name'   => request('company_name'),
            'account_number' => request('account_number'),
            'account_holder' => request('account_holder'),
            'logo_path'      => $logoPath,
            'is_active'      => request()->boolean('is_active'),
            'sort_order'     => request()->integer('sort_order', $account->sort_order),
        ]);

        session()->flash('success', 'تم تحديث الحساب بنجاح.');

        return redirect()->route('admin.payment-accounts.index');
    }

    /**
     * Toggle active status via AJAX.
     */
    public function toggle(int $id): JsonResponse
    {
        $channel = core()->getCurrentChannel();

        $account = ChannelPaymentAccount::where('id', $id)
            ->where('channel_id', $channel->id)
            ->firstOrFail();

        $account->update(['is_active' => ! $account->is_active]);

        return response()->json([
            'status'    => true,
            'is_active' => $account->is_active,
        ]);
    }

    /**
     * Delete a payment account.
     */
    public function destroy(int $id): RedirectResponse
    {
        $channel = core()->getCurrentChannel();

        $account = ChannelPaymentAccount::where('id', $id)
            ->where('channel_id', $channel->id)
            ->firstOrFail();

        if ($account->logo_path) {
            Storage::disk('public')->delete($account->logo_path);
        }

        $account->delete();

        session()->flash('success', 'تم حذف الحساب بنجاح.');

        return redirect()->route('admin.payment-accounts.index');
    }
}
