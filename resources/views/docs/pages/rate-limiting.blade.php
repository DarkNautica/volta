@extends('docs.layout')

@section('content')
    <h2>How rate limiting works</h2>
    <p>Volta enforces per-user rate limits on API calls to protect your AI provider from abuse. Each app has a configurable <code>rate_limit_per_hour</code> that applies to every end-user individually.</p>
    <p>When a user exceeds their rate limit, Volta returns a <code>429</code> response and the SDK throws a <code>RateLimitExceededException</code>.</p>

    <h2>Configuring rate limits</h2>
    <p>Set the rate limit per app in the Volta dashboard under <strong>App Settings</strong>. The default is 60 requests per hour.</p>
    <p>You can also set it programmatically when creating or updating an app:</p>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// In your Volta dashboard app settings:
// Rate Limit Per Hour: 120
//
// Or via the API:
$app->update([\'rate_limit_per_hour\' => 120]);'])

    <h2>429 response format</h2>
    <p>When a user hits the rate limit, the API returns this JSON response:</p>

    @include('docs.partials._code-block', ['language' => 'json', 'code' => '{
    "error": "rate_limit_exceeded",
    "message": "Rate limit of 60 requests per hour exceeded.",
    "retry_after": 142,
    "limit": 60,
    "remaining": 0,
    "reset_at": "2026-04-09T12:30:00Z"
}'])

    <h2>Soft vs hard limits</h2>
    <p><strong>Hard limits</strong> (default) reject requests immediately when the limit is hit. The user must wait until the rate window resets.</p>
    <p><strong>Soft limits</strong> log the overage but still allow the request through. This is useful for monitoring without blocking users. You can enable soft limits per-app in the dashboard.</p>

    @include('docs.partials._callout', ['type' => 'info', 'title' => 'Rate limit windows', 'content' => 'Rate limits use a sliding window. If the limit is 60/hour, Volta counts requests in the last 60 minutes from the current time — not from the top of the hour.'])

    <h2>Catching rate limit errors</h2>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'use Volta\Facades\Volta;
use Volta\Exceptions\RateLimitExceededException;

try {
    Volta::charge($userId, 2, \'gpt-4o\');
} catch (RateLimitExceededException $e) {
    return response()->json([
        \'error\' => \'rate_limited\',
        \'retry_after\' => $e->getRetryAfter(),
        \'message\' => "Please wait {$e->getRetryAfter()} seconds.",
    ], 429);
}'])

    <p>Alternatively, check access before calling the AI provider:</p>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'if (! Volta::hasAccess($userId)) {
    return response()->json([\'error\' => \'rate_limited\'], 429);
}

// Safe to proceed — user is within rate limits and has credits
$response = AiService::chat($message);
Volta::charge($userId, 2);'])
@endsection
