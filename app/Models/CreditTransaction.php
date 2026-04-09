<?php

namespace App\Models;

use Database\Factories\CreditTransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransaction extends Model
{
    /** @use HasFactory<CreditTransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'app_id', 'app_user_id', 'type', 'amount', 'description', 'stripe_payment_intent_id',
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function appUser(): BelongsTo
    {
        return $this->belongsTo(AppUser::class);
    }
}
