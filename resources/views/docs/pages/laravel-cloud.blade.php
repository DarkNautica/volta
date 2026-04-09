@extends('docs.layout')

@section('content')
    <h2>Overview</h2>
    <p>Laravel Cloud is the recommended way to deploy Volta-powered applications. It handles scaling, SSL, queues, and environment management out of the box.</p>

    <h2>Deployment steps</h2>
    <ol>
        <li>Push your code to GitHub</li>
        <li>Connect your repository in the Laravel Cloud dashboard</li>
        <li>Set your environment variables (see below)</li>
        <li>Deploy</li>
    </ol>

    <h2>Required environment variables</h2>
    <p>Set these in your Laravel Cloud environment settings:</p>

    @include('docs.partials._code-block', ['language' => 'env', 'code' => '# Volta
VOLTA_APP_KEY=your-production-app-key
VOLTA_BASE_URL=https://api.volta.dev/v1

# Stripe (production keys)
STRIPE_SECRET=sk_live_...
STRIPE_PUBLISHABLE_KEY=pk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Laravel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com'])

    <h2>Queue configuration</h2>
    <p>Volta's webhook processing uses Laravel's queue system. Make sure you have a queue worker running in your Laravel Cloud deployment.</p>

    @include('docs.partials._callout', ['type' => 'info', 'title' => 'Queue driver', 'content' => 'Laravel Cloud uses the <code>database</code> queue driver by default. For production workloads, consider upgrading to Redis or SQS for better performance.'])

    <h2>Health checks</h2>
    <p>Laravel Cloud monitors the <code>/up</code> health endpoint automatically. Volta doesn't interfere with this — no additional configuration is needed.</p>

    <h2>Custom domains</h2>
    <p>After deploying, add your custom domain in the Laravel Cloud dashboard. Make sure your Stripe webhook endpoint is updated to use the production domain:</p>
    <p><code>https://your-domain.com/stripe/webhook</code></p>
@endsection
