<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class VoltaRateLimiter
{
    public function handle(Request $request, Closure $next): Response
    {
        $app = $request->volta_app ?? null;
        if (! $app) {
            return $next($request);
        }

        $externalUserId = $request->input('user_id') ?? $request->route('externalUserId') ?? 'unknown';
        $key = "volta-api:{$app->id}:{$externalUserId}";

        if (RateLimiter::tooManyAttempts($key, $app->rate_limit_per_hour)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'error' => 'rate_limit_exceeded',
                'retry_after' => $retryAfter,
                'message' => "Rate limit exceeded. Try again in {$retryAfter} seconds.",
            ], 429);
        }

        RateLimiter::hit($key, 3600);

        return $next($request);
    }
}
