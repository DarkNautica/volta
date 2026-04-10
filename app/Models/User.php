<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'plan', 'billing_period', 'trial_ends_at', 'stripe_customer_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function apps(): HasMany
    {
        return $this->hasMany(App::class);
    }

    public function onTrial(): bool
    {
        return $this->plan === null && $this->trial_ends_at?->isFuture();
    }

    public function trialExpired(): bool
    {
        return $this->plan === null && ($this->trial_ends_at === null || $this->trial_ends_at->isPast());
    }

    public function trialDaysRemaining(): int
    {
        if (! $this->onTrial()) {
            return 0;
        }

        return (int) now()->diffInDays($this->trial_ends_at, false);
    }

    public function appLimit(): ?int
    {
        if (! $this->plan) {
            return 1;
        }

        return config("volta.plans.{$this->plan}.app_limit");
    }

    public function canCreateApp(): bool
    {
        $limit = $this->appLimit();

        if ($limit === null) {
            return true;
        }

        return $this->apps()->count() < $limit;
    }

    public function planName(): string
    {
        if (! $this->plan) {
            return 'Trial';
        }

        return config("volta.plans.{$this->plan}.name", ucfirst($this->plan));
    }
}
