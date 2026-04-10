<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlan
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user->plan && $user->trialExpired()) {
            return redirect('/dashboard/billing')->with('error', 'Your trial has ended. Choose a plan to continue.');
        }

        return $next($request);
    }
}
