<?php

use App\Models\App;
use App\Models\User;

test('trial user within trial period can access dashboard', function () {
    $user = User::factory()->create([
        'plan' => null,
        'trial_ends_at' => now()->addDays(7),
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk();
});

test('trial user with expired trial is redirected to billing', function () {
    $user = User::factory()->create([
        'plan' => null,
        'trial_ends_at' => now()->subDays(1),
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect('/billing');
});

test('user on indie plan cannot create a second app', function () {
    $user = User::factory()->create(['plan' => 'indie']);
    App::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post('/dashboard/apps', [
            'name' => 'Second App',
            'ai_provider' => 'anthropic',
        ])
        ->assertRedirect()
        ->assertSessionHas('error');
});

test('user on studio plan can create up to 5 apps', function () {
    $user = User::factory()->create(['plan' => 'studio']);
    App::factory(4)->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post('/dashboard/apps', [
            'name' => 'Fifth App',
            'ai_provider' => 'openai',
        ])
        ->assertRedirect('/dashboard/apps')
        ->assertSessionHas('success');
});

test('agency user has no app limit', function () {
    $user = User::factory()->create(['plan' => 'agency']);
    App::factory(10)->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post('/dashboard/apps', [
            'name' => 'Another App',
            'ai_provider' => 'gemini',
        ])
        ->assertRedirect('/dashboard/apps')
        ->assertSessionHas('success');
});

test('onTrial returns true when trial is active', function () {
    $user = User::factory()->create([
        'plan' => null,
        'trial_ends_at' => now()->addDays(7),
    ]);

    expect($user->onTrial())->toBeTrue();
});

test('trialExpired returns true when trial_ends_at is past', function () {
    $user = User::factory()->create([
        'plan' => null,
        'trial_ends_at' => now()->subDays(1),
    ]);

    expect($user->trialExpired())->toBeTrue();
});

test('canCreateApp returns false when at limit', function () {
    $user = User::factory()->create(['plan' => 'indie']);
    App::factory()->create(['user_id' => $user->id]);

    expect($user->canCreateApp())->toBeFalse();
});
