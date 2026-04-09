<?php

use App\Models\App;
use App\Models\AppUser;
use App\Services\VoltaService;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    $this->app_instance = App::factory()->create([
        'credit_packages' => [[100, 499], [500, 1999]],
    ]);
    $this->appUser = AppUser::factory()->create([
        'app_id' => $this->app_instance->id,
        'credit_balance' => 200,
    ]);
});

it('renders portal with valid signed URL', function () {
    $service = new VoltaService($this->app_instance);
    $url = $service->portalUrl($this->appUser->external_user_id);

    // Extract path and query from the signed URL
    $parsed = parse_url($url);
    $path = $parsed['path'] . '?' . $parsed['query'];

    $response = $this->get($path);

    $response->assertOk();
    $response->assertSee((string) $this->appUser->credit_balance);
});

it('returns 403 for expired signed URL', function () {
    $url = URL::signedRoute('portal.index', [
        'app_id' => $this->app_instance->id,
        'external_user_id' => $this->appUser->external_user_id,
        'theme' => 'dark',
    ], now()->subHour());

    $parsed = parse_url($url);
    $path = $parsed['path'] . '?' . $parsed['query'];

    $response = $this->get($path);
    $response->assertStatus(403);
});

it('returns 403 for tampered signed URL', function () {
    $service = new VoltaService($this->app_instance);
    $url = $service->portalUrl($this->appUser->external_user_id);

    // Tamper with the URL by changing the app_id
    $tampered = str_replace(
        'app_id=' . $this->app_instance->id,
        'app_id=99999',
        $url,
    );

    $parsed = parse_url($tampered);
    $path = $parsed['path'] . '?' . $parsed['query'];

    $response = $this->get($path);
    $response->assertStatus(403);
});
