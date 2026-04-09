@extends('docs.layout')

@section('content')
    <h2>Prerequisites</h2>
    <ul>
        <li>A <a href="https://stripe.com">Stripe</a> account (test mode is fine for development)</li>
        <li>Your Stripe Secret Key</li>
        <li>Your Stripe Webhook Signing Secret</li>
    </ul>

    <h2>Step 1: Get your Stripe keys</h2>
    <ol>
        <li>Log in to the <strong>Stripe Dashboard</strong></li>
        <li>Go to <strong>Developers &rarr; API keys</strong></li>
        <li>Copy your <strong>Secret key</strong> (starts with <code>sk_test_</code> or <code>sk_live_</code>)</li>
        <li>Copy your <strong>Publishable key</strong> (starts with <code>pk_test_</code> or <code>pk_live_</code>)</li>
    </ol>

    <h2>Step 2: Add to .env</h2>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => 'STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_PUBLISHABLE_KEY=pk_test_your_publishable_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here'])

    <h2>Step 3: Configure webhook endpoint</h2>
    <ol>
        <li>In the Stripe Dashboard, go to <strong>Developers &rarr; Webhooks</strong></li>
        <li>Click <strong>Add endpoint</strong></li>
        <li>Set the endpoint URL to: <code>https://your-domain.com/stripe/webhook</code></li>
        <li>Select the following events:
            <ul>
                <li><code>checkout.session.completed</code></li>
                <li><code>customer.subscription.updated</code></li>
                <li><code>customer.subscription.deleted</code></li>
            </ul>
        </li>
        <li>Click <strong>Add endpoint</strong></li>
        <li>Copy the <strong>Signing secret</strong> and add it to your <code>.env</code> as <code>STRIPE_WEBHOOK_SECRET</code></li>
    </ol>

    <h2>Step 4: Test with Stripe CLI</h2>
    <p>For local development, use the Stripe CLI to forward webhook events to your local server:</p>

    @include('docs.partials._code-block', ['language' => 'bash', 'code' => '# Install the Stripe CLI
brew install stripe/stripe-cli/stripe

# Login
stripe login

# Forward webhooks to your local server
stripe listen --forward-to volta.test/stripe/webhook'])

    <p>The CLI will output a webhook signing secret — use that as your <code>STRIPE_WEBHOOK_SECRET</code> during local development.</p>

    <h2>Step 5: Test the purchase flow</h2>
    <ol>
        <li>Generate a portal URL for a test user</li>
        <li>Open the portal and select a credit package</li>
        <li>Use Stripe's test card: <code>4242 4242 4242 4242</code></li>
        <li>Complete the checkout</li>
        <li>Verify the user's credit balance increased</li>
    </ol>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// Quick test in Tinker
$url = Volta::portalUrl(\'test_user_1\');
echo $url; // Open this in your browser'])

    @include('docs.partials._callout', ['type' => 'tip', 'title' => 'Test card numbers', 'content' => 'Use <code>4242 4242 4242 4242</code> for successful payments, <code>4000 0000 0000 0002</code> for declined payments. Any future date and any 3-digit CVC will work in test mode.'])
@endsection
