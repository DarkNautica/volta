<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\BillingPortal\Session as PortalSession;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class BillingController extends Controller
{
    public function index(Request $request): View
    {
        return view('billing.index', [
            'user' => $request->user(),
            'plans' => config('volta.plans'),
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate(['plan' => 'required|in:indie,studio,agency']);

        $plan = config("volta.plans.{$request->plan}");
        Stripe::setApiKey(config('volta.stripe_secret'));

        $user = $request->user();

        if (! $user->stripe_customer_id) {
            $customer = \Stripe\Customer::create(['email' => $user->email]);
            $user->update(['stripe_customer_id' => $customer->id]);
        }

        $session = Session::create([
            'customer' => $user->stripe_customer_id,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => "Volta {$plan['name']} Plan"],
                    'unit_amount' => $plan['price'],
                    'recurring' => ['interval' => 'month'],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => url('/dashboard?subscribed=1'),
            'cancel_url' => url('/billing'),
            'metadata' => ['plan' => $request->plan],
        ]);

        return redirect($session->url);
    }

    public function portal(Request $request)
    {
        Stripe::setApiKey(config('volta.stripe_secret'));

        $session = PortalSession::create([
            'customer' => $request->user()->stripe_customer_id,
            'return_url' => url('/billing'),
        ]);

        return redirect($session->url);
    }
}
