<?php

namespace App\Services;

use App\Exceptions\InsufficientCreditsException;
use App\Exceptions\RateLimitExceededException;
use App\Models\App;
use App\Models\AppModel;
use App\Models\AppUser;
use App\Models\CreditTransaction;
use App\Models\UsageLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class VoltaService
{
    public function __construct(protected ?App $app = null)
    {
    }

    public function setApp(App $app): static
    {
        $this->app = $app;

        return $this;
    }

    public function charge(string $externalUserId, int $credits = 1, ?string $modelIdentifier = null): bool
    {
        $appUser = $this->resolveAppUser($externalUserId);

        $rateLimitKey = "volta:{$this->app->id}:{$externalUserId}";
        if (RateLimiter::tooManyAttempts($rateLimitKey, $this->app->rate_limit_per_hour)) {
            $retryAfter = RateLimiter::availableIn($rateLimitKey);
            throw new RateLimitExceededException(
                "Rate limit exceeded. Try again in {$retryAfter} seconds.",
                $retryAfter,
            );
        }

        if ($appUser->credit_balance < $credits) {
            throw new InsufficientCreditsException(
                "Insufficient credits. Balance: {$appUser->credit_balance}, required: {$credits}."
            );
        }

        $appModel = $modelIdentifier
            ? AppModel::where('app_id', $this->app->id)->where('model_identifier', $modelIdentifier)->first()
            : null;

        DB::transaction(function () use ($appUser, $credits, $appModel) {
            $appUser->decrement('credit_balance', $credits);

            CreditTransaction::create([
                'app_id' => $this->app->id,
                'app_user_id' => $appUser->id,
                'type' => 'deduction',
                'amount' => -$credits,
                'description' => 'API usage charge',
            ]);

            UsageLog::create([
                'app_id' => $this->app->id,
                'app_user_id' => $appUser->id,
                'app_model_id' => $appModel?->id,
                'credits_charged' => $credits,
                'success' => true,
            ]);
        });

        RateLimiter::hit($rateLimitKey, 3600);

        return true;
    }

    public function hasAccess(string $externalUserId, int $credits = 1): bool
    {
        $appUser = AppUser::where('app_id', $this->app->id)
            ->where('external_user_id', $externalUserId)
            ->first();

        if (! $appUser || $appUser->credit_balance < $credits) {
            return false;
        }

        $rateLimitKey = "volta:{$this->app->id}:{$externalUserId}";
        if (RateLimiter::tooManyAttempts($rateLimitKey, $this->app->rate_limit_per_hour)) {
            return false;
        }

        return true;
    }

    public function balance(string $externalUserId): int
    {
        $appUser = $this->resolveAppUser($externalUserId);

        return $appUser->credit_balance;
    }

    public function topUp(string $externalUserId, int $credits, ?string $stripePaymentIntentId = null): bool
    {
        $appUser = $this->resolveAppUser($externalUserId);

        DB::transaction(function () use ($appUser, $credits, $stripePaymentIntentId) {
            $appUser->increment('credit_balance', $credits);

            CreditTransaction::create([
                'app_id' => $this->app->id,
                'app_user_id' => $appUser->id,
                'type' => 'purchase',
                'amount' => $credits,
                'description' => 'Credit top-up',
                'stripe_payment_intent_id' => $stripePaymentIntentId,
            ]);
        });

        return true;
    }

    public function usage(string $externalUserId): array
    {
        $appUser = $this->resolveAppUser($externalUserId);

        $logs = UsageLog::where('app_user_id', $appUser->id)
            ->where('app_id', $this->app->id);

        return [
            'external_user_id' => $externalUserId,
            'credit_balance' => $appUser->credit_balance,
            'total_calls' => $logs->count(),
            'total_credits_used' => $logs->sum('credits_charged'),
            'calls_today' => $logs->clone()->whereDate('created_at', today())->count(),
        ];
    }

    public function portalUrl(string $externalUserId, array $options = []): string
    {
        $params = [
            'app_id' => $this->app->id,
            'external_user_id' => $externalUserId,
            'theme' => $options['theme'] ?? 'dark',
        ];

        if (! empty($options['return_url'])) {
            $params['return_url'] = $options['return_url'];
        }

        if (! empty($options['credits_packages'])) {
            $params['packages'] = json_encode($options['credits_packages']);
        }

        return URL::signedRoute('portal.index', $params, now()->addHours(2));
    }

    protected function resolveAppUser(string $externalUserId): AppUser
    {
        return AppUser::firstOrCreate(
            ['app_id' => $this->app->id, 'external_user_id' => $externalUserId],
            ['credit_balance' => 0],
        );
    }
}
