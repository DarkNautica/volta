@extends('docs.layout')

@section('content')
    <h2>Config file</h2>
    <p>After publishing, the config file lives at <code>config/volta.php</code>. Here's the full annotated version:</p>

@php
$configCode = <<<'CODEBLOCK'
return [
    /*
    |--------------------------------------------------------------------------
    | App Key
    |--------------------------------------------------------------------------
    | Your Volta app key, found in the dashboard under App Settings.
    | This authenticates your Laravel app with the Volta API.
    */
    'app_key' => env('VOLTA_APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    | The Volta API endpoint. Override this if you're self-hosting
    | or running Volta locally for development.
    */
    'base_url' => env('VOLTA_BASE_URL', 'https://volta-main-7omebh.laravel.cloud'),

    /*
    |--------------------------------------------------------------------------
    | SSL Verification
    |--------------------------------------------------------------------------
    | Disable this only for local development when Volta runs
    | without HTTPS. Always keep true in production.
    */
    'verify_ssl' => env('VOLTA_VERIFY_SSL', true),

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    | How long to cache user balances (in seconds). Set to 0 to
    | disable caching. A short TTL reduces API calls while keeping
    | balances reasonably fresh.
    */
    'cache_balance_ttl' => env('VOLTA_CACHE_TTL', 30),

    /*
    |--------------------------------------------------------------------------
    | Fail Silently
    |--------------------------------------------------------------------------
    | When true, failed charge() calls return false instead of
    | throwing. Use with caution — this means users may receive
    | AI responses without being charged.
    */
    'fail_silently' => env('VOLTA_FAIL_SILENTLY', false),

    /*
    |--------------------------------------------------------------------------
    | User Resolver
    |--------------------------------------------------------------------------
    | A callback that resolves the current user ID from the request.
    | Override this if your user IDs don't come from Auth::id().
    */
    'user_resolver' => null,
];
CODEBLOCK;
@endphp
    @include('docs.partials._code-block', ['language' => 'php', 'code' => $configCode])

    <h2>Configuration reference</h2>
    <table>
        <thead>
            <tr>
                <th>Key</th>
                <th>Default</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>app_key</code></td>
                <td><code>null</code></td>
                <td>Your Volta app key for API authentication</td>
            </tr>
            <tr>
                <td><code>base_url</code></td>
                <td><code>https://volta-main-7omebh.laravel.cloud</code></td>
                <td>Volta API endpoint URL</td>
            </tr>
            <tr>
                <td><code>verify_ssl</code></td>
                <td><code>true</code></td>
                <td>Enable/disable SSL certificate verification</td>
            </tr>
            <tr>
                <td><code>cache_balance_ttl</code></td>
                <td><code>30</code></td>
                <td>Seconds to cache user balances (0 to disable)</td>
            </tr>
            <tr>
                <td><code>fail_silently</code></td>
                <td><code>false</code></td>
                <td>Return false on failed charges instead of throwing</td>
            </tr>
            <tr>
                <td><code>user_resolver</code></td>
                <td><code>null</code></td>
                <td>Custom callback to resolve user ID from request</td>
            </tr>
        </tbody>
    </table>

    <h2>Environment variables</h2>
    <table>
        <thead>
            <tr>
                <th>Variable</th>
                <th>Required</th>
                <th>Example</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>VOLTA_APP_KEY</code></td>
                <td>Yes</td>
                <td><code>a1b2c3d4-e5f6-7890-abcd-ef1234567890</code></td>
            </tr>
            <tr>
                <td><code>VOLTA_BASE_URL</code></td>
                <td>No</td>
                <td><code>https://volta-main-7omebh.laravel.cloud</code></td>
            </tr>
            <tr>
                <td><code>VOLTA_VERIFY_SSL</code></td>
                <td>No</td>
                <td><code>false</code> (local only)</td>
            </tr>
            <tr>
                <td><code>VOLTA_CACHE_TTL</code></td>
                <td>No</td>
                <td><code>60</code></td>
            </tr>
            <tr>
                <td><code>VOLTA_FAIL_SILENTLY</code></td>
                <td>No</td>
                <td><code>true</code></td>
            </tr>
        </tbody>
    </table>

    @include('docs.partials._callout', ['type' => 'warning', 'title' => 'Caution with fail_silently', 'content' => 'Enabling <code>fail_silently</code> means failed charges won\'t throw exceptions — users may get AI responses without being charged. Only use this if your app can tolerate unbilled usage, for example during a beta period.'])
@endsection
