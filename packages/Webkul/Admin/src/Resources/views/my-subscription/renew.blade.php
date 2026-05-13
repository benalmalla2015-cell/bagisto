<x-admin::layouts>
    <x-slot:title>تجديد الاشتراك</x-slot>

    <div class="flex items-center justify-between mb-5">
        <p class="text-xl font-bold text-gray-800 dark:text-white">تجديد الاشتراك</p>
        <a href="{{ route('admin.my-subscription.index') }}" class="text-sm text-purple-600 hover:underline">
            ← العودة إلى اشتراكي
        </a>
    </div>

    @if ($plan)
        <div class="mb-5 box-shadow rounded-xl bg-white p-6 dark:bg-gray-900">
            <p class="text-base font-semibold text-gray-800 dark:text-white mb-2">الباقة المختارة</p>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $plan->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $plan->duration_days }} يوم — {{ $plan->description }}</p>
                </div>
                <p class="text-2xl font-extrabold text-gray-800 dark:text-white">
                    {{ number_format($plan->price, 0) }} <span class="text-sm font-normal text-gray-500">ر.س</span>
                </p>
            </div>
        </div>
    @endif

    {{-- بيانات الحساب البنكي --}}
    <div class="mb-5 box-shadow rounded-xl bg-white p-6 dark:bg-gray-900">
        <p class="text-base font-semibold text-gray-800 dark:text-white mb-4">بيانات التحويل البنكي</p>

        @if ($accounts->isNotEmpty())
            <div class="space-y-4">
                @foreach ($accounts as $acc)
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-950">
                        <p class="font-bold text-blue-800 dark:text-blue-200 mb-2">{{ $acc->company_name }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-blue-700 dark:text-blue-300">
                            <div>
                                <span class="text-blue-500 dark:text-blue-400">رقم الحساب:</span>
                                <span class="font-mono font-semibold" dir="ltr">{{ $acc->account_number }}</span>
                            </div>
                            <div>
                                <span class="text-blue-500 dark:text-blue-400">اسم صاحب الحساب:</span>
                                <span class="font-semibold">{{ $acc->account_holder }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-3 dark:border-amber-800 dark:bg-amber-950">
                <p class="text-sm text-amber-700 dark:text-amber-300">
                    ⚠️ بعد إتمام التحويل، أدخل رقم الحوالة أدناه وارفع صورة الإيصال (إن وجد).
                    سيتم تفعيل اشتراكك بعد مراجعة الإدارة.
                </p>
            </div>
        @else
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800 text-center">
                <p class="text-gray-500 dark:text-gray-400">لا توجد حسابات بنكية مفعلة حالياً. تواصل مع الإدارة.</p>
            </div>
        @endif
    </div>

    {{-- نموذج إرسال طلب التجديد --}}
    <div class="box-shadow rounded-xl bg-white p-6 dark:bg-gray-900">
        <p class="text-base font-semibold text-gray-800 dark:text-white mb-4">تأكيد التحويل</p>

        <form method="POST" action="{{ route('admin.my-subscription.renew.store') }}" enctype="multipart/form-data">
            @csrf

            @if ($plan)
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            @endif

            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    رقم الحوالة / التحويل <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="transfer_number"
                    required
                    placeholder="أدخل رقم الحوالة البنكية"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-purple-500 focus:ring-purple-500"
                />
                @error('transfer_number')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    صورة الإيصال (اختياري)
                </label>
                <input
                    type="file"
                    name="receipt"
                    accept="image/*,.pdf"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white file:mr-4 file:rounded-lg file:border-0 file:bg-purple-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900 dark:file:text-purple-300"
                />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">jpg, jpeg, png, pdf — بحد أقصى 5 ميجا</p>
                @error('receipt')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="rounded-lg bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                >
                    إرسال طلب الاشتراك
                </button>
                <a
                    href="{{ route('admin.my-subscription.index') }}"
                    class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</x-admin::layouts>
