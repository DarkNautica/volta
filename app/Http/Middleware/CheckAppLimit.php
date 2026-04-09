<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $limit = $user->appLimit();

        if ($limit === null) {
            return $next($request);
        }

        $currentCount = $user->apps()->count();

        if ($currentCount >= $limit) {
            $planConfig = config('volta.plans.' . $user->plan);
            $planName = $planConfig['name'] ?? 'Trial';

            $plans = config('volta.plans');
            $nextPlan = null;
            $nextLimit = null;

            foreach ($plans as $key => $plan) {
                if (($plan['app_limit'] ?? 0) > $limit) {
                    $nextPlan = $plan['name'];
                    $nextLimit = $plan['app_limit'] ? "up to {$plan['app_limit']}" : 'unlimited';
                    break;
                }
            }

            $message = "You've reached the {$limit} app limit on the {$planName} plan.";
            if ($nextPlan) {
                $message .= " Upgrade to {$nextPlan} for {$nextLimit} apps.";
            }

            return redirect()->back()->with('error', $message);
        }

        return $next($request);
    }
}
