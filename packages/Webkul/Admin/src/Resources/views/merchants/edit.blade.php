<x-admin::layouts>
    <x-slot:title>تعديل روابط التاجر: {{ $customer->name }}</x-slot>

    {{-- Header --}}
    <div class="mb-5 flex items-center gap-3">
        <a href="{{ route('admin.merchants.index') }}" class="text-gray-400 hover:text-gray-700">
            <span class="icon-arrow-right text-xl rtl:rotate-180"></span>
        </a>
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            تعديل روابط التاجر: {{ $customer->name }}
        </p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Social Links Form --}}
        <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">روابط التواصل الاجتماعي</h2>

            <form action="{{ route('admin.merchants.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                @foreach ([
                    'whatsapp'  => 'واتساب',
                    'facebook'  => 'فيسبوك',
                    'instagram' => 'إنستغرام',
                    'tiktok'    => 'تيك توك',
                    'twitter'   => 'تويتر / X',
                ] as $field => $label)
                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $label }}
                        </label>
                        <input
                            type="url"
                            name="{{ $field }}"
                            value="{{ old($field, $socialLinks?->$field) }}"
                            placeholder="https://..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                        />
                        @error($field)
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <button
                    type="submit"
                    class="mt-2 w-full rounded-lg bg-indigo-600 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    حفظ الروابط
                </button>
            </form>
        </div>

        {{-- QR Code Panel --}}
        <div class="rounded-xl border bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">رمز QR الخاص بالمتجر</h2>

            @if ($storeUrl)
                <div class="mb-4 flex justify-center">
                    <img
                        src="{{ $qrApiUrl }}"
                        alt="QR Code"
                        class="h-48 w-48 rounded-lg border border-gray-200 p-2"
                    />
                </div>

                <div class="mb-4 rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-800">
                    <p class="mb-1 text-xs font-medium text-gray-500">رابط متجر التاجر (العام)</p>
                    <a href="{{ $storeUrl }}" target="_blank" class="break-all text-xs text-blue-600 hover:underline">
                        {{ $storeUrl }}
                    </a>
                </div>

                <div class="flex gap-3">
                    <a
                        href="{{ $qrApiUrl }}"
                        download="qr-{{ $customer->merchant_slug }}.png"
                        class="flex-1 rounded-lg border border-indigo-300 py-2 text-center text-sm font-medium text-indigo-600 hover:bg-indigo-50"
                    >
                        تحميل QR
                    </a>

                    <form
                        action="{{ route('admin.merchants.regenerate-slug', $customer->id) }}"
                        method="POST"
                        class="flex-1"
                        onsubmit="return confirm('إعادة توليد رمز QR ستغير رابط متجر التاجر. تأكيد؟')"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="w-full rounded-lg border border-gray-300 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50"
                        >
                            إعادة توليد
                        </button>
                    </form>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center text-gray-400">
                    <span class="text-4xl">📷</span>
                    <p class="mt-2 text-sm">لم يتم توليد رمز QR بعد.</p>
                    <p class="mt-1 text-xs text-gray-400">
                        يتم توليد الـ QR تلقائياً عند أول زيارة التاجر لصفحة QR من حسابه.
                    </p>

                    <form
                        action="{{ route('admin.merchants.regenerate-slug', $customer->id) }}"
                        method="POST"
                        class="mt-4"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                        >
                            توليد رمز QR الآن
                        </button>
                    </form>
                </div>
            @endif

            {{-- Merchant Info --}}
            <div class="mt-6 border-t pt-4 dark:border-gray-700">
                <p class="text-xs font-medium text-gray-500">معلومات التاجر</p>
                <table class="mt-2 w-full text-xs text-gray-600 dark:text-gray-400">
                    <tr>
                        <td class="py-1 font-medium">الاسم</td>
                        <td>{{ $customer->name }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-medium">البريد</td>
                        <td>{{ $customer->email }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-medium">الهاتف</td>
                        <td>{{ $customer->phone ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-medium">حالة الاشتراك</td>
                        <td>{{ $customer->subscription_status ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-medium">انتهاء الاشتراك</td>
                        <td>{{ $customer->subscription_ends_at ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

</x-admin::layouts>
