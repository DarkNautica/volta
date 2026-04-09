<?php

namespace App\Providers;

use App\Services\VoltaService;
use Illuminate\Support\ServiceProvider;

class VoltaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(VoltaService::class, function () {
            return new VoltaService();
        });
    }
}
