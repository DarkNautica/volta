@extends('docs.layout')

@section('content')
    <h2>Pre-launch checklist</h2>
    <p>Before going live, verify every item on this list.</p>

    <div class="my-6 space-y-3">
        @php
            $items = [
                ['Real Stripe keys in production .env', 'Replace sk_test_ and pk_test_ with sk_live_ and pk_live_ keys from the Stripe dashboard.'],
                ['VOLTA_VERIFY_SSL=true (or removed)', 'SSL verification must be enabled in production. Remove the variable entirely to use the default (true).'],
                ['APP_ENV=production', 'Ensures Laravel runs in production mode with appropriate error handling.'],
                ['APP_DEBUG=false', 'Prevents sensitive information from being exposed in error pages.'],
                ['Queue worker running', 'Webhook processing depends on the queue. Ensure a worker is running for webhook events.'],
                ['Stripe webhook configured for production domain', 'Update your webhook endpoint URL in the Stripe dashboard to your production domain.'],
                ['volta:test passes against production URL', 'Run php artisan volta:test to verify the SDK can reach the Volta API with your production credentials.'],
                ['Test a real credit purchase end to end', 'Complete a full purchase flow: portal → checkout → webhook → balance increase.'],
            ];
        @endphp
        @foreach($items as [$itemTitle, $desc])
            <div class="flex items-start gap-3 p-4 rounded-lg" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="flex-shrink-0 w-5 h-5 mt-0.5 rounded border-2 flex items-center justify-center" style="border-color: #cbd5e1;">
                </div>
                <div>
                    <p class="font-semibold text-sm mb-0.5" style="color: #0a0a1a;">{{ $itemTitle }}</p>
                    <p class="text-sm" style="color: #64748b; margin: 0;">{{ $desc }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <h2>Production environment variables</h2>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => '# App
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Volta
VOLTA_APP_KEY=your-production-app-key
VOLTA_BASE_URL=https://api.volta.dev/v1
VOLTA_CACHE_TTL=30

# Stripe (live keys)
STRIPE_SECRET=sk_live_...
STRIPE_PUBLISHABLE_KEY=pk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...'])

    @include('docs.partials._callout', ['type' => 'danger', 'title' => 'Double-check your keys', 'content' => 'The most common launch issue is using test Stripe keys in production. Verify that your <code>STRIPE_SECRET</code> starts with <code>sk_live_</code> and your <code>STRIPE_PUBLISHABLE_KEY</code> starts with <code>pk_live_</code>.'])

    <h2>Laravel Cloud deployment</h2>
    <p>If you're deploying to Laravel Cloud, see the <a href="/docs/laravel-cloud">Laravel Cloud guide</a> for specific deployment instructions.</p>

    <h2>Post-launch monitoring</h2>
    <p>After launching, keep an eye on:</p>
    <ul>
        <li><strong>Volta dashboard</strong> — monitor API calls, credit purchases, and user activity</li>
        <li><strong>Stripe dashboard</strong> — check for failed payments or disputes</li>
        <li><strong>Laravel logs</strong> — watch for webhook processing errors</li>
        <li><strong>Queue monitoring</strong> — ensure webhook jobs are processing without delays</li>
    </ul>

    @include('docs.partials._callout', ['type' => 'tip', 'title' => 'You\'re ready!', 'content' => 'If every item in the checklist is verified and your test purchase went through, you\'re ready to start accepting real payments. Congratulations on launching!'])
@endsection
