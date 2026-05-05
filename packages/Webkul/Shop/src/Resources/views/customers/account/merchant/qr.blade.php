<x-shop::layouts.account>
    <x-slot:title>
        @lang('shop::app.customers.account.merchant.qr.title')
    </x-slot>

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-md:mb-5">
            <h2 class="text-2xl font-medium max-md:text-xl">
                @lang('shop::app.customers.account.merchant.qr.title')
            </h2>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl border border-zinc-200 p-6 text-center">
            <p class="mb-4 text-sm text-zinc-600">
                @lang('shop::app.customers.account.merchant.qr.description')
            </p>

            <div class="mb-6 flex justify-center">
                <img
                    src="{{ $qrApiUrl }}"
                    alt="QR Code"
                    class="h-56 w-56 rounded-lg border border-zinc-200 p-2"
                />
            </div>

            <div class="mb-6 rounded-lg bg-zinc-50 px-4 py-3">
                <p class="mb-1 text-xs font-medium text-zinc-500">
                    @lang('shop::app.customers.account.merchant.qr.store-link')
                </p>
                <a
                    href="{{ $storeUrl }}"
                    target="_blank"
                    class="break-all text-sm font-medium text-navyBlue hover:underline"
                >
                    {{ $storeUrl }}
                </a>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-4">
                <a
                    href="{{ $qrApiUrl }}&format=png"
                    download="qr-{{ $customer->merchant_slug }}.png"
                    class="rounded-lg border border-navyBlue px-6 py-2.5 text-sm font-medium text-navyBlue hover:bg-navyBlue hover:text-white"
                >
                    @lang('shop::app.customers.account.merchant.qr.download')
                </a>

                <form
                    action="{{ route('shop.customers.account.merchant.qr.regenerate') }}"
                    method="POST"
                    onsubmit="return confirm('{{ __('shop::app.customers.account.merchant.qr.confirm-regenerate') }}')"
                >
                    @csrf
                    <button
                        type="submit"
                        class="rounded-lg border border-zinc-300 px-6 py-2.5 text-sm font-medium text-zinc-600 hover:bg-zinc-50"
                    >
                        @lang('shop::app.customers.account.merchant.qr.regenerate')
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-shop::layouts.account>
