<x-admin::layouts>
    <x-slot:title>تعديل باقة الاشتراك</x-slot>

    <div class="mb-4 text-xl font-bold text-gray-800 dark:text-white">تعديل: {{ $plan->name }}</div>

    <x-admin::form :action="route('admin.subscription.plans.update', $plan)" method="PUT">
        <div class="grid grid-cols-1 gap-4 rounded-lg border p-6 dark:border-gray-800 md:grid-cols-2">
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">اسم الباقة</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="name" :value="$plan->name" rules="required" />
                <x-admin::form.control-group.error control-name="name" />
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">السعر (ر.س)</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="number" name="price" :value="$plan->price" step="0.01" rules="required" />
                <x-admin::form.control-group.error control-name="price" />
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">المدة (بالأيام)</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="number" name="duration_days" :value="$plan->duration_days" rules="required" />
                <x-admin::form.control-group.error control-name="duration_days" />
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>مفعّلة</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="switch" name="is_active" :value="$plan->is_active ? '1' : '0'" />
            </x-admin::form.control-group>

            <x-admin::form.control-group class="md:col-span-2">
                <x-admin::form.control-group.label>الوصف</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="textarea" name="description" :value="$plan->description" />
            </x-admin::form.control-group>
        </div>

        <div class="mt-4 flex gap-3">
            <button type="submit" class="primary-button">تحديث</button>
            <a href="{{ route('admin.subscription.plans.index') }}" class="secondary-button">إلغاء</a>
        </div>
    </x-admin::form>
</x-admin::layouts>
