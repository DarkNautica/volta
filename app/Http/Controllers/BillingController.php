<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        $plan = $request->plan;
        $priceId = config("volta.stripe_prices.{$plan}");
        $planConfig = config("volta.plans.{$plan}");

        if (! $priceId) {
            return back()->with('error', 'This plan is not configured yet.');
        }

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
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => url('/billing?subscribed=1'),
            'cancel_url' => url('/dashboard/billing'),
            'metadata' => [
                'user_id' => $user->id,
                'plan' => $plan,
            ],
        ]);

        return redirect($session->url);
    }

    public function portal(Request $request)
    {
        $user = $request->user();

        if (! $user->stripe_customer_id) {
            return redirect('/dashboard/billing')->with('error', 'No active subscription found.');
        }

        Stripe::setApiKey(config('volta.stripe_secret'));

        $session = PortalSession::create([
            'customer' => $user->stripe_customer_id,
            'return_url' => url('/dashboard/billing'),
        ]);

        return redirect($session->url);
    }

    public static function planFromPriceId(string $priceId): ?string
    {
        $prices = config('volta.stripe_prices');

        return array_search($priceId, $prices) ?: null;
    }
}
