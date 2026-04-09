@extends('docs.layout')

@section('content')
    <h2>Overview</h2>
    <p>The embeddable portal is a white-label billing interface that lets your end-users view their credit balance, purchase credit packages, and see transaction history — all without leaving your app.</p>
    <p>It supports both <strong>iframe embedding</strong> and <strong>full-page redirect</strong> modes, with dark and light themes.</p>

    <h2>How it works</h2>
    <ol>
        <li>Your server generates a signed portal URL via <code>Volta::portalUrl()</code></li>
        <li>You embed that URL in an iframe or redirect the user to it</li>
        <li>The user sees their balance and can purchase credit packages</li>
        <li>Purchases are processed via Stripe Checkout</li>
        <li>Credits are added automatically via webhooks</li>
    </ol>

    <h2>Quick setup</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// Controller
public function billing(Request $request)
{
    return view(\'billing\', [
        \'portalUrl\' => Volta::portalUrl(
            (string) $request->user()->id,
            [
                \'theme\' => \'dark\',
                \'return_url\' => route(\'billing\'),
                \'credits_packages\' => [
                    [100, 499],    // 100 credits for $4.99
                    [500, 1999],   // 500 credits for $19.99
                    [1000, 3499],  // 1,000 credits for $34.99
                ],
            ]
        ),
    ]);
}'])

    @include('docs.partials._code-block', ['language' => 'html', 'code' => '<!-- Blade template -->
<div class="billing-container">
    <h2>Manage Credits</h2>
    <iframe
        src="{{ $portalUrl }}"
        width="100%"
        height="600"
        frameborder="0"
        style="border-radius: 12px;"
    ></iframe>
</div>'])

    <h2>Theming</h2>
    <p>The portal supports two themes:</p>
    <ul>
        <li><code>'dark'</code> — dark background, light text. Best for dark-themed apps.</li>
        <li><code>'light'</code> — white background, dark text. Best for light-themed apps.</li>
    </ul>

    <h2>Security</h2>
    <p>Portal URLs are cryptographically signed and expire after 2 hours. They cannot be tampered with — if any parameter is modified, the signature check fails and a 403 is returned.</p>

    @include('docs.partials._callout', ['type' => 'tip', 'title' => 'No Volta branding', 'content' => 'The portal is white-label by default. No Volta branding is shown to your end-users — it looks like a native part of your application.'])
@endsection
