<x-admin::layouts>
    <x-slot:title>الحسابات البنكية والصرافة</x-slot>

    {{-- ══ Page Header ══ --}}
    <div class="mb-5 flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">الحسابات البنكية / الصرافة</p>
        <button
            type="button"
            class="flex cursor-pointer items-center gap-1.5 rounded-md border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            @click="$dispatch('open-modal', { name: 'add-account-modal' })"
            x-data
        >
            + إضافة حساب
        </button>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-700 dark:bg-green-950 dark:text-green-200">
            ✔ {{ session('success') }}
        </div>
    @endif

    {{-- ══ Accounts Table ══ --}}
    <div class="box-shadow rounded-xl bg-white dark:bg-gray-900">
        @if ($accounts->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="mb-3 text-5xl">🏦</div>
                <p class="text-base font-semibold text-gray-700 dark:text-white">لا توجد حسابات بعد</p>
                <p class="mt-1 text-sm text-gray-400">أضف أول حساب بنكي أو صرافة لعرضه للعملاء عند الدفع.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-xs font-semibold text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            <th class="px-5 py-3 text-right">الشعار</th>
                            <th class="px-5 py-3 text-right">اسم البنك / الصرافة</th>
                            <th class="px-5 py-3 text-right">رقم الحساب / الآيبان</th>
                            <th class="px-5 py-3 text-right">صاحب الحساب</th>
                            <th class="px-5 py-3 text-center">الحالة</th>
                            <th class="px-5 py-3 text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr class="border-b last:border-0 dark:border-gray-800">
                                <td class="px-5 py-3">
                                    @if ($account->logo_url)
                                        <img
                                            src="{{ $account->logo_url }}"
                                            alt="{{ $account->company_name }}"
                                            class="h-10 w-10 rounded-md border object-contain p-0.5 dark:border-gray-700"
                                        />
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-md bg-gray-100 text-lg dark:bg-gray-800">🏦</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-semibold text-gray-800 dark:text-white">
                                    {{ $account->company_name }}
                                </td>
                                <td class="px-5 py-3 font-mono text-gray-600 dark:text-gray-300">
                                    {{ $account->account_number }}
                                </td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $account->account_holder }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div
                                        x-data="{ active: {{ $account->is_active ? 'true' : 'false' }} }"
                                        class="flex justify-center"
                                    >
                                        <button
                                            type="button"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                            :class="active ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"
                                            @click="
                                                active = !active;
                                                fetch('{{ route('admin.payment-accounts.toggle', $account->id) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                        'Accept': 'application/json'
                                                    }
                                                });
                                            "
                                        >
                                            <span
                                                class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                                :class="active ? 'translate-x-1 rtl:-translate-x-5' : 'translate-x-6 rtl:-translate-x-1'"
                                            ></span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        {{-- Edit Button --}}
                                        <button
                                            type="button"
                                            class="text-blue-600 hover:underline"
                                            x-data
                                            @click="$dispatch('open-modal', { name: 'edit-account-modal-{{ $account->id }}' })"
                                        >
                                            تعديل
                                        </button>

                                        {{-- Delete Form --}}
                                        <form
                                            action="{{ route('admin.payment-accounts.destroy', $account->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('هل تريد حذف هذا الحساب؟')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- ══ Edit Modal ══ --}}
                            <x-admin::modal :name="'edit-account-modal-' . $account->id">
                                <x-slot:header>
                                    <p class="font-semibold text-gray-800 dark:text-white">تعديل: {{ $account->company_name }}</p>
                                </x-slot>
                                <x-slot:content>
                                    <form
                                        action="{{ route('admin.payment-accounts.update', $account->id) }}"
                                        method="POST"
                                        enctype="multipart/form-data"
                                        class="space-y-4"
                                        id="edit-form-{{ $account->id }}"
                                    >
                                        @csrf
                                        @method('PUT')
                                        @include('admin::payment-accounts._form', ['account' => $account])
                                    </form>
                                </x-slot>
                                <x-slot:footer>
                                    <button
                                        type="submit"
                                        form="edit-form-{{ $account->id }}"
                                        class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                                    >
                                        حفظ التعديلات
                                    </button>
                                </x-slot>
                            </x-admin::modal>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ══ Add Modal ══ --}}
    <x-admin::modal name="add-account-modal">
        <x-slot:header>
            <p class="font-semibold text-gray-800 dark:text-white">إضافة حساب جديد</p>
        </x-slot>
        <x-slot:content>
            <form
                action="{{ route('admin.payment-accounts.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-4"
                id="add-account-form"
            >
                @csrf
                @include('admin::payment-accounts._form', ['account' => null])
            </form>
        </x-slot>
        <x-slot:footer>
            <button
                type="submit"
                form="add-account-form"
                class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                إضافة الحساب
            </button>
        </x-slot>
    </x-admin::modal>
</x-admin::layouts>
