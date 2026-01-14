<?php
// English comments only.

namespace StellarSecurity\SupportClient;

use Illuminate\Support\ServiceProvider;

class SupportClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/support-client.php', 'support-client');

        $this->app->singleton(SupportClient::class, function () {
            return new SupportClient(config('support-client'));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/Config/support-client.php' => config_path('support-client.php'),
        ], 'stellarsecurity-support-client-config');
    }
}
