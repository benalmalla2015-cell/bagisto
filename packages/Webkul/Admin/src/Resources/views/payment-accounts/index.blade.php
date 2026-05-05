<x-admin::layouts>
    <x-slot:title>الحسابات البنكية والصرافة</x-slot>

    <div
        x-data="{
            showAddModal: false,
            editId: null,
            showEditModal: false,
            currentEdit: {},
            previewAdd: null,
            previewEdit: null,

            openEdit(acc) {
                this.currentEdit = acc;
                this.previewEdit = acc.logo_url;
                this.showEditModal = true;
            },

            closeAll() {
                this.showAddModal = false;
                this.showEditModal = false;
                this.previewAdd = null;
            }
        }"
    >

    {{-- ══ Page Header ══ --}}
    <div class="mb-5 flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">الحسابات البنكية / الصرافة</p>
        <button
            type="button"
            class="flex cursor-pointer items-center gap-1.5 rounded-md border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            @click.prevent="showAddModal = true"
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
                                        <img src="{{ $account->logo_url }}" alt="{{ $account->company_name }}"
                                            class="h-10 w-10 rounded-md border object-contain p-0.5 dark:border-gray-700" />
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-md bg-gray-100 text-lg dark:bg-gray-800">🏦</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-semibold text-gray-800 dark:text-white">{{ $account->company_name }}</td>
                                <td class="px-5 py-3 font-mono text-gray-600 dark:text-gray-300">{{ $account->account_number }}</td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $account->account_holder }}</td>
                                <td class="px-5 py-3 text-center">
                                    <div x-data="{ active: {{ $account->is_active ? 'true' : 'false' }} }" class="flex justify-center">
                                        <button
                                            type="button"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                            :class="active ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"
                                            @click.prevent="active = !active; fetch('{{ route('admin.payment-accounts.toggle', $account->id) }}', { method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept':'application/json'} });"
                                        >
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                                :class="active ? 'translate-x-1 rtl:-translate-x-5' : 'translate-x-6 rtl:-translate-x-1'"></span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button
                                            type="button"
                                            class="text-blue-500 hover:underline"
                                            @click.prevent="openEdit({
                                                id: {{ $account->id }},
                                                company_name: '{{ addslashes($account->company_name) }}',
                                                account_number: '{{ addslashes($account->account_number) }}',
                                                account_holder: '{{ addslashes($account->account_holder) }}',
                                                is_active: {{ $account->is_active ? 'true' : 'false' }},
                                                sort_order: {{ $account->sort_order }},
                                                logo_url: '{{ $account->logo_url ?? '' }}',
                                                has_logo: {{ $account->logo_path ? 'true' : 'false' }},
                                                update_url: '{{ route('admin.payment-accounts.update', $account->id) }}'
                                            })"
                                        >
                                            تعديل
                                        </button>
                                        <form action="{{ route('admin.payment-accounts.destroy', $account->id) }}" method="POST"
                                            onsubmit="return confirm('هل تريد حذف هذا الحساب؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ══ ADD MODAL (Alpine.js) ══ --}}
    <div
        x-show="showAddModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        style="display:none"
    >
        <div class="absolute inset-0 bg-black/60" @click.prevent="showAddModal = false"></div>
        <div class="relative z-10 w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl dark:bg-gray-900 mx-4">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-800 dark:text-white">إضافة حساب جديد</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" @click.prevent="showAddModal = false">✕</button>
            </div>
            <form
                action="{{ route('admin.payment-accounts.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-4"
            >
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">اسم البنك / الصرافة <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" placeholder="مثال: بنك الراجحي" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الحساب / الآيبان <span class="text-red-500">*</span></label>
                    <input type="text" name="account_number" placeholder="SA00 0000 0000 0000" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-mono text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">اسم صاحب الحساب <span class="text-red-500">*</span></label>
                    <input type="text" name="account_holder" placeholder="الاسم الكامل" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                </div>
                <div x-data="{ preview: null }">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">شعار البنك / الصرافة</label>
                    <template x-if="preview">
                        <img :src="preview" class="mb-2 h-14 w-14 rounded-lg border object-contain p-1 dark:border-gray-600" />
                    </template>
                    <input type="file" name="logo" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:me-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-600 dark:file:bg-blue-950 dark:file:text-blue-300"
                        @change="const f=$event.target.files[0]; if(f) preview=URL.createObjectURL(f);" />
                    <p class="mt-1 text-xs text-gray-400">PNG / JPG / SVG — حجم أقصى 2MB</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="add_is_active" checked class="rounded" />
                    <label for="add_is_active" class="text-sm text-gray-700 dark:text-gray-300">مفعّل (يظهر للعملاء)</label>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click.prevent="showAddModal = false"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:text-gray-300">إلغاء</button>
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">إضافة الحساب</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ EDIT MODAL (Alpine.js) ══ --}}
    <div
        x-show="showEditModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        style="display:none"
    >
        <div class="absolute inset-0 bg-black/60" @click.prevent="showEditModal = false"></div>
        <div class="relative z-10 w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl dark:bg-gray-900 mx-4">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-800 dark:text-white" x-text="'تعديل: ' + currentEdit.company_name"></h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" @click.prevent="showEditModal = false">✕</button>
            </div>
            <form
                :action="currentEdit.update_url"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-4"
            >
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">اسم البنك / الصرافة <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" :value="currentEdit.company_name" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الحساب / الآيبان <span class="text-red-500">*</span></label>
                    <input type="text" name="account_number" :value="currentEdit.account_number" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-mono text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">اسم صاحب الحساب <span class="text-red-500">*</span></label>
                    <input type="text" name="account_holder" :value="currentEdit.account_holder" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" />
                </div>
                <div x-data="{}">
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">شعار البنك / الصرافة</label>
                    <template x-if="previewEdit">
                        <img :src="previewEdit" class="mb-2 h-14 w-14 rounded-lg border object-contain p-1 dark:border-gray-600" />
                    </template>
                    <input type="file" name="logo" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:me-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-600 dark:file:bg-blue-950 dark:file:text-blue-300"
                        @change="const f=$event.target.files[0]; if(f) previewEdit=URL.createObjectURL(f);" />
                    <template x-if="currentEdit.has_logo">
                        <label class="mt-2 flex cursor-pointer items-center gap-2 text-xs text-red-500">
                            <input type="checkbox" name="remove_logo" value="1"
                                @change="if($event.target.checked){ previewEdit=null; }" class="rounded" />
                            حذف الشعار الحالي
                        </label>
                    </template>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" :checked="currentEdit.is_active" class="rounded" />
                    <label for="edit_is_active" class="text-sm text-gray-700 dark:text-gray-300">مفعّل (يظهر للعملاء)</label>
                </div>
                <input type="hidden" name="sort_order" :value="currentEdit.sort_order" />
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click.prevent="showEditModal = false"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-600 dark:text-gray-300">إلغاء</button>
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

    </div>{{-- end x-data wrapper --}}
</x-admin::layouts>
