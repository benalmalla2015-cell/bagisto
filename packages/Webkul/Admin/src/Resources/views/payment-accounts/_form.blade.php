{{-- اسم البنك / الصرافة --}}
<div>
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        اسم البنك / شركة الصرافة <span class="text-red-500">*</span>
    </label>
    <input
        type="text"
        name="company_name"
        value="{{ old('company_name', $account->company_name ?? '') }}"
        placeholder="مثال: بنك الراجحي — شركة الكريمي للصرافة"
        required
        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
    />
</div>

{{-- رقم الحساب --}}
<div>
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        رقم الحساب / الآيبان <span class="text-red-500">*</span>
    </label>
    <input
        type="text"
        name="account_number"
        value="{{ old('account_number', $account->account_number ?? '') }}"
        placeholder="SA00 0000 0000 0000 0000 0000"
        required
        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-mono text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
    />
</div>

{{-- اسم صاحب الحساب --}}
<div>
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        اسم صاحب الحساب <span class="text-red-500">*</span>
    </label>
    <input
        type="text"
        name="account_holder"
        value="{{ old('account_holder', $account->account_holder ?? '') }}"
        placeholder="الاسم الكامل"
        required
        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white"
    />
</div>

{{-- شعار البنك --}}
<div x-data="{ hasLogo: {{ ($account && $account->logo_path) ? 'true' : 'false' }}, preview: '{{ ($account && $account->logo_url) ? $account->logo_url : '' }}' }">
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        شعار البنك / الصرافة
    </label>

    {{-- Preview --}}
    <div class="mb-2 flex items-center gap-3">
        <template x-if="preview">
            <img :src="preview" class="h-14 w-14 rounded-lg border border-gray-200 object-contain p-1 dark:border-gray-600" />
        </template>
        <template x-if="!preview">
            <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-gray-300 text-2xl dark:border-gray-600">🏦</div>
        </template>
    </div>

    <input
        type="file"
        name="logo"
        accept="image/png,image/jpeg,image/svg+xml,image/webp"
        class="block w-full text-sm text-gray-500 file:me-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-600 hover:file:bg-blue-100 dark:file:bg-blue-950 dark:file:text-blue-300"
        @change="
            const file = $event.target.files[0];
            if (file) { preview = URL.createObjectURL(file); hasLogo = true; }
        "
    />
    <p class="mt-1 text-xs text-gray-400">PNG / JPG / SVG — يُفضل 200×60 بكسل أو مربع</p>

    @if ($account && $account->logo_path)
        <label class="mt-2 flex cursor-pointer items-center gap-2 text-xs text-red-500">
            <input
                type="checkbox"
                name="remove_logo"
                value="1"
                @change="if ($event.target.checked) { preview = ''; hasLogo = false; }"
            />
            حذف الشعار الحالي
        </label>
    @endif
</div>

{{-- الترتيب والحالة --}}
<div class="flex items-center gap-6">
    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            {{ ($account === null || $account->is_active) ? 'checked' : '' }}
            class="rounded"
        />
        مفعّل (يظهر للعملاء عند الدفع)
    </label>

    <div class="ms-auto flex items-center gap-2">
        <label class="text-xs text-gray-500">الترتيب</label>
        <input
            type="number"
            name="sort_order"
            value="{{ old('sort_order', $account->sort_order ?? 0) }}"
            min="0"
            class="w-16 rounded-lg border border-gray-300 px-2 py-1.5 text-center text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
        />
    </div>
</div>
