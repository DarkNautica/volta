<?php

namespace App\Facades;

use App\Services\VoltaService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool charge(string $externalUserId, int $credits = 1, ?string $modelIdentifier = null)
 * @method static bool hasAccess(string $externalUserId, int $credits = 1)
 * @method static int balance(string $externalUserId)
 * @method static bool topUp(string $externalUserId, int $credits, ?string $stripePaymentIntentId = null)
 * @method static array usage(string $externalUserId)
 * @method static static setApp(\App\Models\App $app)
 */
class Volta extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return VoltaService::class;
    }
}
