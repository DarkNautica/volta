@extends('docs.layout')

@section('content')
    <h2>Method signature</h2>
    @include('docs.partials._method-signature', ['method' => 'Volta::balance', 'params' => 'string $userId', 'returns' => 'int'])

    <h2>Parameters</h2>
    @include('docs.partials._params-table', ['params' => [
        ['name' => '$userId', 'type' => 'string', 'required' => true, 'default' => '—', 'description' => 'The unique identifier for the end-user'],
    ]])

    <h2>Return value</h2>
    <p>Returns the user's current credit balance as an integer. Returns <code>0</code> if the user has no credits or doesn't exist yet.</p>

    <h2>Caching behavior</h2>
    <p>Balances are cached for the duration specified in <code>cache_balance_ttl</code> (default: 30 seconds). This reduces API calls while keeping balances reasonably fresh.</p>
    <p>The cache is automatically invalidated when you call <code>charge()</code> or <code>topUp()</code> for the same user.</p>

    @include('docs.partials._callout', ['type' => 'tip', 'title' => 'Adjusting cache TTL', 'content' => 'Set <code>VOLTA_CACHE_TTL=0</code> in your <code>.env</code> to disable caching entirely. This is useful during development but not recommended in production due to increased API calls.'])

    <h2>Examples</h2>
    <h3>Display in a controller</h3>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'public function dashboard(Request $request)
{
    $userId = (string) $request->user()->id;

    return view(\'dashboard\', [
        \'credits\' => Volta::balance($userId),
    ]);
}'])

    <h3>Display in Blade with the directive</h3>
    @include('docs.partials._code-block', ['language' => 'html', 'code' => '<div class="credits-badge">
    <span>Credits remaining:</span>
    <strong>@@voltaBalance($userId)</strong>
</div>'])
@endsection
