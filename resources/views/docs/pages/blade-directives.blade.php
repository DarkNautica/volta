@extends('docs.layout')

@section('content')
    <p>Volta registers several Blade directives for working with credits directly in your templates.</p>

    <h2>@@voltaBalance($userId)</h2>
    <p>Outputs the user's current credit balance as a plain integer.</p>
    @include('docs.partials._code-block', ['language' => 'html', 'code' => '<div class="credit-badge">
    <svg>...</svg>
    <span>@@voltaBalance($userId) credits</span>
</div>

<!-- Renders as: -->
<div class="credit-badge">
    <svg>...</svg>
    <span>247 credits</span>
</div>'])

    <h2>@@voltaHasAccess / @@endvoltaHasAccess</h2>
    <p>Conditionally renders content only if the user has enough credits.</p>
    @include('docs.partials._code-block', ['language' => 'html', 'code' => '@@voltaHasAccess($userId, 5)
    <form action="/ai/generate" method="POST">
        @@csrf
        <textarea name="prompt" rows="4" placeholder="Describe your image..."></textarea>
        <button type="submit" class="btn-primary">
            Generate Image
            <span class="text-sm opacity-70">(5 credits)</span>
        </button>
    </form>
@@endvoltaHasAccess'])

    <h2>@@voltaNoAccess / @@endvoltaNoAccess</h2>
    <p>The inverse — renders content only when the user does <strong>not</strong> have enough credits.</p>
    @include('docs.partials._code-block', ['language' => 'html', 'code' => '@@voltaNoAccess($userId, 5)
    <div class="rounded-lg border p-6 text-center">
        <p class="text-gray-500 mb-3">You need 5 credits to generate images.</p>
        <a href="@@voltaPortalUrl($userId)" class="btn-primary">
            Buy Credits
        </a>
    </div>
@@endvoltaNoAccess'])

    <h2>@@voltaPortalUrl($userId)</h2>
    <p>Outputs a signed portal URL for the given user. Useful for inline links and iframe embeds.</p>
    @include('docs.partials._code-block', ['language' => 'html', 'code' => '<!-- As a link -->
<a href="@@voltaPortalUrl($userId)">Manage Credits</a>

<!-- As an iframe embed -->
<iframe
    src="@@voltaPortalUrl($userId)"
    width="100%"
    height="600"
    frameborder="0"
    style="border-radius: 12px; border: 1px solid #e5e7eb;"
></iframe>'])

    @include('docs.partials._callout', ['type' => 'warning', 'title' => 'Portal URL caching', 'content' => 'Portal URLs expire after 2 hours. If you\'re caching the view that contains <code>@@voltaPortalUrl</code>, make sure the cache TTL is shorter than the URL expiry.'])
@endsection
