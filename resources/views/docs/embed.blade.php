@extends('layouts.app')

@section('title', 'Embed Billing Portal')

@section('content')
    <div class="max-w-3xl">
        <h1 class="text-4xl mb-2" style="color: var(--text);">Embed Billing Portal</h1>
        <p class="mb-8" style="color: var(--muted);">Let your end-users manage credits directly inside your app.</p>

        {{-- Step 1: Generate URL --}}
        <div class="rounded-xl p-6 mb-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <h2 class="text-xl mb-4" style="color: var(--text);">1. Generate a signed portal URL (server-side)</h2>
            <pre class="rounded-lg p-4 overflow-x-auto text-sm" style="background-color: var(--surface2); color: var(--text);"><code>// Using the Volta API
$response = Http::withHeaders([
    'X-Volta-Key' => 'your-app-key',
])->get('{{ url('/api/v1/portal-url') }}', [
    'user_id' => $yourUserId,
    'options' => [
        'theme' => 'dark',           // or 'light'
        'return_url' => 'https://yourapp.com/billing',
        'credits_packages' => [
            [100, 499],   // 100 credits for $4.99
            [500, 1999],  // 500 credits for $19.99
            [1000, 3499], // 1000 credits for $34.99
        ],
    ],
]);

$portalUrl = $response->json('url');</code></pre>
        </div>

        {{-- Step 2: Embed --}}
        <div class="rounded-xl p-6 mb-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <h2 class="text-xl mb-4" style="color: var(--text);">2. Embed in your frontend</h2>

            <h3 class="text-sm font-semibold mb-2" style="color: var(--accent);">Option A: iframe</h3>
            <pre class="rounded-lg p-4 overflow-x-auto text-sm mb-6" style="background-color: var(--surface2); color: var(--text);"><code>&lt;iframe
    src="@{{ $portalUrl }}"
    width="100%"
    height="600"
    frameborder="0"
    style="border-radius: 12px;"
&gt;&lt;/iframe&gt;</code></pre>

            <h3 class="text-sm font-semibold mb-2" style="color: var(--accent);">Option B: Full page redirect</h3>
            <pre class="rounded-lg p-4 overflow-x-auto text-sm" style="background-color: var(--surface2); color: var(--text);"><code>&lt;a href="@{{ $portalUrl }}"&gt;Manage credits&lt;/a&gt;</code></pre>
        </div>

        {{-- Notes --}}
        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <h2 class="text-xl mb-4" style="color: var(--text);">Notes</h2>
            <ul class="space-y-2 text-sm" style="color: var(--muted);">
                <li>Portal URLs expire after <strong style="color: var(--text);">2 hours</strong>. Generate a fresh one each time.</li>
                <li>The portal is <strong style="color: var(--text);">white-label</strong> by default — no Volta branding is shown.</li>
                <li>Supports <strong style="color: var(--text);">dark</strong> and <strong style="color: var(--text);">light</strong> themes via the options parameter.</li>
                <li>Credit packages default to your app's configured packages if not passed.</li>
            </ul>
        </div>
    </div>
@endsection
