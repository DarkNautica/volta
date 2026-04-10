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
        $validated = $request->validate([
            'plan' => 'required|in:indie,studio,agency',
            'billing_period' => 'sometimes|in:monthly,annual',
        ]);

        $plan = $validated['plan'];
        $billingPeriod = $validated['billing_period'] ?? 'monthly';

        $priceKey = $billingPeriod === 'annual'
            ? "stripe_prices.{$plan}_annual"
            : "stripe_prices.{$plan}";

        $priceId = config("volta.{$priceKey}");

        if (! $priceId) {
            return back()->with('error', 'Invalid plan selected. Please try again.');
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
                'billing_period' => $billingPeriod,
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
        $key = array_search($priceId, $prices) ?: null;

        // Strip _annual suffix to return the base plan name
        if ($key && str_ends_with($key, '_annual')) {
            return str_replace('_annual', '', $key);
        }

        return $key;
    }

    public static function billingPeriodFromPriceId(string $priceId): string
    {
        $prices = config('volta.stripe_prices');
        $key = array_search($priceId, $prices) ?: null;

        return ($key && str_ends_with($key, '_annual')) ? 'annual' : 'monthly';
    }
}
