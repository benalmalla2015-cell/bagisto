<x-admin::layouts>
    <x-slot:title>اشتراكي</x-slot>

    <div class="flex items-center justify-between mb-5">
        <p class="text-xl font-bold text-gray-800 dark:text-white">اشتراكي</p>
    </div>

    @php
        $daysLeft    = $activeSubscription ? now()->diffInDays($activeSubscription->end_date, false) : null;
        $totalDays   = $activeSubscription ? $activeSubscription->start_date->diffInDays($activeSubscription->end_date) : 1;
        $usedPercent = $activeSubscription ? max(0, min(100, round(($totalDays - max($daysLeft, 0)) / max($totalDays, 1) * 100))) : 0;
    @endphp

    {{-- ══ Active Subscription Card ══ --}}
    @if ($activeSubscription)
        @if ($daysLeft !== null && $daysLeft <= 7 && $daysLeft >= 0)
            <div class="mb-4 flex items-center gap-3 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800 dark:border-amber-700 dark:bg-amber-950 dark:text-amber-200">
                ⏰ اشتراكك ينتهي خلال <strong>{{ $daysLeft }} {{ $daysLeft == 1 ? 'يوم' : 'أيام' }}</strong> — جدّد الآن
                <a href="{{ route('shop.subscription.renew') }}" class="ms-auto rounded-md border border-amber-500 px-3 py-1 text-xs hover:bg-amber-100 dark:hover:bg-amber-900">
                    تجديد الاشتراك
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 mb-5">
            <!-- Main Subscription Card -->
            <div class="col-span-2 box-shadow rounded-xl bg-white p-6 dark:bg-gray-900">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            {{ $activeSubscription->plan->name ?? 'باقة مخصصة' }}
                            @if ($activeSubscription->is_complimentary)
                                <span class="ms-2 rounded-full bg-purple-100 px-2 py-0.5 text-xs font-semibold text-purple-700 dark:bg-purple-900 dark:text-purple-200">🎁 مجاني</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $activeSubscription->plan->description ?? '' }}
                        </p>
                    </div>
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-200">
                        ● نشط
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="mb-1 flex justify-between text-xs text-gray-500">
                        <span>بدأ: {{ $activeSubscription->start_date->format('Y/m/d') }}</span>
                        <span>ينتهي: {{ $activeSubscription->end_date->format('Y/m/d') }}</span>
                    </div>
                    <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                        <div
                            class="h-2.5 rounded-full {{ $daysLeft <= 7 ? 'bg-amber-500' : 'bg-purple-600' }}"
                            style="width: {{ $usedPercent }}%"
                        ></div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 text-right">
                        @if ($daysLeft !== null && $daysLeft >= 0)
                            متبقي <strong class="{{ $daysLeft <= 7 ? 'text-amber-600' : 'text-purple-600' }}">{{ $daysLeft }} يوم</strong>
                        @else
                            <span class="text-red-500">منتهي الصلاحية</span>
                        @endif
                    </p>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                        <p class="text-xs text-gray-500 mb-1">تاريخ بداية الاشتراك</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $activeSubscription->start_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                        <p class="text-xs text-gray-500 mb-1">موعد التجديد / السداد القادم</p>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $activeSubscription->end_date->format('d/m/Y') }}</p>
                    </div>
                    @if (! $activeSubscription->is_complimentary && $activeSubscription->plan)
                        <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                            <p class="text-xs text-gray-500 mb-1">تكلفة الباقة</p>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ number_format($activeSubscription->plan->price, 2) }} ر.س</p>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                            <p class="text-xs text-gray-500 mb-1">مدة الباقة</p>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $activeSubscription->plan->duration_days }} يوم</p>
                        </div>
                    @endif
                    @if ($activeSubscription->complimentary_note)
                        <div class="col-span-2 rounded-lg bg-purple-50 p-3 dark:bg-purple-950">
                            <p class="text-xs text-purple-500 mb-1">ملاحظة الاشتراك</p>
                            <p class="text-sm text-purple-800 dark:text-purple-200">{{ $activeSubscription->complimentary_note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Renew Action Card -->
            <div class="box-shadow rounded-xl bg-white p-6 dark:bg-gray-900 flex flex-col justify-between">
                <div>
                    <p class="text-base font-semibold text-gray-800 dark:text-white mb-2">تجديد الاشتراك</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        قم بتجديد اشتراكك قبل انتهائه للاستمرار في استخدام جميع مزايا المنصة.
                    </p>
                </div>
                <a
                    href="{{ route('shop.subscription.renew') }}"
                    class="mt-4 block rounded-lg bg-purple-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-purple-700"
                >
                    تجديد الاشتراك الآن
                </a>
            </div>
        </div>
    @else
        <!-- No Subscription -->
        <div class="mb-5 box-shadow rounded-xl bg-white p-8 dark:bg-gray-900 text-center">
            <div class="text-5xl mb-3">📭</div>
            <p class="text-lg font-semibold text-gray-800 dark:text-white mb-2">لا يوجد اشتراك نشط</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">اختر إحدى الباقات أدناه للبدء.</p>
            <a
                href="{{ route('shop.subscription.renew') }}"
                class="inline-block rounded-lg bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-purple-700"
            >
                اشترك الآن
            </a>
        </div>
    @endif

    {{-- ══ Available Plans ══ --}}
    <div class="box-shadow rounded-xl bg-white p-6 dark:bg-gray-900 mb-5">
        <p class="text-base font-semibold text-gray-800 dark:text-white mb-4">الباقات المتاحة</p>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($plans as $plan)
                @php $isCurrent = $activeSubscription && $activeSubscription->plan_id === $plan->id; @endphp
                <div class="rounded-xl border-2 p-5 transition {{ $isCurrent ? 'border-purple-500 dark:border-purple-400 bg-purple-50 dark:bg-purple-950' : 'border-gray-200 dark:border-gray-700' }}">
                    @if ($isCurrent)
                        <span class="mb-2 inline-block rounded-full bg-purple-600 px-2 py-0.5 text-xs font-semibold text-white">✓ باقتك الحالية</span>
                    @endif
                    <p class="text-base font-bold text-gray-800 dark:text-white">{{ $plan->name }}</p>
                    <p class="text-2xl font-extrabold text-purple-600 dark:text-purple-400 my-1">
                        {{ number_format($plan->price, 0) }} <span class="text-sm font-normal text-gray-500">ر.س</span>
                    </p>
                    <p class="text-xs text-gray-500 mb-3">{{ $plan->duration_days }} يوم — {{ $plan->description }}</p>
                    @if (! $isCurrent)
                        <a
                            href="{{ route('shop.subscription.renew') }}?plan_id={{ $plan->id }}"
                            class="block rounded-lg border border-purple-500 px-3 py-1.5 text-center text-sm font-medium text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-950"
                        >
                            اختر هذه الباقة
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ══ Subscription History ══ --}}
    @if ($subscriptionHistory->isNotEmpty())
        <div class="box-shadow rounded-xl bg-white p-6 dark:bg-gray-900">
            <p class="text-base font-semibold text-gray-800 dark:text-white mb-4">سجل الاشتراكات</p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right">
                    <thead>
                        <tr class="border-b text-xs text-gray-500 dark:border-gray-700">
                            <th class="pb-2 font-medium">الباقة</th>
                            <th class="pb-2 font-medium">تاريخ البداية</th>
                            <th class="pb-2 font-medium">تاريخ الانتهاء</th>
                            <th class="pb-2 font-medium">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptionHistory as $sub)
                            <tr class="border-b last:border-0 dark:border-gray-800">
                                <td class="py-2.5 font-medium text-gray-800 dark:text-white">
                                    {{ $sub->plan->name ?? 'مخصص' }}
                                    @if ($sub->is_complimentary) <span class="text-purple-500 text-xs">(مجاني)</span> @endif
                                </td>
                                <td class="py-2.5 text-gray-600 dark:text-gray-400">{{ $sub->start_date->format('d/m/Y') }}</td>
                                <td class="py-2.5 text-gray-600 dark:text-gray-400">{{ $sub->end_date->format('d/m/Y') }}</td>
                                <td class="py-2.5">
                                    @php
                                        $badge = match($sub->status) {
                                            'active'    => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200',
                                            'expired'   => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200',
                                            'cancelled' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                            default     => 'bg-amber-100 text-amber-700',
                                        };
                                        $label = match($sub->status) {
                                            'active'    => 'نشط',
                                            'expired'   => 'منتهي',
                                            'cancelled' => 'ملغى',
                                            default     => 'معلّق',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $label }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-admin::layouts>
