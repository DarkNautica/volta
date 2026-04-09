<?php

namespace App\Http\Middleware;

use App\Models\App;
use App\Services\VoltaService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveAppKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = config('volta.app_key_header', 'X-Volta-Key');
        $appKey = $request->header($header);

        if (! $appKey) {
            return response()->json(['error' => 'missing_app_key', 'message' => 'X-Volta-Key header is required.'], 401);
        }

        $app = App::where('app_key', $appKey)->where('active', true)->first();

        if (! $app) {
            return response()->json(['error' => 'invalid_app_key', 'message' => 'Invalid or inactive app key.'], 401);
        }

        $request->merge(['volta_app' => $app]);
        app(VoltaService::class)->setApp($app);

        return $next($request);
    }
}
