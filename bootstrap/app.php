<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'volta.app-key' => \App\Http\Middleware\ResolveAppKey::class,
            'volta.rate-limit' => \App\Http\Middleware\VoltaRateLimiter::class,
            'plan.active' => \App\Http\Middleware\CheckPlan::class,
            'plan.app-limit' => \App\Http\Middleware\CheckAppLimit::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
