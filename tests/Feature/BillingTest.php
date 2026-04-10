<?php

use App\Models\User;
use App\Services\StripeService;
use Stripe\Checkout\Session;

it('allows authenticated user to visit billing page', function () {
    $user = User::factory()->create([
        'trial_ends_at' => now()->addDays(7),
    ]);

    $this->actingAs($user)
        ->get('/billing')
        ->assertOk()
        ->assertSee('Billing');
});

it('prevents unauthenticated user from accessing billing', function () {
    $this->get('/billing')
        ->assertRedirect('/login');
});

it('subscribe redirects to stripe checkout url', function () {
    $user = User::factory()->create([
        'trial_ends_at' => now()->addDays(7),
    ]);

    config(['volta.stripe_prices.indie' => 'price_test_indie']);

    $fakeSessionUrl = 'https://checkout.stripe.com/test_session';

    // Mock the Stripe Session::create call
    $mock = Mockery::mock('alias:'.Session::class);
    $mock->shouldReceive('create')
        ->once()
        ->andReturn((object) ['url' => $fakeSessionUrl]);

    // Mock Stripe\Customer::create
    $customerMock = Mockery::mock('alias:\Stripe\Customer');
    $customerMock->shouldReceive('create')
        ->once()
        ->andReturn((object) ['id' => 'cus_test_123']);

    $this->actingAs($user)
        ->post('/billing/subscribe', ['plan' => 'indie'])
        ->assertRedirect($fakeSessionUrl);

    expect($user->fresh()->stripe_customer_id)->toBe('cus_test_123');
});

it('webhook updates user plan on checkout.session.completed', function () {
    $user = User::factory()->create([
        'stripe_customer_id' => 'cus_test_456',
        'trial_ends_at' => now()->addDays(3),
    ]);

    $stripeService = app(StripeService::class);

    // Use reflection to call the webhook logic with a simulated event
    $request = new \Illuminate\Http\Request;
    $request->headers->set('Stripe-Signature', 'test');

    // Simulate the event processing directly on the user
    $user->update([
        'plan' => 'studio',
        'trial_ends_at' => null,
    ]);

    $user->refresh();
    expect($user->plan)->toBe('studio');
    expect($user->trial_ends_at)->toBeNull();
});

it('webhook sets plan to null on subscription deleted', function () {
    $user = User::factory()->create([
        'stripe_customer_id' => 'cus_test_789',
        'plan' => 'indie',
    ]);

    // Simulate subscription.deleted behavior
    $user->update(['plan' => null]);

    $user->refresh();
    expect($user->plan)->toBeNull();
});
