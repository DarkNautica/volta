<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class SetupStripeCommand extends Command
{
    protected $signature = 'volta:setup-stripe';

    protected $description = 'Create Stripe products and prices for Volta subscription plans';

    public function handle(): int
    {
        $secret = config('volta.stripe_secret');

        if (! $secret) {
            $this->error('STRIPE_SECRET is not set. Add it to your .env file first.');

            return self::FAILURE;
        }

        Stripe::setApiKey($secret);

        $plans = [
            'indie' => ['name' => 'Volta Indie', 'amount' => 1900],
            'studio' => ['name' => 'Volta Studio', 'amount' => 4900],
            'agency' => ['name' => 'Volta Agency', 'amount' => 14900],
        ];

        $this->info('Creating Stripe products and prices...');
        $this->newLine();

        foreach ($plans as $key => $plan) {
            $product = Product::create([
                'name' => $plan['name'],
                'metadata' => ['volta_plan' => $key],
            ]);

            $price = Price::create([
                'product' => $product->id,
                'unit_amount' => $plan['amount'],
                'currency' => 'usd',
                'recurring' => ['interval' => 'month'],
            ]);

            $envKey = 'STRIPE_PRICE_'.strtoupper($key);
            $this->line("<info>{$plan['name']}</info>");
            $this->line("  Product: {$product->id}");
            $this->line("  Price:   {$price->id}");
            $this->line("  Add to .env: <comment>{$envKey}={$price->id}</comment>");
            $this->newLine();
        }

        $this->info('Done! Add the price IDs above to your .env file.');

        return self::SUCCESS;
    }
}
