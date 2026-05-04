<x-admin::layouts>
    <x-slot:title>إدارة اشتراكات التجار</x-slot>

    <div class="mb-4 text-xl font-bold text-gray-800 dark:text-white">إدارة اشتراكات التجار</div>

    <div class="overflow-x-auto rounded-lg border dark:border-gray-800">
        <table class="w-full text-sm text-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-right">التاجر</th>
                    <th class="px-4 py-3 text-right">البريد</th>
                    <th class="px-4 py-3 text-right">حالة الحساب</th>
                    <th class="px-4 py-3 text-right">حالة الاشتراك</th>
                    <th class="px-4 py-3 text-right">انتهاء الاشتراك</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-t dark:border-gray-800">
                        <td class="px-4 py-3 font-medium">{{ $customer->name }}</td>
                        <td class="px-4 py-3">{{ $customer->email }}</td>
                        <td class="px-4 py-3">
                            @php $statusColors = ['active'=>'green','pending'=>'yellow','suspended'=>'red']; @endphp
                            <span class="rounded-full px-2 py-1 text-xs font-semibold
                                bg-{{ $statusColors[$customer->merchant_status] ?? 'gray' }}-100
                                text-{{ $statusColors[$customer->merchant_status] ?? 'gray' }}-700">
                                {{ ['active'=>'مفعّل','pending'=>'معلق','suspended'=>'موقوف'][$customer->merchant_status] ?? $customer->merchant_status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-semibold
                                {{ $customer->subscription_status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ['active'=>'نشط','expired'=>'منتهي','none'=>'لا يوجد'][$customer->subscription_status] ?? $customer->subscription_status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $customer->subscription_ends_at?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                {{-- تفعيل اشتراك --}}
                                <form method="POST" action="{{ route('admin.subscription.merchants.activate', $customer) }}"
                                    class="inline-flex gap-1">
                                    @csrf
                                    <select name="plan_id" class="rounded border px-2 py-1 text-xs dark:border-gray-700 dark:bg-gray-900">
                                        <option value="">بدون باقة</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded bg-green-600 px-2 py-1 text-xs text-white hover:bg-green-700">تفعيل</button>
                                </form>

                                {{-- اشتراك مجاني --}}
                                <form method="POST" action="{{ route('admin.subscription.merchants.complimentary', $customer) }}"
                                    class="inline-flex gap-1">
                                    @csrf
                                    <input type="text" name="note" placeholder="ملاحظة"
                                        class="rounded border px-2 py-1 text-xs dark:border-gray-700 dark:bg-gray-900" />
                                    <button type="submit" class="rounded bg-blue-600 px-2 py-1 text-xs text-white hover:bg-blue-700">مجاني</button>
                                </form>

                                {{-- إيقاف --}}
                                <form method="POST" action="{{ route('admin.subscription.merchants.suspend', $customer) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700"
                                        onclick="return confirm('هل أنت متأكد؟')">إيقاف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">لا يوجد تجار.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
</x-admin::layouts>
