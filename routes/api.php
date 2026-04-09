<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ChartDataController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\VoltaApiController;
use App\Http\Middleware\CheckStripeWebhook;
use App\Http\Middleware\ResolveAppKey;
use App\Http\Middleware\VoltaRateLimiter;
use Illuminate\Support\Facades\Route;

// App Key routes (SDK usage)
Route::prefix('v1')->middleware([ResolveAppKey::class, VoltaRateLimiter::class])->group(function () {
    Route::post('/charge', [VoltaApiController::class, 'charge']);
    Route::get('/balance/{externalUserId}', [VoltaApiController::class, 'balance']);
    Route::get('/access/{externalUserId}', [VoltaApiController::class, 'access']);
    Route::post('/topup', [VoltaApiController::class, 'topUp']);
    Route::get('/usage/{externalUserId}', [VoltaApiController::class, 'usage']);
    Route::get('/portal-url', [VoltaApiController::class, 'portalUrl']);
});

// Authenticated dashboard API routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/apps', [AppController::class, 'index']);
    Route::post('/apps', [AppController::class, 'store']);
    Route::get('/apps/{app}', [AppController::class, 'show']);
    Route::delete('/apps/{app}', [AppController::class, 'destroy']);
    Route::get('/apps/{app}/users', [AppController::class, 'users']);
    Route::get('/apps/{app}/stats', [AppController::class, 'stats']);
    Route::post('/apps/{app}/models', [AppController::class, 'storeModel']);
    Route::delete('/apps/{app}/models/{appModel}', [AppController::class, 'destroyModel']);
    Route::get('/apps/{app}/chart-data', ChartDataController::class);
});

// Stripe webhook
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->middleware(CheckStripeWebhook::class);
