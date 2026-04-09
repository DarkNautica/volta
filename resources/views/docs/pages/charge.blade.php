@extends('docs.layout')

@section('content')
    <h2>Method signature</h2>
    @include('docs.partials._method-signature', ['method' => 'Volta::charge', 'params' => 'string $userId, int $credits = 1, ?string $model = null', 'returns' => 'bool'])

    <h2>Parameters</h2>
    @include('docs.partials._params-table', ['params' => [
        ['name' => '$userId', 'type' => 'string', 'required' => true, 'default' => '—', 'description' => 'The unique identifier for the end-user being charged'],
        ['name' => '$credits', 'type' => 'int', 'required' => false, 'default' => '1', 'description' => 'Number of credits to deduct'],
        ['name' => '$model', 'type' => '?string', 'required' => false, 'default' => 'null', 'description' => 'AI model identifier for analytics tracking'],
    ]])

    <h2>Return value</h2>
    <p>Returns <code>true</code> on success. If <code>fail_silently</code> is enabled in config, returns <code>false</code> on failure. Otherwise, throws an exception.</p>

    <h2>Exceptions</h2>
    <ul>
        <li><code>InsufficientCreditsException</code> — the user doesn't have enough credits</li>
        <li><code>RateLimitExceededException</code> — the user has exceeded their rate limit</li>
        <li><code>VoltaApiException</code> — network or server error communicating with Volta</li>
    </ul>

    <h2>Examples</h2>
    <h3>Basic usage</h3>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'Volta::charge(\'user_123\');'])

    <h3>With a model</h3>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'Volta::charge(\'user_123\', 3, \'gpt-4o\');'])

    <h3>Inside a try/catch</h3>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'use Volta\Facades\Volta;
use Volta\Exceptions\InsufficientCreditsException;

try {
    $response = AiService::chat($message);
    Volta::charge($userId, 2, \'claude-3-5-sonnet\');

    return response()->json([\'message\' => $response]);
} catch (InsufficientCreditsException $e) {
    return response()->json([
        \'error\' => \'insufficient_credits\',
        \'balance\' => $e->getBalance(),
        \'required\' => $e->getRequired(),
    ], 402);
}'])

    @include('docs.partials._callout', ['type' => 'info', 'title' => 'Important', 'content' => 'Credits are only deducted when you explicitly call <code>charge()</code>. A failed AI call does not automatically deduct credits — you control exactly when charges happen.'])
@endsection
