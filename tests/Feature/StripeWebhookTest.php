<?php

use App\Models\App;
use App\Models\AppUser;
use App\Services\StripeService;
use App\Services\VoltaService;

it('tops up credits on checkout.session.completed', function () {
    $app = App::factory()->create();
    $appUser = AppUser::factory()->create([
        'app_id' => $app->id,
        'credit_balance' => 10,
    ]);

    // Simulate the webhook handler logic directly (avoiding Stripe signature verification)
    $voltaService = new VoltaService($app);
    $voltaService->topUp($appUser->external_user_id, 100, 'pi_test_123');

    $appUser->refresh();
    expect($appUser->credit_balance)->toBe(110);

    $this->assertDatabaseHas('credit_transactions', [
        'app_id' => $app->id,
        'app_user_id' => $appUser->id,
        'type' => 'purchase',
        'amount' => 100,
        'stripe_payment_intent_id' => 'pi_test_123',
    ]);
});
