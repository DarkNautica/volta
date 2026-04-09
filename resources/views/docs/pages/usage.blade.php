@extends('docs.layout')

@section('content')
    <h2>Method signature</h2>
    @include('docs.partials._method-signature', ['method' => 'Volta::usage', 'params' => 'string $userId', 'returns' => 'array'])

    <h2>Parameters</h2>
    @include('docs.partials._params-table', ['params' => [
        ['name' => '$userId', 'type' => 'string', 'required' => true, 'default' => '—', 'description' => 'The unique identifier for the end-user'],
    ]])

    <h2>Response structure</h2>
    <p>Returns an associative array with the user's usage statistics:</p>

    @include('docs.partials._code-block', ['language' => 'json', 'code' => '{
    "external_user_id": "user_123",
    "credit_balance": 247,
    "total_calls": 1583,
    "total_credits_used": 3891,
    "calls_today": 42
}'])

    <table>
        <thead>
            <tr>
                <th>Key</th>
                <th>Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>external_user_id</code></td>
                <td><code>string</code></td>
                <td>The user ID you passed in</td>
            </tr>
            <tr>
                <td><code>credit_balance</code></td>
                <td><code>int</code></td>
                <td>Current credit balance</td>
            </tr>
            <tr>
                <td><code>total_calls</code></td>
                <td><code>int</code></td>
                <td>Total API calls made by this user (all time)</td>
            </tr>
            <tr>
                <td><code>total_credits_used</code></td>
                <td><code>int</code></td>
                <td>Total credits consumed (all time)</td>
            </tr>
            <tr>
                <td><code>calls_today</code></td>
                <td><code>int</code></td>
                <td>Number of API calls made today (UTC)</td>
            </tr>
        </tbody>
    </table>

    <h2>Example</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'use Volta\Facades\Volta;

$stats = Volta::usage(\'user_123\');

return view(\'user.profile\', [
    \'credits\' => $stats[\'credit_balance\'],
    \'totalCalls\' => $stats[\'total_calls\'],
    \'callsToday\' => $stats[\'calls_today\'],
]);'])
@endsection
