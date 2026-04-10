<?php

return [
    'stripe_secret' => env('STRIPE_SECRET'),
    'stripe_webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'stripe_publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
    'stripe_prices' => [
        'indie'  => env('STRIPE_PRICE_INDIE'),
        'studio' => env('STRIPE_PRICE_STUDIO'),
        'agency' => env('STRIPE_PRICE_AGENCY'),
    ],
    'app_key_header' => 'X-Volta-Key',
    'plans' => [
        'indie' => ['name' => 'Indie', 'price' => 1900, 'app_limit' => 1],
        'studio' => ['name' => 'Studio', 'price' => 4900, 'app_limit' => 5],
        'agency' => ['name' => 'Agency', 'price' => 14900, 'app_limit' => null],
    ],
];
