<?php

namespace App\Providers;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $allowedIPs = array_map('trim', explode(',', config('app.debug_allowed_ips')));

        $allowedIPs = array_filter($allowedIPs);

        if (empty($allowedIPs)) {
            return;
        }

        if (in_array(Request::ip(), $allowedIPs)) {
            Debugbar::enable();
        } else {
            Debugbar::disable();
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ParallelTesting::setUpTestDatabase(function (string $database, int $token) {
            Artisan::call('db:seed');
        });

        /*
         |-------------------------------------------------------------------
         | 3inab AI Bridge — inject floating assistant into Bagisto /admin/*
         |-------------------------------------------------------------------
         | Hooks into Bagisto's `bagisto.admin.layout.vue-app-mount.before`
         | view event and renders a lightweight vanilla-JS FAB widget that
         | talks to panel_app's /panel/api/ai/admin/ask endpoint.
         |
         | Controlled by env: AI_BRIDGE_ENABLED=true
         */
        if ((bool) env('AI_BRIDGE_ENABLED', false)) {
            View::addNamespace('ai-bridge', resource_path('views/ai-bridge'));

            Event::listen('bagisto.admin.layout.vue-app-mount.before', function ($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate('ai-bridge::fab');
            });
        }
    }
}
