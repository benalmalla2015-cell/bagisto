<x-shop::layouts>
    <x-slot:title>تجديد الاشتراك</x-slot>

    <div class="container mx-auto max-w-xl px-4 py-12">
        <div class="rounded-2xl border bg-white p-8 shadow dark:border-gray-800 dark:bg-gray-900">
            <h1 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">تجديد اشتراكك</h1>
            <p class="mb-6 text-gray-500 dark:text-gray-400">
                انتهت مدة اشتراكك أو تم إيقاف حسابك. لإعادة التفعيل، قم بتحويل رسوم الاشتراك إلى أحد الحسابات التالية ثم أدخل رقم الحوالة.
            </p>

            {{-- بيانات الحساب البنكي --}}
            @php $accounts = \Webkul\Core\Models\ChannelPaymentAccount::where('is_active', true)->get(); @endphp
            @if ($accounts->isNotEmpty())
                <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-950">
                    <p class="mb-2 font-semibold text-blue-700 dark:text-blue-300">بيانات التحويل:</p>
                    @foreach ($accounts as $acc)
                        <div class="mb-2 text-sm text-blue-800 dark:text-blue-200">
                            <strong>{{ $acc->company_name }}</strong><br>
                            رقم الحساب: {{ $acc->account_number }}<br>
                            اسم صاحب الحساب: {{ $acc->account_holder }}
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- نموذج تجديد الاشتراك --}}
            <form method="POST" action="{{ route('shop.subscription.renew.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الحوالة *</label>
                    <input
                        type="text"
                        name="transfer_number"
                        required
                        placeholder="أدخل رقم الحوالة البنكية"
                        class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                    />
                </div>

                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">صورة الإيصال (اختياري)</label>
                    <input type="file" name="receipt" accept="image/*,.pdf"
                        class="w-full rounded-lg border px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-blue-600 py-2 font-semibold text-white hover:bg-blue-700">
                    إرسال طلب التجديد
                </button>
            </form>

            @if (session('success'))
                <div class="mt-4 rounded-lg bg-green-100 p-3 text-sm text-green-700 dark:bg-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</x-shop::layouts>
