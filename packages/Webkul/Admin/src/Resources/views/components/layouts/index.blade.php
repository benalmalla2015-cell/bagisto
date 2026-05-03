<!DOCTYPE html>

<html
    class="dark"
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>

<head>
    {!! view_render_event('bagisto.admin.layout.head.before') !!}

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
        content="{{ core()->getBaseCurrency()->toJson() }}"
    >
    <meta 
        name="generator" 
        content="Bagisto"
    >

    @stack('meta')

    @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap"
        rel="stylesheet"
    />

    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <link
        rel="preload"
        as="image"
        href="{{ url('cache/logo/bagisto.png') }}"
    >

    @if ($favicon = core()->getConfigData('general.design.admin_logo.favicon'))
        <link
            type="image/x-icon"
            href="{{ Storage::url($favicon) }}"
            rel="shortcut icon"
            sizes="16x16"
        >
    @else
        <link
            type="image/x-icon"
            href="{{ bagisto_asset('images/favicon.ico') }}"
            rel="shortcut icon"
            sizes="16x16"
        />
    @endif

    @stack('styles')

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    <style>
    /* ═══════════════════ ENAB ADMIN DARK THEME ═══════════════════ */
    :root {
        --enab-bg:        #0A0A0A;
        --enab-surface:   #111111;
        --enab-sidebar:   #141414;
        --enab-card:      rgba(255,255,255,0.05);
        --enab-border:    #333333;
        --enab-text:      #F5F5F5;
        --enab-text-sec:  #A0A0A0;
        --enab-input-bg:  #1E1E1E;
        --enab-hover:     #2A2A2A;
    }

    /* ── Base ── */
    html.dark body                          { background:#0A0A0A !important; color:#F5F5F5 !important; }
    html.dark #app                          { background:#0A0A0A !important; }
    html.dark .enab-main                    { background:#111111 !important; }

    /* ── Tailwind gray overrides ── */
    html.dark .dark\:bg-gray-950           { background-color:#0A0A0A !important; }
    html.dark .dark\:bg-gray-900           { background-color:#141414 !important; }
    html.dark .dark\:bg-gray-800           { background-color:#1E1E1E !important; }
    html.dark .bg-white                    { background-color:#141414 !important; }
    html.dark .dark\:border-gray-800       { border-color:#333 !important; }
    html.dark .dark\:border-gray-700       { border-color:#3a3a3a !important; }
    html.dark .dark\:text-white            { color:#F5F5F5 !important; }
    html.dark .dark\:text-gray-300         { color:#C0C0C0 !important; }
    html.dark .dark\:text-gray-200         { color:#D0D0D0 !important; }
    html.dark .dark\:text-gray-400         { color:#909090 !important; }
    html.dark .text-gray-600               { color:#A0A0A0 !important; }
    html.dark .text-gray-800               { color:#E0E0E0 !important; }
    html.dark .border-gray-200             { border-color:#333 !important; }
    html.dark .border-t                    { border-color:#2a2a2a !important; }

    /* ── Sidebar ── */
    html.dark .enab-sidebar {
        background:#1A1A1A !important;
        border-right:1px solid #333 !important;
        box-shadow:none !important;
    }
    html.dark .enab-sidebar a:hover {
        background:#2A2A2A !important;
        border-radius:8px !important;
    }
    html.dark .enab-sidebar .bg-blue-600 {
        background:#333 !important;
        border:1px solid #555 !important;
    }
    html.dark .enab-sidebar p { color:#E0E0E0 !important; }

    /* ── Cards ── */
    html.dark .box-shadow {
        background: rgba(255,255,255,0.05) !important;
        backdrop-filter: blur(8px) !important;
        border: 1px solid #333 !important;
        border-radius: 16px !important;
        box-shadow: none !important;
    }
    html.dark .rounded-xl.bg-white,
    html.dark .rounded-lg.bg-white,
    html.dark .rounded-md.bg-white,
    html.dark .rounded.bg-white {
        background: rgba(255,255,255,0.05) !important;
        backdrop-filter: blur(4px) !important;
        border: 1px solid #333 !important;
    }

    /* ── Tables ── */
    html.dark table                         { border-collapse:collapse !important; }
    html.dark table thead th                { background:#1A1A1A !important; color:#A0A0A0 !important; border-color:#333 !important; font-size:.75rem !important; letter-spacing:.05em !important; }
    html.dark table tbody td                { background:#111111 !important; color:#E0E0E0 !important; border-color:#2a2a2a !important; }
    html.dark table tbody tr:hover td       { background:#1A1A1A !important; }
    html.dark table tbody tr:nth-child(even) td { background:rgba(255,255,255,0.02) !important; }
    html.dark .dark\:border-b.dark\:border-gray-800 { border-color:#2a2a2a !important; }

    /* ── Forms ── */
    html.dark input:not([type="checkbox"]):not([type="radio"]):not([type="range"]),
    html.dark select,
    html.dark textarea {
        background-color:#1E1E1E !important;
        color:#F5F5F5 !important;
        border-color:#555 !important;
        border-radius:8px !important;
    }
    html.dark input:focus, html.dark select:focus, html.dark textarea:focus {
        border-color:#777 !important;
        outline:none !important;
        box-shadow:0 0 0 2px rgba(255,255,255,0.06) !important;
    }
    html.dark input::placeholder, html.dark textarea::placeholder { color:#666 !important; }
    html.dark label { color:#C0C0C0 !important; }

    /* ── Buttons ── */
    html.dark .primary-button,
    html.dark button[type="submit"],
    html.dark a.primary-button {
        background:transparent !important;
        border:1px solid #555 !important;
        color:#F5F5F5 !important;
        border-radius:8px !important;
        transition:background .2s,border-color .2s !important;
    }
    html.dark .primary-button:hover,
    html.dark button[type="submit"]:hover,
    html.dark a.primary-button:hover {
        background:#333 !important;
        border-color:#777 !important;
    }
    html.dark .transparent-button,
    html.dark a.transparent-button {
        background:transparent !important;
        border:1px solid #555 !important;
        color:#E0E0E0 !important;
        border-radius:8px !important;
    }
    html.dark .transparent-button:hover { background:#2A2A2A !important; }

    /* ── Header ── */
    html.dark .dark\:bg-gray-900.sticky,
    html.dark header {
        background:#141414 !important;
        border-bottom:1px solid #333 !important;
        box-shadow:none !important;
    }

    /* ── Powered-by footer strip ── */
    html.dark .border-t.bg-white.py-2 {
        background:#0A0A0A !important;
        border-color:#2a2a2a !important;
        color:#555 !important;
    }

    /* ── Dropdown menus ── */
    html.dark [class*="dropdown"], html.dark .dark\:bg-gray-900[role="menu"] {
        background:#1A1A1A !important;
        border:1px solid #333 !important;
    }

    /* ── Badges / chips ── */
    html.dark .badge, html.dark [class*="rounded-full"][class*="bg-"] {
        filter: brightness(0.55) !important;
        border:1px solid #444 !important;
    }
    /* ═══════════════════════════════════════════════════════════════ */
    </style>

    {!! view_render_event('bagisto.admin.layout.head.after') !!}
</head>

<body class="h-full enab-admin dark:bg-gray-950">
    {!! view_render_event('bagisto.admin.layout.body.before') !!}

    <!-- Built With Bagisto -->
    <div
        id="app"
        class="h-full"
    >
        <!-- Flash Message Blade Component -->
        <x-admin::flash-group />

        <!-- Confirm Modal Blade Component -->
        <x-admin::modal.confirm />

        {!! view_render_event('bagisto.admin.layout.content.before') !!}

        <!-- Page Header Blade Component -->
        <x-admin::layouts.header />

        <div
            class="group/container {{ request()->cookie('sidebar_collapsed') ?? 0 ? 'sidebar-collapsed' : 'sidebar-not-collapsed' }} flex flex-col lg:flex-row gap-0 lg:gap-4"
            ref="appLayout"
        >
            <!-- Page Sidebar Blade Component -->
            <div class="lg:fixed lg:top-[62px] lg:left-0 rtl:lg:right-0 rtl:lg:left-auto lg:z-10 w-full lg:w-auto">
                <x-admin::layouts.sidebar />
            </div>

            <div class="enab-main flex min-h-[calc(100vh-62px)] max-w-full flex-1 flex-col bg-white transition-all duration-300 dark:bg-gray-950 pt-3 px-2 sm:px-4 lg:pt-3 lg:px-4 lg:ltr:pl-[286px] lg:group-[.sidebar-collapsed]/container:ltr:pl-[85px] lg:rtl:pr-[286px] lg:group-[.sidebar-collapsed]/container:rtl:pr-[85px]">
                <!-- Added dynamic tabs for third level menus  -->
                <div class="pb-4 lg:pb-6">
                    <!-- Todo @suraj-webkul need to optimize below statement. -->
                    @if (! request()->routeIs('admin.configuration.index'))
                        <div class="overflow-x-auto">
                            <x-admin::layouts.tabs />
                        </div>
                    @endif

                    <!-- Page Content Blade Component -->
                    <div class="w-full overflow-x-hidden">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Powered By -->
                <div class="mt-auto">
                    <div class="border-t bg-white py-2 text-center text-xs sm:text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                        <span style="font-family:'Tajawal',sans-serif;font-weight:700;color:#888">عنب</span>
                        <span style="color:#555;font-size:.75rem"> 3INAB &copy; {{ date('Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.layout.content.after') !!}
    </div>

    {!! view_render_event('bagisto.admin.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('bagisto.admin.layout.vue-app-mount.before') !!}

    <script>
        /**
         * Load event, the purpose of using the event is to mount the application
         * after all of our `Vue` components which is present in blade file have
         * been registered in the app. No matter what `app.mount()` should be
         * called in the last.
         */
        window.addEventListener("load", function(event) {
            app.mount("#app");
        });
    </script>

    {!! view_render_event('bagisto.admin.layout.vue-app-mount.after') !!}
</body>

</html>
