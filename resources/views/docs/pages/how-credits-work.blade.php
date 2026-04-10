@extends('docs.layout')

@section('content')
    <h2>Credit lifecycle</h2>
    <p>Credits flow through a simple lifecycle from purchase to consumption:</p>

    <div class="flex flex-col gap-3 my-6">
        @php
            $steps = [
                ['User buys credits', 'User selects a credit package in the embeddable portal or your custom UI.'],
                ['Stripe Checkout', 'Stripe processes the payment securely. No card details touch your server.'],
                ['Webhook fires', 'Stripe sends checkout.session.completed to your webhook endpoint.'],
                ['topUp() called', 'The webhook handler calls Volta::topUp() to add credits to the user\'s balance.'],
                ['Balance increases', 'The user\'s credit balance is updated immediately and visible in the portal.'],
                ['User makes AI call', 'Your app processes an AI request on behalf of the user.'],
                ['charge() called', 'After a successful AI response, you call Volta::charge() to deduct credits.'],
                ['Balance decreases', 'Credits are deducted. The cycle repeats.'],
            ];
        @endphp
        @foreach($steps as $i => [$stepTitle, $desc])
            <div class="flex items-start gap-4 p-4 rounded-lg" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold" style="background-color: #00C2FF; color: #0a0a1a;">{{ $i + 1 }}</div>
                <div>
                    <p class="font-semibold text-sm mb-0.5" style="color: #0a0a1a;">{{ $stepTitle }}</p>
                    <p class="text-sm" style="color: #64748b; margin: 0;">{{ $desc }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <h2>Deduction rules</h2>
    <p>Credits are <strong>only</strong> deducted when you explicitly call <code>Volta::charge()</code>. This is intentional — if an AI API call fails, you should not charge the user. Always call <code>charge()</code> after a successful response.</p>

    @include('docs.partials._callout', ['type' => 'tip', 'title' => 'Best practice', 'content' => 'Always structure your code as: check access &rarr; call AI &rarr; charge. Never charge before you have a successful result to deliver.'])

    <h2>Purchase flow</h2>
    <p>Volta supports credit purchases through Stripe Checkout. The flow works like this:</p>
    <ol>
        <li>Generate a portal URL with <code>Volta::portalUrl($userId)</code></li>
        <li>The user visits the portal and selects a credit package</li>
        <li>Stripe processes the payment</li>
        <li>The <code>checkout.session.completed</code> webhook fires</li>
        <li>Your webhook handler calls <code>Volta::topUp()</code></li>
        <li>Credits are immediately available</li>
    </ol>

    <h2>Credit packages</h2>
    <p>Credit packages are configured per-app in the Volta dashboard. Each package is an array of <code>[credits, price_in_cents]</code>:</p>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// Default credit packages (configured in dashboard)
[
    [100, 499],    // 100 credits for $4.99
    [500, 1999],   // 500 credits for $19.99
    [1000, 3499],  // 1,000 credits for $34.99
]'])

    <p>You can override packages per-portal session when generating the URL:</p>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => '$url = Volta::portalUrl($userId, [
    \'credits_packages\' => [
        [50, 299],
        [200, 999],
    ],
]);'])

    <h2>Refunds and adjustments</h2>
    <p>To add credits back to a user (for refunds, support gestures, or promotional credits), use <code>Volta::topUp()</code>:</p>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// Refund 50 credits to a user
Volta::topUp($userId, 50);'])

    <p>For Stripe refunds, process the refund through Stripe as usual. The credit balance is independent of Stripe — you'll need to call <code>topUp()</code> separately if you want to restore credits.</p>
@endsection
