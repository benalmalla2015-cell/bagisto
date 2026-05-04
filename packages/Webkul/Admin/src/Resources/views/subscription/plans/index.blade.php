<x-admin::layouts>
    <x-slot:title>باقات الاشتراك</x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">باقات الاشتراك</p>

        <a href="{{ route('admin.subscription.plans.create') }}"
            class="primary-button cursor-pointer">
            + إضافة باقة
        </a>
    </div>

    <div class="mt-6 overflow-x-auto rounded-lg border dark:border-gray-800">
        <table class="w-full text-sm text-gray-700 dark:text-gray-300">
            <thead class="bg-gray-50 text-xs uppercase dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-right">الاسم</th>
                    <th class="px-4 py-3 text-right">السعر</th>
                    <th class="px-4 py-3 text-right">المدة (أيام)</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plans as $plan)
                    <tr class="border-t dark:border-gray-800">
                        <td class="px-4 py-3">{{ $plan->name }}</td>
                        <td class="px-4 py-3">{{ number_format($plan->price, 2) }} ر.س</td>
                        <td class="px-4 py-3">{{ $plan->duration_days }} يوم</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs font-semibold
                                {{ $plan->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $plan->is_active ? 'مفعّل' : 'معطّل' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.subscription.plans.edit', $plan) }}"
                                class="text-blue-600 hover:underline">تعديل</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">لا توجد باقات بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin::layouts>
