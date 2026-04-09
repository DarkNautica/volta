@extends('docs.layout')

@section('content')
    <h2>Overview</h2>
    <p>Volta works seamlessly with Anthropic's Claude models. This guide shows how to integrate Volta billing with the Anthropic SDK.</p>

    <h2>Setup</h2>
    @include('docs.partials._code-block', ['language' => 'bash', 'code' => 'composer require anthropic/anthropic-php'])

    <p>Add your Anthropic API key to <code>.env</code>:</p>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => 'ANTHROPIC_API_KEY=sk-ant-your-key-here'])

    <h2>Model pricing</h2>
    <p>Recommended credit costs per model (configure in the Volta dashboard):</p>
    <table>
        <thead>
            <tr>
                <th>Model</th>
                <th>Suggested Credits</th>
            </tr>
        </thead>
        <tbody>
            <tr><td><code>claude-3-5-sonnet</code></td><td>2</td></tr>
            <tr><td><code>claude-3-opus</code></td><td>5</td></tr>
            <tr><td><code>claude-3-haiku</code></td><td>1</td></tr>
        </tbody>
    </table>

    <h2>Complete example</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'use Anthropic\Anthropic;
use Volta\Facades\Volta;

class ClaudeController extends Controller
{
    public function chat(Request $request)
    {
        $userId = (string) $request->user()->id;
        $model = \'claude-3-5-sonnet\';
        $credits = 2;

        if (! Volta::hasAccess($userId, $credits)) {
            return response()->json([\'error\' => \'insufficient_credits\'], 402);
        }

        $client = Anthropic::client(config(\'services.anthropic.key\'));

        $response = $client->messages()->create([
            \'model\' => $model,
            \'max_tokens\' => 1024,
            \'messages\' => [
                [\'role\' => \'user\', \'content\' => $request->input(\'message\')],
            ],
        ]);

        // Charge only after successful response
        Volta::charge($userId, $credits, $model);

        return response()->json([
            \'message\' => $response->content[0]->text,
            \'credits_remaining\' => Volta::balance($userId),
        ]);
    }
}'])
@endsection
