@extends('docs.layout')

@section('content')
    <h2>Complete reference</h2>
    <p>All environment variables used by Volta and its integrations.</p>

    <h3>Volta SDK</h3>
    <table>
        <thead>
            <tr>
                <th>Variable</th>
                <th>Required</th>
                <th>Default</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>VOLTA_APP_KEY</code></td>
                <td>Yes</td>
                <td>—</td>
                <td>Your Volta app key</td>
            </tr>
            <tr>
                <td><code>VOLTA_BASE_URL</code></td>
                <td>No</td>
                <td><code>https://api.volta.dev/v1</code></td>
                <td>Volta API endpoint</td>
            </tr>
            <tr>
                <td><code>VOLTA_VERIFY_SSL</code></td>
                <td>No</td>
                <td><code>true</code></td>
                <td>SSL verification (disable only for local dev)</td>
            </tr>
            <tr>
                <td><code>VOLTA_CACHE_TTL</code></td>
                <td>No</td>
                <td><code>30</code></td>
                <td>Balance cache duration in seconds</td>
            </tr>
            <tr>
                <td><code>VOLTA_FAIL_SILENTLY</code></td>
                <td>No</td>
                <td><code>false</code></td>
                <td>Suppress charge exceptions</td>
            </tr>
        </tbody>
    </table>

    <h3>Stripe</h3>
    <table>
        <thead>
            <tr>
                <th>Variable</th>
                <th>Required</th>
                <th>Default</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>STRIPE_SECRET</code></td>
                <td>Yes</td>
                <td>—</td>
                <td>Stripe secret API key</td>
            </tr>
            <tr>
                <td><code>STRIPE_PUBLISHABLE_KEY</code></td>
                <td>Yes</td>
                <td>—</td>
                <td>Stripe publishable key (for frontend)</td>
            </tr>
            <tr>
                <td><code>STRIPE_WEBHOOK_SECRET</code></td>
                <td>Yes</td>
                <td>—</td>
                <td>Stripe webhook signing secret</td>
            </tr>
        </tbody>
    </table>

    <h3>AI Providers</h3>
    <table>
        <thead>
            <tr>
                <th>Variable</th>
                <th>Provider</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>ANTHROPIC_API_KEY</code></td>
                <td>Anthropic</td>
                <td>Claude API key</td>
            </tr>
            <tr>
                <td><code>OPENAI_API_KEY</code></td>
                <td>OpenAI</td>
                <td>OpenAI API key</td>
            </tr>
            <tr>
                <td><code>GEMINI_API_KEY</code></td>
                <td>Google</td>
                <td>Gemini API key</td>
            </tr>
        </tbody>
    </table>

    <h2>Environment-specific settings</h2>

    <h3>Local development</h3>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => 'APP_ENV=local
APP_DEBUG=true
VOLTA_VERIFY_SSL=false
STRIPE_SECRET=sk_test_...'])

    <h3>Production</h3>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => 'APP_ENV=production
APP_DEBUG=false
# Do NOT set VOLTA_VERIFY_SSL (defaults to true)
STRIPE_SECRET=sk_live_...'])

    @include('docs.partials._callout', ['type' => 'danger', 'title' => 'Never commit .env files', 'content' => 'Your <code>.env</code> file contains secrets. Never commit it to version control. Use <code>.env.example</code> to document required variables without actual values.'])
@endsection
