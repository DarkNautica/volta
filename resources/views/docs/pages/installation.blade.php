@extends('docs.layout')

@section('content')
    <h2>Requirements</h2>
    <ul>
        <li>PHP 8.2 or higher</li>
        <li>Laravel 10, 11, 12, or 13</li>
        <li>Composer</li>
    </ul>

    <h2>Step 1: Install the package</h2>
    @include('docs.partials._code-block', ['language' => 'bash', 'code' => 'composer require darknautica/volta-php'])

    <h2>Step 2: Publish the config</h2>
    @include('docs.partials._code-block', ['language' => 'bash', 'code' => 'php artisan vendor:publish --tag=volta-config'])
    <p>This creates <code>config/volta.php</code> in your project.</p>

    <h2>Step 3: Add environment variables</h2>
    <p>Add these to your <code>.env</code> file. You can find your app key in the <a href="/dashboard/apps">Volta dashboard</a>.</p>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => 'VOLTA_APP_KEY=your-app-key-here
VOLTA_BASE_URL=https://volta.test/api/v1'])

    <h2>Step 4: Verify the installation</h2>
    @include('docs.partials._code-block', ['language' => 'bash', 'code' => 'php artisan volta:test'])
    <p>Expected output:</p>
    @include('docs.partials._code-block', ['language' => 'text', 'code' => '✓ Volta API is reachable
✓ App key is valid
✓ App: ChatBot Pro (anthropic)
✓ 3 models configured
✓ Ready to go!'])

    <h2>Troubleshooting</h2>

    <h3>"Could not reach Volta API"</h3>
    <ul>
        <li>Verify <code>VOLTA_BASE_URL</code> is correct and the server is running</li>
        <li>Verify <code>VOLTA_APP_KEY</code> is set and matches a valid app key in Volta</li>
        <li>Check that your server can make outbound HTTP requests</li>
    </ul>

    <h3>"Invalid app key"</h3>
    <p>Your app key may have been regenerated. Go to the <a href="/dashboard/apps">Apps dashboard</a> and copy the current key, or regenerate a new one from the app settings.</p>

    <h3>SSL errors on local development</h3>
    <p>If you're running Volta locally without HTTPS, add this to your <code>.env</code>:</p>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => 'VOLTA_VERIFY_SSL=false'])
    @include('docs.partials._callout', ['type' => 'warning', 'title' => 'Local development only', 'content' => 'Never disable SSL verification in production. Remove <code>VOLTA_VERIFY_SSL</code> from your production <code>.env</code>.'])
@endsection
