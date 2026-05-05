<x-admin::layouts>
    <x-slot:title>إدارة التجار — روابط التواصل و QR</x-slot>

    <div class="mb-5 flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">إدارة التجار — روابط التواصل و QR</p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:bg-green-900 dark:text-green-100">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border dark:border-gray-800">
        <table class="w-full text-sm text-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-right">التاجر</th>
                    <th class="px-4 py-3 text-right">البريد</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">الـ Slug</th>
                    <th class="px-4 py-3 text-right">روابط مُضافة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($merchants as $merchant)
                    @php
                        $statusMap = ['active' => ['label' => 'نشط', 'color' => 'green'], 'pending' => ['label' => 'معلق', 'color' => 'yellow'], 'suspended' => ['label' => 'موقوف', 'color' => 'red']];
                        $st = $statusMap[$merchant->merchant_status] ?? ['label' => $merchant->merchant_status, 'color' => 'gray'];
                        $links = $merchant->socialLinks;
                        $linkCount = $links ? collect(['whatsapp','facebook','instagram','tiktok','twitter'])->filter(fn($k) => !empty($links->$k))->count() : 0;
                    @endphp
                    <tr class="border-t dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                        <td class="px-4 py-3 font-medium">{{ $merchant->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $merchant->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-semibold bg-{{ $st['color'] }}-100 text-{{ $st['color'] }}-700">
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $merchant->merchant_slug ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($linkCount > 0)
                                <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">{{ $linkCount }} روابط</span>
                            @else
                                <span class="text-gray-400">لا يوجد</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a
                                href="{{ route('admin.merchants.edit', $merchant->id) }}"
                                class="rounded bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700"
                            >
                                تعديل الروابط و QR
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">لا يوجد تجار مسجلون.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $merchants->links() }}
    </div>

</x-admin::layouts>
