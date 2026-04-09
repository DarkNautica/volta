<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InsufficientCreditsException extends Exception
{
    public function __construct(string $message = 'Insufficient credits.')
    {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'insufficient_credits',
            'message' => $this->getMessage(),
        ], 402);
    }
}
