<x-shop::layouts.account>
    <x-slot:title>
        @lang('shop::app.customers.account.merchant.social-links.title')
    </x-slot>

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-md:mb-5">
            <h2 class="text-2xl font-medium max-md:text-xl">
                @lang('shop::app.customers.account.merchant.social-links.title')
            </h2>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl border border-zinc-200 p-6">
            <form action="{{ route('shop.customers.account.merchant.social-links.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    {{-- WhatsApp --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">
                            @lang('shop::app.customers.account.merchant.social-links.whatsapp')
                        </label>
                        <input
                            type="url"
                            name="whatsapp"
                            value="{{ old('whatsapp', $socialLinks?->whatsapp) }}"
                            placeholder="https://wa.me/967XXXXXXXXX"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-none"
                        />
                        @error('whatsapp')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Facebook --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">
                            @lang('shop::app.customers.account.merchant.social-links.facebook')
                        </label>
                        <input
                            type="url"
                            name="facebook"
                            value="{{ old('facebook', $socialLinks?->facebook) }}"
                            placeholder="https://facebook.com/yourpage"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-none"
                        />
                        @error('facebook')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instagram --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">
                            @lang('shop::app.customers.account.merchant.social-links.instagram')
                        </label>
                        <input
                            type="url"
                            name="instagram"
                            value="{{ old('instagram', $socialLinks?->instagram) }}"
                            placeholder="https://instagram.com/yourprofile"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-none"
                        />
                        @error('instagram')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TikTok --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">
                            @lang('shop::app.customers.account.merchant.social-links.tiktok')
                        </label>
                        <input
                            type="url"
                            name="tiktok"
                            value="{{ old('tiktok', $socialLinks?->tiktok) }}"
                            placeholder="https://tiktok.com/@yourprofile"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-none"
                        />
                        @error('tiktok')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Twitter / X --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">
                            @lang('shop::app.customers.account.merchant.social-links.twitter')
                        </label>
                        <input
                            type="url"
                            name="twitter"
                            value="{{ old('twitter', $socialLinks?->twitter) }}"
                            placeholder="https://x.com/yourprofile"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-navyBlue focus:outline-none"
                        />
                        @error('twitter')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        type="submit"
                        class="rounded-lg bg-navyBlue px-8 py-2.5 text-sm font-medium text-white hover:opacity-90"
                    >
                        @lang('shop::app.customers.account.merchant.social-links.save')
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-shop::layouts.account>
