@extends('docs.layout')

@section('content')
    <h2>Method signature</h2>
    @include('docs.partials._method-signature', ['method' => 'Volta::topUp', 'params' => 'string $userId, int $credits', 'returns' => 'bool'])

    <h2>Parameters</h2>
    @include('docs.partials._params-table', ['params' => [
        ['name' => '$userId', 'type' => 'string', 'required' => true, 'default' => '—', 'description' => 'The unique identifier for the end-user'],
        ['name' => '$credits', 'type' => 'int', 'required' => true, 'default' => '—', 'description' => 'Number of credits to add to the user\'s balance'],
    ]])

    <h2>Return value</h2>
    <p>Returns <code>true</code> on success. Throws <code>VoltaApiException</code> on failure.</p>

    <h2>When to use topUp</h2>
    <p>In most cases, you won't call <code>topUp()</code> directly — it's called automatically by the Stripe webhook handler when a user purchases credits through the embeddable portal.</p>
    <p>Use it manually for:</p>
    <ul>
        <li><strong>Refunds</strong> — restore credits after a support request</li>
        <li><strong>Promotional credits</strong> — give users free credits for signing up, referrals, etc.</li>
        <li><strong>Testing</strong> — seed test users with credits during development</li>
        <li><strong>Custom purchase flows</strong> — if you handle payments outside the Volta portal</li>
    </ul>

    <h2>Example</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'use Volta\Facades\Volta;

// Add 100 credits to a user (e.g., promotional bonus)
Volta::topUp(\'user_123\', 100);

// In a Stripe webhook handler
public function handleCheckoutCompleted(array $payload)
{
    $userId = $payload[\'metadata\'][\'user_id\'];
    $credits = $payload[\'metadata\'][\'credits\'];

    Volta::topUp($userId, (int) $credits);
}'])
@endsection
