<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

// Embeddable billing portal (signed URLs, no auth)
Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');
Route::post('/portal/checkout', [PortalController::class, 'checkout'])->name('portal.checkout');

// Docs
Route::get('/docs/embed', fn () => view('docs.embed'))->name('docs.embed');
Route::get('/docs', fn () => redirect('/docs/introduction'));
Route::get('/docs/{section}/{page}', [DocsController::class, 'show'])->name('docs.section.show');
Route::get('/docs/{page}', [DocsController::class, 'show'])->name('docs.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard
Route::middleware(['auth', 'plan.active'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/apps', [DashboardController::class, 'apps'])->name('dashboard.apps');
    Route::get('/apps/create', [DashboardController::class, 'createApp'])->name('dashboard.apps.create');
    Route::post('/apps', [DashboardController::class, 'storeApp'])->name('dashboard.apps.store')->middleware('plan.app-limit');
    Route::get('/apps/{app}', [DashboardController::class, 'showApp'])->name('dashboard.apps.show');
    Route::put('/apps/{app}', [DashboardController::class, 'updateApp'])->name('dashboard.apps.update');
    Route::post('/apps/{app}/models', [DashboardController::class, 'storeAppModel'])->name('dashboard.apps.models.store');
    Route::delete('/apps/{app}/models/{appModel}', [DashboardController::class, 'destroyAppModel'])->name('dashboard.apps.models.destroy');
    Route::post('/apps/{app}/regenerate-key', [DashboardController::class, 'regenerateKey'])->name('dashboard.apps.regenerate-key');
    Route::delete('/apps/{app}/users/{appUser}', [DashboardController::class, 'destroyAppUser'])->name('dashboard.apps.users.destroy');
});

// Billing
Route::middleware('auth')->prefix('billing')->group(function () {
    Route::get('/', [BillingController::class, 'index'])->name('billing');
    Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::post('/portal', [BillingController::class, 'portal'])->name('billing.portal');
});

// Stripe webhook (no auth, no CSRF)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');
