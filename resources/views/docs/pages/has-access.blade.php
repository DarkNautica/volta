@extends('docs.layout')

@section('content')
    <h2>Method signature</h2>
    @include('docs.partials._method-signature', ['method' => 'Volta::hasAccess', 'params' => 'string $userId, int $credits = 1', 'returns' => 'bool'])

    <h2>Parameters</h2>
    @include('docs.partials._params-table', ['params' => [
        ['name' => '$userId', 'type' => 'string', 'required' => true, 'default' => '—', 'description' => 'The unique identifier for the end-user'],
        ['name' => '$credits', 'type' => 'int', 'required' => false, 'default' => '1', 'description' => 'Number of credits required for the operation'],
    ]])

    <h2>Return value</h2>
    <p>Always returns a <code>bool</code>. Returns <code>true</code> if the user has enough credits and is within their rate limit. Returns <code>false</code> otherwise. <strong>Never throws exceptions.</strong></p>

    <h2>hasAccess vs charge</h2>
    <table>
        <thead>
            <tr>
                <th></th>
                <th><code>hasAccess()</code></th>
                <th><code>charge()</code></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Deducts credits</td>
                <td>No</td>
                <td>Yes</td>
            </tr>
            <tr>
                <td>Throws exceptions</td>
                <td>No</td>
                <td>Yes (unless fail_silently)</td>
            </tr>
            <tr>
                <td>Checks rate limit</td>
                <td>Yes</td>
                <td>Yes</td>
            </tr>
            <tr>
                <td>Use case</td>
                <td>Pre-flight check, UI rendering</td>
                <td>After successful AI call</td>
            </tr>
        </tbody>
    </table>

    <h2>Examples</h2>

    <h3>Gate check before an AI call</h3>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'if (! Volta::hasAccess($userId, 3)) {
    return response()->json([
        \'error\' => \'Cannot process request\',
        \'balance\' => Volta::balance($userId),
    ], 402);
}

// Safe to proceed
$response = AiService::chat($message);
Volta::charge($userId, 3, \'gpt-4o\');'])

    <h3>Conditional UI rendering</h3>
    @include('docs.partials._code-block', ['language' => 'html', 'code' => '@@voltaHasAccess($userId, 5)
    <button onclick="generateImage()">
        Generate Image (5 credits)
    </button>
@@endvoltaHasAccess

@@voltaNoAccess($userId, 5)
    <div class="text-gray-400">
        Not enough credits.
        <a href="@{{ Volta::portalUrl($userId) }}">Buy more</a>
    </div>
@@endvoltaNoAccess'])
@endsection
