<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class RateLimitExceededException extends Exception
{
    public function __construct(
        string $message = 'Rate limit exceeded.',
        protected int $retryAfter = 60,
    ) {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'rate_limit_exceeded',
            'retry_after' => $this->retryAfter,
            'message' => $this->getMessage(),
        ], 429);
    }
}
