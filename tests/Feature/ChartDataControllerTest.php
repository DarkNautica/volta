<?php

use App\Models\App;
use App\Models\AppUser;
use App\Models\UsageLog;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->app_instance = App::factory()->create(['user_id' => $this->user->id]);
    $this->appUser = AppUser::factory()->create(['app_id' => $this->app_instance->id]);
});

it('returns correct date range for 7d', function () {
    // Create usage logs for the last 7 days
    for ($i = 6; $i >= 0; $i--) {
        UsageLog::factory()->create([
            'app_id' => $this->app_instance->id,
            'app_user_id' => $this->appUser->id,
            'credits_charged' => 2,
            'created_at' => now()->subDays($i),
        ]);
    }

    $response = $this->actingAs($this->user)
        ->getJson("/api/v1/apps/{$this->app_instance->id}/chart-data?range=7d");

    $response->assertOk()
        ->assertJsonStructure([
            'labels',
            'datasets' => ['api_calls', 'credits_used', 'new_users'],
            'totals' => ['api_calls', 'credits_used', 'new_users'],
        ]);

    expect(count($response->json('labels')))->toBe(7);
    expect($response->json('totals.api_calls'))->toBe(7);
});

it('fills zero values for days with no activity', function () {
    // Only create a log for today — other days should be zero
    UsageLog::factory()->create([
        'app_id' => $this->app_instance->id,
        'app_user_id' => $this->appUser->id,
        'credits_charged' => 5,
        'created_at' => now(),
    ]);

    $response = $this->actingAs($this->user)
        ->getJson("/api/v1/apps/{$this->app_instance->id}/chart-data?range=7d");

    $response->assertOk();

    $apiCalls = $response->json('datasets.api_calls');
    expect(count($apiCalls))->toBe(7);

    // First 6 days should be 0, last day should be 1
    $zeros = array_filter($apiCalls, fn ($v) => $v === 0);
    expect(count($zeros))->toBe(6);
    expect($response->json('totals.api_calls'))->toBe(1);
});

it('rejects unauthenticated requests', function () {
    $response = $this->getJson("/api/v1/apps/{$this->app_instance->id}/chart-data?range=7d");

    $response->assertStatus(401);
});
