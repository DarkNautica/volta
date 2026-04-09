<?php

namespace App\Models;

use Database\Factories\AppFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class App extends Model
{
    /** @use HasFactory<AppFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'slug', 'app_key', 'ai_provider', 'active',
        'rate_limit_per_hour', 'stripe_config', 'credit_packages',
    ];

    protected $casts = [
        'active' => 'boolean',
        'stripe_config' => 'array',
        'credit_packages' => 'array',
    ];

    public function getDefaultCreditPackages(): array
    {
        return $this->credit_packages ?? [[100, 499], [500, 1999], [1000, 3499]];
    }

    protected static function booted(): void
    {
        static::creating(function (App $app) {
            if (empty($app->app_key)) {
                $app->app_key = (string) Str::uuid();
            }
            if (empty($app->slug)) {
                $app->slug = Str::slug($app->name) . '-' . Str::random(6);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appUsers(): HasMany
    {
        return $this->hasMany(AppUser::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(UsageLog::class);
    }

    public function appModels(): HasMany
    {
        return $this->hasMany(AppModel::class);
    }
}
