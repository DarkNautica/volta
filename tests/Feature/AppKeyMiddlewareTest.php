<?php

use App\Models\App;

it('rejects requests without app key', function () {
    $response = $this->getJson('/api/v1/balance/user_123');

    $response->assertStatus(401)
        ->assertJson(['error' => 'missing_app_key']);
});

it('rejects requests with invalid app key', function () {
    $response = $this->getJson('/api/v1/balance/user_123', [
        'X-Volta-Key' => 'invalid-key-here',
    ]);

    $response->assertStatus(401)
        ->assertJson(['error' => 'invalid_app_key']);
});

it('resolves correct app with valid key', function () {
    $app = App::factory()->create();

    $response = $this->getJson('/api/v1/balance/user_123', [
        'X-Volta-Key' => $app->app_key,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['user_id', 'balance']);
});
