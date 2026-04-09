@extends('docs.layout')

@section('content')
    <h2>Method signature</h2>
    @include('docs.partials._method-signature', ['method' => 'Volta::portalUrl', 'params' => 'string $userId, array $options = []', 'returns' => 'string'])

    <h2>Parameters</h2>
    @include('docs.partials._params-table', ['params' => [
        ['name' => '$userId', 'type' => 'string', 'required' => true, 'default' => '—', 'description' => 'The unique identifier for the end-user'],
        ['name' => '$options', 'type' => 'array', 'required' => false, 'default' => '[]', 'description' => 'Optional configuration for the portal session'],
    ]])

    <h2>Options</h2>
    <table>
        <thead>
            <tr>
                <th>Key</th>
                <th>Type</th>
                <th>Default</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>return_url</code></td>
                <td><code>string</code></td>
                <td>Current page</td>
                <td>URL to redirect to after a purchase</td>
            </tr>
            <tr>
                <td><code>theme</code></td>
                <td><code>string</code></td>
                <td><code>'dark'</code></td>
                <td><code>'dark'</code> or <code>'light'</code></td>
            </tr>
            <tr>
                <td><code>credits_packages</code></td>
                <td><code>array</code></td>
                <td>App defaults</td>
                <td>Override credit packages for this session</td>
            </tr>
        </tbody>
    </table>

    <h2>Embedding via iframe</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// Controller
public function billing(Request $request)
{
    $userId = (string) $request->user()->id;

    return view(\'billing\', [
        \'portalUrl\' => Volta::portalUrl($userId, [
            \'theme\' => \'light\',
            \'return_url\' => route(\'billing\'),
        ]),
    ]);
}'])

    @include('docs.partials._code-block', ['language' => 'html', 'code' => '<!-- Blade template -->
<iframe
    src="{{ $portalUrl }}"
    width="100%"
    height="600"
    frameborder="0"
    style="border-radius: 12px;"
></iframe>'])

    <h2>Embedding via redirect</h2>
    @include('docs.partials._code-block', ['language' => 'html', 'code' => '<a href="{{ $portalUrl }}" class="btn btn-primary">
    Manage Credits
</a>'])

    <h2>Custom credit packages</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => '$url = Volta::portalUrl($userId, [
    \'credits_packages\' => [
        [50, 299],     // 50 credits for $2.99
        [200, 999],    // 200 credits for $9.99
        [500, 1999],   // 500 credits for $19.99
    ],
]);'])

    @include('docs.partials._callout', ['type' => 'info', 'title' => 'Portal URL expiry', 'content' => 'Portal URLs are signed and expire after 2 hours. Generate a fresh URL each time the user visits the billing page.'])
@endsection
