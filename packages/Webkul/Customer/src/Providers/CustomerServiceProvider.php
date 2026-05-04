<?php

namespace Webkul\Customer\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webkul\Customer\Console\Commands\CheckSubscriptionExpiry;
use Webkul\Customer\Facades\Captcha;
use Webkul\Customer\Services\SubscriptionService;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services.
     *
     * @param  Router  $router
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'customer');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'customer');

        $this->app['validator']->extend('captcha', function ($attribute, $value, $parameters) {
            return Captcha::getFacadeRoot()->validateResponse($value);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([CheckSubscriptionExpiry::class]);

            $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
                $schedule->command('subscriptions:check-expiry')->daily();
            });
        }
    }

    /**
     * Register application services.
     */
    public function register(): void
    {
        $this->app->singleton(SubscriptionService::class);
    }
}
