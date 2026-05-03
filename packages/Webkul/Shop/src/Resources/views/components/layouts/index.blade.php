@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>

<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>
    <head>

        {!! view_render_event('bagisto.shop.layout.head.before') !!}

        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">

        <meta
            http-equiv="X-UA-Compatible"
            content="IE=edge"
        >
        <meta
            http-equiv="content-language"
            content="{{ app()->getLocale() }}"
        >

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="base-url"
            content="{{ url()->to('/') }}"
        >
        <meta
            name="currency"
            content="{{ core()->getCurrentCurrency()->toJson() }}"
        >
        <meta 
            name="generator" 
            content="Bagisto"
        >

        @stack('meta')

        <link
            rel="icon"
            sizes="16x16"
            href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
        />

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
            crossorigin
        />

        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        />

        <link
            rel="preload" as="style"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
        />

        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap"
        />

        @stack('styles')

        <style>
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
        </style>

        <style>
        /* ══════════════════ ENAB SHOP DARK THEME ══════════════════ */
        :root {
            --es-bg:         #0A0A0A;
            --es-surface:    #111111;
            --es-card:       rgba(255,255,255,0.05);
            --es-border:     #333333;
            --es-text:       #F5F5F5;
            --es-text-sec:   #A0A0A0;
            --es-input-bg:   #1E1E1E;
            --es-hover:      #2A2A2A;
        }

        /* ── Global ── */
        body.enab-shop-dark {
            background:#0A0A0A !important;
            color:#F5F5F5 !important;
        }
        body.enab-shop-dark #app               { background:#0A0A0A !important; }
        body.enab-shop-dark #main              { background:#0A0A0A !important; }
        body.enab-shop-dark .bg-white          { background-color:#111111 !important; }
        body.enab-shop-dark .bg-gray-100       { background-color:#1A1A1A !important; }
        body.enab-shop-dark .bg-gray-50        { background-color:#141414 !important; }
        body.enab-shop-dark .bg-zinc-100       { background-color:#1A1A1A !important; }
        body.enab-shop-dark .border-gray-200   { border-color:#333 !important; }
        body.enab-shop-dark .border-gray-100   { border-color:#2a2a2a !important; }
        body.enab-shop-dark .divide-y          { border-color:#2a2a2a !important; }
        body.enab-shop-dark .text-gray-600,
        body.enab-shop-dark .text-gray-500     { color:#A0A0A0 !important; }
        body.enab-shop-dark .text-gray-800,
        body.enab-shop-dark .text-gray-900     { color:#E0E0E0 !important; }
        body.enab-shop-dark .text-black        { color:#F5F5F5 !important; }

        /* ── Header ── */
        body.enab-shop-dark header,
        body.enab-shop-dark [class*="header"],
        body.enab-shop-dark nav {
            background:#0A0A0A !important;
            border-bottom:1px solid #333 !important;
        }
        body.enab-shop-dark header a,
        body.enab-shop-dark nav a          { color:#E0E0E0 !important; }
        body.enab-shop-dark header a:hover,
        body.enab-shop-dark nav a:hover    { color:#F5F5F5 !important; }
        body.enab-shop-dark [class*="cart"] svg,
        body.enab-shop-dark [class*="wish"] svg { stroke:#E0E0E0 !important; fill:none !important; }
        body.enab-shop-dark .icon-cancel,
        body.enab-shop-dark .icon-search   { color:#E0E0E0 !important; }

        /* ── Footer ── */
        body.enab-shop-dark footer,
        body.enab-shop-dark [class*="footer"] {
            background:#111111 !important;
            border-top:1px solid #333 !important;
        }
        body.enab-shop-dark footer a       { color:#A0A0A0 !important; }
        body.enab-shop-dark footer a:hover { color:#F5F5F5 !important; }

        /* ── Product Cards ── */
        body.enab-shop-dark [class*="product"][class*="card"],
        body.enab-shop-dark .product-card {
            background: rgba(255,255,255,0.05) !important;
            backdrop-filter: blur(4px) !important;
            border: 1px solid #333 !important;
            border-radius: 16px !important;
            overflow: hidden !important;
            transition: border-color .2s, transform .2s !important;
        }
        body.enab-shop-dark [class*="product"][class*="card"]:hover,
        body.enab-shop-dark .product-card:hover {
            border-color: #555 !important;
            transform: translateY(-2px) !important;
        }
        body.enab-shop-dark .product-card .price,
        body.enab-shop-dark [class*="price"]      { color:#F5F5F5 !important; font-weight:600 !important; }
        body.enab-shop-dark [class*="product-name"],
        body.enab-shop-dark .product-name         { color:#C0C0C0 !important; }

        /* ── Buttons ── */
        body.enab-shop-dark .primary-button,
        body.enab-shop-dark button.bg-navyBlue,
        body.enab-shop-dark button[class*="add-to-cart"],
        body.enab-shop-dark a.primary-button {
            background: transparent !important;
            border: 1px solid #555 !important;
            color: #F5F5F5 !important;
            border-radius: 8px !important;
            transition: background .2s, border-color .2s !important;
        }
        body.enab-shop-dark .primary-button:hover,
        body.enab-shop-dark button.bg-navyBlue:hover,
        body.enab-shop-dark a.primary-button:hover {
            background: #333 !important;
            border-color: #777 !important;
        }
        body.enab-shop-dark .secondary-button {
            background: transparent !important;
            border: 1px solid #555 !important;
            color: #E0E0E0 !important;
            border-radius: 8px !important;
        }
        body.enab-shop-dark .secondary-button:hover  { background: #2A2A2A !important; }

        /* ── Forms ── */
        body.enab-shop-dark input:not([type="checkbox"]):not([type="radio"]):not([type="range"]),
        body.enab-shop-dark select,
        body.enab-shop-dark textarea {
            background: #1E1E1E !important;
            color: #F5F5F5 !important;
            border-color: #555 !important;
            border-radius: 8px !important;
        }
        body.enab-shop-dark input:focus, body.enab-shop-dark select:focus, body.enab-shop-dark textarea:focus {
            border-color: #777 !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.06) !important;
        }
        body.enab-shop-dark input::placeholder { color: #666 !important; }
        body.enab-shop-dark label              { color: #C0C0C0 !important; }

        /* ── Cart Page ── */
        body.enab-shop-dark [class*="cart"][class*="item"],
        body.enab-shop-dark .cart-item {
            background: rgba(255,255,255,0.04) !important;
            border: 1px solid #333 !important;
            border-radius: 12px !important;
        }
        body.enab-shop-dark [class*="cart-total"],
        body.enab-shop-dark .order-summary {
            background: rgba(255,255,255,0.05) !important;
            backdrop-filter: blur(4px) !important;
            border: 1px solid #333 !important;
            border-radius: 16px !important;
        }

        /* ── Checkout ── */
        body.enab-shop-dark [class*="checkout"][class*="panel"],
        body.enab-shop-dark [class*="checkout-step"] {
            background: rgba(255,255,255,0.04) !important;
            border: 1px solid #333 !important;
            border-radius: 16px !important;
        }

        /* ── Product View Page ── */
        body.enab-shop-dark [class*="product-info"],
        body.enab-shop-dark [class*="product-details"] {
            background: #111111 !important;
        }
        body.enab-shop-dark [class*="product-view"] [class*="price"]  { color:#F5F5F5 !important; font-size:1.4rem !important; }
        body.enab-shop-dark [class*="product-view"] [class*="title"]  { color:#E0E0E0 !important; }
        body.enab-shop-dark [class*="product-view"] [class*="desc"]   { color:#A0A0A0 !important; }

        /* ── Account Pages ── */
        body.enab-shop-dark [class*="account"][class*="sidebar"],
        body.enab-shop-dark [class*="account-sidebar"] {
            background: #141414 !important;
            border: 1px solid #333 !important;
            border-radius: 12px !important;
        }
        body.enab-shop-dark [class*="account"][class*="sidebar"] a:hover {
            background: #2A2A2A !important;
            border-radius: 6px !important;
        }

        /* ── Tables ── */
        body.enab-shop-dark table thead th        { background:#1A1A1A !important; color:#A0A0A0 !important; border-color:#333 !important; }
        body.enab-shop-dark table tbody td        { background:#111111 !important; color:#E0E0E0 !important; border-color:#2a2a2a !important; }
        body.enab-shop-dark table tbody tr:hover td { background:#1A1A1A !important; }

        /* ── Modals ── */
        body.enab-shop-dark [class*="modal"][class*="container"],
        body.enab-shop-dark [class*="modal-body"] {
            background: #1A1A1A !important;
            border: 1px solid #333 !important;
        }

        /* ── Skip link (keep accessible) ── */
        body.enab-shop-dark .skip-to-main-content-link {
            background: #0A0A0A !important;
            color: #F5F5F5 !important;
        }
        /* ════════════════════════════════════════════════════════════ */
        </style>

        @if(core()->getConfigData('general.content.speculation_rules.enabled'))
            <script type="speculationrules">
                @json(core()->getSpeculationRules(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            </script>
        @endif

        {!! view_render_event('bagisto.shop.layout.head.after') !!}

    </head>

    <body class="enab-shop-dark">
        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        <a
            href="#main"
            class="skip-to-main-content-link"
        >
            Skip to main content
        </a>

        <!-- Built With Bagisto -->
        <div id="app">
            <!-- Flash Message Blade Component -->
            <x-shop::flash-group />

            <!-- Confirm Modal Blade Component -->
            <x-shop::modal.confirm />

            <!-- Page Header Blade Component -->
            @if ($hasHeader)
                <x-shop::layouts.header />
            @endif

            @if(
                core()->getConfigData('general.gdpr.settings.enabled')
                && core()->getConfigData('general.gdpr.cookie.enabled')
            )
                <x-shop::layouts.cookie />
            @endif

            {!! view_render_event('bagisto.shop.layout.content.before') !!}

            <!-- Page Content Blade Component -->
            <main id="main" class="bg-white">
                {{ $slot }}
            </main>

            {!! view_render_event('bagisto.shop.layout.content.after') !!}


            <!-- Page Services Blade Component -->
            @if ($hasFeature)
                <x-shop::layouts.services />
            @endif

            <!-- Page Footer Blade Component -->
            @if ($hasFooter)
                <x-shop::layouts.footer />
            @endif
        </div>

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        @stack('scripts')

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.before') !!}
        <script>
            /**
             * Load event, the purpose of using the event is to mount the application
             * after all of our `Vue` components which is present in blade file have
             * been registered in the app. No matter what `app.mount()` should be
             * called in the last.
             */
            window.addEventListener("load", function (event) {
                app.mount("#app");
            });
        </script>

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.after') !!}

        <script type="text/javascript">
            {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
        </script>
    </body>
</html>
