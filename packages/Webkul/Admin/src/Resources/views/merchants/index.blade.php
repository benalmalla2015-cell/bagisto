<x-admin::layouts>
    <x-slot:title>إدارة التجار</x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">إدارة التجار — روابط التواصل و QR</p>
    </div>

    <div class="mt-6 box-shadow rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">

        @if (session('success'))
            <div class="m-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-xs font-medium uppercase text-gray-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                        <th class="px-6 py-4 text-right">التاجر</th>
                        <th class="px-6 py-4 text-right">البريد الإلكتروني</th>
                        <th class="px-6 py-4 text-right">حالة الحساب</th>
                        <th class="px-6 py-4 text-right">رابط المتجر (Slug)</th>
                        <th class="px-6 py-4 text-right">روابط التواصل</th>
                        <th class="px-6 py-4 text-right">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($merchants as $merchant)
                        @php
                            $statusMap = [
                                'active'    => ['label' => 'نشط',    'class' => 'bg-green-100 text-green-700'],
                                'pending'   => ['label' => 'معلق',   'class' => 'bg-yellow-100 text-yellow-700'],
                                'suspended' => ['label' => 'موقوف',  'class' => 'bg-red-100 text-red-700'],
                            ];
                            $st = $statusMap[$merchant->merchant_status] ?? ['label' => $merchant->merchant_status, 'class' => 'bg-gray-100 text-gray-600'];
                            $links = $merchant->socialLinks;
                            $platforms = ['whatsapp','facebook','instagram','tiktok','twitter'];
                            $linkCount = $links ? collect($platforms)->filter(fn($k) => !empty($links->$k))->count() : 0;
                        @endphp
                        <tr class="text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 font-medium">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                                        {{ strtoupper(substr($merchant->first_name, 0, 1)) }}
                                    </div>
                                    <span>{{ $merchant->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $merchant->email }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $st['class'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if ($merchant->merchant_slug)
                                    <a href="{{ route('shop.merchant.store.show', $merchant->merchant_slug) }}" target="_blank" class="text-indigo-600 hover:underline">
                                        {{ $merchant->merchant_slug }}
                                    </a>
                                @else
                                    <span class="text-gray-400">لم يُحدد بعد</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($linkCount > 0)
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ $linkCount }} / 5 منصات
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">لا توجد روابط</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a
                                    href="{{ route('admin.merchants.edit', $merchant->id) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-300 bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100"
                                >
                                    <span class="icon-edit text-base"></span>
                                    تعديل
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <span class="icon-customer-2 text-5xl"></span>
                                    <p class="text-sm">لا يوجد تجار مسجلون بعد.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($merchants->hasPages())
            <div class="border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                {{ $merchants->links() }}
            </div>
        @endif
    </div>

</x-admin::layouts>
