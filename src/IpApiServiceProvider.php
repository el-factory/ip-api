<?php

namespace ElFactory\IpApi;

use ElFactory\IpApi\Console\TestConnection;
use Illuminate\Support\ServiceProvider;

class IpApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/ip-api.php' => config_path('ip-api.php'),
        ], 'ip-api-config');
    }

    public function register(): void
    {
        $this->commands([
            TestConnection::class,
        ]);
    }
}
