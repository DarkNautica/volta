<?php

namespace App\Models;

use Database\Factories\AppModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppModel extends Model
{
    /** @use HasFactory<AppModelFactory> */
    use HasFactory;

    protected $fillable = [
        'app_id', 'model_identifier', 'display_name', 'credits_per_call', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
}
