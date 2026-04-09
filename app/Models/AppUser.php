<?php

namespace App\Models;

use Database\Factories\AppUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppUser extends Model
{
    /** @use HasFactory<AppUserFactory> */
    use HasFactory;

    protected $fillable = [
        'app_id', 'external_user_id', 'credit_balance',
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(UsageLog::class);
    }
}
