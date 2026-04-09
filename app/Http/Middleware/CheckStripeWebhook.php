<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class CheckStripeWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('volta.stripe_webhook_secret');

        if (! $sigHeader || ! $secret) {
            return response()->json(['error' => 'Invalid webhook configuration.'], 400);
        }

        try {
            Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException) {
            return response()->json(['error' => 'Invalid signature.'], 400);
        }

        return $next($request);
    }
}
