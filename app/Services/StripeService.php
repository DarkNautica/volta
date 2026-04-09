<?php

namespace App\Services;

use App\Models\App;
use App\Models\AppUser;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    public function createCheckoutSession(App $app, AppUser $appUser, int $credits, int $priceInCents): string
    {
        $stripeConfig = $app->stripe_config;
        $secretKey = $stripeConfig['secret_key'] ?? config('volta.stripe_secret');

        Stripe::setApiKey($secretKey);

        $params = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => "{$credits} Credits for {$app->name}",
                    ],
                    'unit_amount' => $priceInCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/api/v1/stripe/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/'),
            'metadata' => [
                'app_id' => $app->id,
                'app_user_id' => $appUser->id,
                'external_user_id' => $appUser->external_user_id,
                'credits' => $credits,
            ],
        ];

        $connectOptions = [];
        if (! empty($stripeConfig['connected_account_id'])) {
            $connectOptions['stripe_account'] = $stripeConfig['connected_account_id'];
        }

        $session = Session::create($params, $connectOptions);

        return $session->url;
    }

    public function createCheckoutSessionForPortal(App $app, AppUser $appUser, int $credits, int $priceInCents, string $returnUrl): string
    {
        $stripeConfig = $app->stripe_config;
        $secretKey = $stripeConfig['secret_key'] ?? config('volta.stripe_secret');

        Stripe::setApiKey($secretKey);

        // Build success URL — append success=1 to the portal signed URL
        $successUrl = preg_replace('/\?/', '?success=1&', $returnUrl, 1);

        $params = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => "{$credits} Credits for {$app->name}",
                    ],
                    'unit_amount' => $priceInCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $returnUrl,
            'metadata' => [
                'app_id' => $app->id,
                'app_user_id' => $appUser->id,
                'external_user_id' => $appUser->external_user_id,
                'credits' => $credits,
            ],
        ];

        $connectOptions = [];
        if (! empty($stripeConfig['connected_account_id'])) {
            $connectOptions['stripe_account'] = $stripeConfig['connected_account_id'];
        }

        $session = Session::create($params, $connectOptions);

        return $session->url;
    }

    public function handleWebhook(Request $request): void
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('volta.stripe_webhook_secret');

        $event = Webhook::constructEvent($payload, $sigHeader, $secret);

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $metadata = $session->metadata;

            $app = App::findOrFail($metadata->app_id);

            $voltaService = new VoltaService($app);
            $voltaService->topUp(
                $metadata->external_user_id,
                (int) $metadata->credits,
                $session->payment_intent,
            );
        }

        if ($event->type === 'customer.subscription.updated' || $event->type === 'customer.subscription.deleted') {
            $subscription = $event->data->object;
            $stripeCustomerId = $subscription->customer;

            $user = \App\Models\User::where('stripe_customer_id', $stripeCustomerId)->first();
            if ($user) {
                if ($event->type === 'customer.subscription.deleted') {
                    $user->update(['plan' => null]);
                } else {
                    $priceId = $subscription->items->data[0]->price->id ?? null;
                    // Plan mapping would be configured per environment
                    $user->update(['plan' => $this->mapPriceToPlan($priceId)]);
                }
            }
        }
    }

    protected function mapPriceToPlan(?string $priceId): ?string
    {
        // In production, map Stripe price IDs to plan names via config
        return null;
    }
}
