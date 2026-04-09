<?php

use App\Exceptions\InsufficientCreditsException;
use App\Models\App;
use App\Models\AppUser;
use App\Services\VoltaService;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    $this->app_model = App::factory()->create(['rate_limit_per_hour' => 60]);
    $this->appUser = AppUser::factory()->create([
        'app_id' => $this->app_model->id,
        'credit_balance' => 100,
    ]);
    $this->service = new VoltaService($this->app_model);
});

it('deducts credits on charge', function () {
    $this->service->charge($this->appUser->external_user_id, 10);

    $this->appUser->refresh();
    expect($this->appUser->credit_balance)->toBe(90);
});

it('throws InsufficientCreditsException when balance is too low', function () {
    $this->appUser->update(['credit_balance' => 5]);

    $this->service->charge($this->appUser->external_user_id, 10);
})->throws(InsufficientCreditsException::class);

it('increases balance on topUp', function () {
    $this->service->topUp($this->appUser->external_user_id, 50);

    $this->appUser->refresh();
    expect($this->appUser->credit_balance)->toBe(150);
});

it('returns false for hasAccess when rate limited', function () {
    $key = "volta:{$this->app_model->id}:{$this->appUser->external_user_id}";

    // Exhaust the rate limit
    for ($i = 0; $i <= $this->app_model->rate_limit_per_hour; $i++) {
        RateLimiter::hit($key, 3600);
    }

    expect($this->service->hasAccess($this->appUser->external_user_id))->toBeFalse();
});

it('returns correct balance', function () {
    expect($this->service->balance($this->appUser->external_user_id))->toBe(100);
});

it('returns usage stats', function () {
    $usage = $this->service->usage($this->appUser->external_user_id);

    expect($usage)->toHaveKeys(['external_user_id', 'credit_balance', 'total_calls', 'total_credits_used', 'calls_today']);
    expect($usage['credit_balance'])->toBe(100);
});
