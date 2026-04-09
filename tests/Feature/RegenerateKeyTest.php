<?php

use App\Models\App;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['plan' => 'studio']);
    $this->app_instance = App::factory()->create(['user_id' => $this->user->id]);
});

it('generates a new key different from old key', function () {
    $oldKey = $this->app_instance->app_key;

    $response = $this->actingAs($this->user)
        ->postJson("/dashboard/apps/{$this->app_instance->id}/regenerate-key");

    $response->assertOk()->assertJsonStructure(['app_key']);
    expect($response->json('app_key'))->not->toBe($oldKey);
});

it('old key no longer resolves app after regeneration', function () {
    $oldKey = $this->app_instance->app_key;

    $this->actingAs($this->user)
        ->postJson("/dashboard/apps/{$this->app_instance->id}/regenerate-key");

    // Try to use the old key via the API
    $response = $this->getJson('/api/v1/balance/user_123', [
        'X-Volta-Key' => $oldKey,
    ]);

    $response->assertStatus(401)
        ->assertJson(['error' => 'invalid_app_key']);
});

it('new key resolves app after regeneration', function () {
    $response = $this->actingAs($this->user)
        ->postJson("/dashboard/apps/{$this->app_instance->id}/regenerate-key");

    $newKey = $response->json('app_key');

    $response = $this->getJson('/api/v1/balance/user_123', [
        'X-Volta-Key' => $newKey,
    ]);

    $response->assertOk()
        ->assertJsonStructure(['user_id', 'balance']);
});
