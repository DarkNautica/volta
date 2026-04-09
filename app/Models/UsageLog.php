<?php

namespace App\Models;

use Database\Factories\UsageLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageLog extends Model
{
    /** @use HasFactory<UsageLogFactory> */
    use HasFactory;

    protected $fillable = [
        'app_id', 'app_user_id', 'app_model_id', 'credits_charged',
        'tokens_used', 'endpoint', 'success',
    ];

    protected $casts = [
        'success' => 'boolean',
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function appUser(): BelongsTo
    {
        return $this->belongsTo(AppUser::class);
    }

    public function appModel(): BelongsTo
    {
        return $this->belongsTo(AppModel::class);
    }
}
