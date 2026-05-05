<x-admin::layouts>
    <x-slot:title>الحسابات البنكية والصرافة</x-slot>

    {{-- ══ Page Header ══ --}}
    <div class="mb-5 flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">الحسابات البنكية / الصرافة</p>
        <button
            type="button"
            onclick="paModal.openAdd()"
            class="flex cursor-pointer items-center gap-1.5 rounded-md border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
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
                                    <button
                                        type="button"
                                        id="toggle-btn-{{ $account->id }}"
                                        onclick="paModal.toggle({{ $account->id }}, this)"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $account->is_active ? 'bg-green-500' : 'bg-gray-300' }}"
                                    >
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $account->is_active ? 'translate-x-1 rtl:-translate-x-5' : 'translate-x-6 rtl:-translate-x-1' }}"></span>
                                    </button>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button
                                            type="button"
                                            class="text-blue-500 hover:underline"
                                            onclick="paModal.openEdit({{ $account->id }})"
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

    {{-- ══ ADD MODAL ══ --}}
    <div id="pa-add-modal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.6)" onclick="if(event.target===this)paModal.closeAdd()">
        <div style="position:relative;width:100%;max-width:520px;margin:16px;background:#fff;border-radius:12px;padding:24px;box-shadow:0 25px 50px rgba(0,0,0,0.3)" class="dark:bg-gray-900">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                <h3 style="font-weight:700;font-size:1rem" class="text-gray-800 dark:text-white">إضافة حساب جديد</h3>
                <button type="button" onclick="paModal.closeAdd()" style="font-size:1.2rem;color:#9ca3af;cursor:pointer;background:none;border:none">✕</button>
            </div>
            <form action="{{ route('admin.payment-accounts.store') }}" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:12px">
                @csrf
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:500;margin-bottom:4px" class="text-gray-700 dark:text-gray-300">اسم البنك / الصرافة <span style="color:red">*</span></label>
                    <input type="text" name="company_name" placeholder="مثال: بنك الراجحي" required
                        style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:.875rem;box-sizing:border-box" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:500;margin-bottom:4px" class="text-gray-700 dark:text-gray-300">رقم الحساب / الآيبان <span style="color:red">*</span></label>
                    <input type="text" name="account_number" placeholder="SA00 0000 0000 0000" required
                        style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:.875rem;font-family:monospace;box-sizing:border-box" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:500;margin-bottom:4px" class="text-gray-700 dark:text-gray-300">اسم صاحب الحساب <span style="color:red">*</span></label>
                    <input type="text" name="account_holder" placeholder="الاسم الكامل" required
                        style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:.875rem;box-sizing:border-box" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:500;margin-bottom:4px" class="text-gray-700 dark:text-gray-300">شعار البنك / الصرافة</label>
                    <img id="add-logo-preview" src="" alt="" style="display:none;height:56px;width:56px;border-radius:8px;border:1px solid #e5e7eb;object-fit:contain;padding:4px;margin-bottom:8px" />
                    <input type="file" name="logo" accept="image/*"
                        onchange="const f=this.files[0];if(f){const el=document.getElementById('add-logo-preview');el.src=URL.createObjectURL(f);el.style.display='block'}"
                        style="font-size:.8rem" />
                    <p style="font-size:.7rem;color:#9ca3af;margin-top:4px">PNG / JPG / SVG — حجم أقصى 2MB</p>
                </div>
                <label style="display:flex;align-items:center;gap:8px;font-size:.875rem;cursor:pointer" class="text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="is_active" value="1" checked style="border-radius:4px" />
                    مفعّل (يظهر للعملاء)
                </label>
                <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:8px">
                    <button type="button" onclick="paModal.closeAdd()"
                        style="border:1px solid #d1d5db;border-radius:8px;padding:8px 16px;font-size:.875rem;cursor:pointer;background:#fff" class="dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800">إلغاء</button>
                    <button type="submit"
                        style="background:#2563eb;color:#fff;border-radius:8px;padding:8px 20px;font-size:.875rem;font-weight:600;cursor:pointer;border:none">إضافة الحساب</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ EDIT MODAL ══ --}}
    <div id="pa-edit-modal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.6)" onclick="if(event.target===this)paModal.closeEdit()">
        <div style="position:relative;width:100%;max-width:520px;margin:16px;background:#fff;border-radius:12px;padding:24px;box-shadow:0 25px 50px rgba(0,0,0,0.3)" class="dark:bg-gray-900">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                <h3 id="edit-modal-title" style="font-weight:700;font-size:1rem" class="text-gray-800 dark:text-white">تعديل الحساب</h3>
                <button type="button" onclick="paModal.closeEdit()" style="font-size:1.2rem;color:#9ca3af;cursor:pointer;background:none;border:none">✕</button>
            </div>
            <form id="pa-edit-form" action="" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:12px">
                @csrf
                @method('PUT')
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:500;margin-bottom:4px" class="text-gray-700 dark:text-gray-300">اسم البنك / الصرافة <span style="color:red">*</span></label>
                    <input type="text" id="edit-company-name" name="company_name" required
                        style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:.875rem;box-sizing:border-box" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:500;margin-bottom:4px" class="text-gray-700 dark:text-gray-300">رقم الحساب / الآيبان <span style="color:red">*</span></label>
                    <input type="text" id="edit-account-number" name="account_number" required
                        style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:.875rem;font-family:monospace;box-sizing:border-box" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:500;margin-bottom:4px" class="text-gray-700 dark:text-gray-300">اسم صاحب الحساب <span style="color:red">*</span></label>
                    <input type="text" id="edit-account-holder" name="account_holder" required
                        style="width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:.875rem;box-sizing:border-box" class="dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>
                <div>
                    <label style="display:block;font-size:.8rem;font-weight:500;margin-bottom:4px" class="text-gray-700 dark:text-gray-300">شعار البنك / الصرافة</label>
                    <img id="edit-logo-preview" src="" alt="" style="display:none;height:56px;width:56px;border-radius:8px;border:1px solid #e5e7eb;object-fit:contain;padding:4px;margin-bottom:8px" />
                    <input type="file" name="logo" accept="image/*"
                        onchange="const f=this.files[0];if(f){const el=document.getElementById('edit-logo-preview');el.src=URL.createObjectURL(f);el.style.display='block'}"
                        style="font-size:.8rem" />
                    <label id="edit-remove-logo-wrap" style="display:none;align-items:center;gap:6px;font-size:.75rem;color:#ef4444;cursor:pointer;margin-top:6px">
                        <input type="checkbox" name="remove_logo" value="1"
                            onchange="if(this.checked){document.getElementById('edit-logo-preview').style.display='none'}" style="border-radius:4px" />
                        حذف الشعار الحالي
                    </label>
                </div>
                <label style="display:flex;align-items:center;gap:8px;font-size:.875rem;cursor:pointer" class="text-gray-700 dark:text-gray-300">
                    <input type="checkbox" id="edit-is-active" name="is_active" value="1" style="border-radius:4px" />
                    مفعّل (يظهر للعملاء)
                </label>
                <input type="hidden" id="edit-sort-order" name="sort_order" value="0" />
                <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:8px">
                    <button type="button" onclick="paModal.closeEdit()"
                        style="border:1px solid #d1d5db;border-radius:8px;padding:8px 16px;font-size:.875rem;cursor:pointer;background:#fff" class="dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800">إلغاء</button>
                    <button type="submit"
                        style="background:#2563eb;color:#fff;border-radius:8px;padding:8px 20px;font-size:.875rem;font-weight:600;cursor:pointer;border:none">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ Accounts Data + JS ══ --}}
    <script>
        var paAccounts = {!! json_encode($accountsData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) !!};

        var paModal = {
            openAdd: function() {
                var m = document.getElementById('pa-add-modal');
                m.style.display = 'flex';
            },
            closeAdd: function() {
                document.getElementById('pa-add-modal').style.display = 'none';
            },
            openEdit: function(id) {
                var acc = paAccounts.find(function(a){ return a.id === id; });
                if (!acc) return;
                document.getElementById('edit-modal-title').textContent = 'تعديل: ' + acc.company_name;
                document.getElementById('pa-edit-form').action = acc.update_url;
                document.getElementById('edit-company-name').value = acc.company_name;
                document.getElementById('edit-account-number').value = acc.account_number;
                document.getElementById('edit-account-holder').value = acc.account_holder;
                document.getElementById('edit-is-active').checked = acc.is_active;
                document.getElementById('edit-sort-order').value = acc.sort_order;
                var prev = document.getElementById('edit-logo-preview');
                var rmWrap = document.getElementById('edit-remove-logo-wrap');
                if (acc.logo_url) {
                    prev.src = acc.logo_url;
                    prev.style.display = 'block';
                    rmWrap.style.display = 'flex';
                } else {
                    prev.style.display = 'none';
                    rmWrap.style.display = 'none';
                }
                document.getElementById('pa-edit-modal').style.display = 'flex';
            },
            closeEdit: function() {
                document.getElementById('pa-edit-modal').style.display = 'none';
            },
            toggle: function(id, btn) {
                var csrf = document.querySelector('meta[name=csrf-token]');
                var token = csrf ? csrf.getAttribute('content') : '';
                var acc = paAccounts.find(function(a){ return a.id === id; });
                if (!acc) return;
                fetch(acc.toggle_url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                }).then(function(r){ return r.json(); }).then(function(data) {
                    acc.is_active = data.is_active;
                    btn.className = btn.className.replace(/bg-\w+-\d+/g, '');
                    btn.classList.add(data.is_active ? 'bg-green-500' : 'bg-gray-300');
                    var span = btn.querySelector('span');
                    if (span) {
                        span.classList.toggle('translate-x-1', data.is_active);
                        span.classList.toggle('rtl:-translate-x-5', data.is_active);
                        span.classList.toggle('translate-x-6', !data.is_active);
                        span.classList.toggle('rtl:-translate-x-1', !data.is_active);
                    }
                });
            }
        };
    </script>
</x-admin::layouts>
