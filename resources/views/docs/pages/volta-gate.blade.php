@extends('docs.layout')

@section('content')
    <h2>What it does</h2>
    <p>The <code>volta.gate</code> middleware checks a user's credit balance and rate limit before a request reaches your controller. If the user doesn't have enough credits or is rate-limited, the middleware returns a <code>402</code> or <code>429</code> JSON response automatically.</p>
    <p>This eliminates the need for manual <code>hasAccess()</code> checks in every controller method.</p>

    <h2>Registration</h2>
    <p>The middleware is registered automatically by the Volta service provider. No manual setup is needed — just use it on your routes.</p>

    <h2>Usage on routes</h2>
    <h3>Basic — 1 credit, no model tracking</h3>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'Route::post(\'/chat\', [ChatController::class, \'send\'])
    ->middleware(\'volta.gate\');'])

    <h3>With credits parameter</h3>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// Require 3 credits per request
Route::post(\'/chat\', [ChatController::class, \'send\'])
    ->middleware(\'volta.gate:3\');'])

    <h3>With credits and model</h3>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// Require 3 credits, track as gpt-4 usage
Route::post(\'/chat/gpt4\', [ChatController::class, \'send\'])
    ->middleware(\'volta.gate:3,gpt-4\');'])

    <h2>Custom user ID resolution</h2>
    <p>By default, VoltaGate resolves the user ID from <code>Auth::id()</code>. To use a different identifier, set the <code>user_resolver</code> in your Volta config:</p>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// config/volta.php
\'user_resolver\' => function (Request $request) {
    return $request->header(\'X-User-ID\')
        ?? (string) $request->user()?->id;
},'])

    <h2>402 response format</h2>
    <p>When a user has insufficient credits, VoltaGate returns:</p>

    @include('docs.partials._code-block', ['language' => 'json', 'code' => '{
    "error": "insufficient_credits",
    "message": "You need 3 credits but only have 1.",
    "balance": 1,
    "required": 3
}'])

    <h2>Route group example</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// All AI routes require at least 1 credit
Route::middleware([\'auth\', \'volta.gate\'])->prefix(\'ai\')->group(function () {
    Route::post(\'/chat\', [AiController::class, \'chat\']);
    Route::post(\'/summarize\', [AiController::class, \'summarize\']);

    // Image generation costs more
    Route::post(\'/image\', [AiController::class, \'image\'])
        ->middleware(\'volta.gate:5,dall-e-3\');
});'])

    @include('docs.partials._callout', ['type' => 'info', 'title' => 'Note', 'content' => 'VoltaGate only <strong>checks</strong> access — it does not charge credits. You still need to call <code>Volta::charge()</code> in your controller after a successful AI response.'])
@endsection
