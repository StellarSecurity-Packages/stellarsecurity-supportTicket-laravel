<?php

namespace StellarSecurity\SupportClient;

use Illuminate\Support\ServiceProvider;

class SupportClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stellar-support.php', 'stellar-support');

        $this->app->singleton(SupportClient::class, function () {
            return new SupportClient(config('stellar-support'));
        });

        $this->app->singleton(Support::class, function ($app) {
            return new Support($app->make(SupportClient::class));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/stellar-support.php' => config_path('stellar-support.php'),
        ], 'stellar-support-config');
    }
}
