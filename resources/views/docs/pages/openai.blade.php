@extends('docs.layout')

@section('content')
    <h2>Overview</h2>
    <p>Volta integrates with OpenAI's GPT models for credit-based billing. This guide covers setup and a complete working example.</p>

    <h2>Setup</h2>
    @include('docs.partials._code-block', ['language' => 'bash', 'code' => 'composer require openai-php/client'])

    <p>Add your OpenAI API key to <code>.env</code>:</p>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => 'OPENAI_API_KEY=sk-your-key-here'])

    <h2>Model pricing</h2>
    <table>
        <thead>
            <tr>
                <th>Model</th>
                <th>Suggested Credits</th>
            </tr>
        </thead>
        <tbody>
            <tr><td><code>gpt-4o</code></td><td>3</td></tr>
            <tr><td><code>gpt-4o-mini</code></td><td>1</td></tr>
            <tr><td><code>gpt-3.5-turbo</code></td><td>1</td></tr>
            <tr><td><code>dall-e-3</code></td><td>5</td></tr>
        </tbody>
    </table>

    <h2>Complete example</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'use OpenAI;
use Volta\Facades\Volta;

class GptController extends Controller
{
    public function chat(Request $request)
    {
        $userId = (string) $request->user()->id;
        $model = $request->input(\'model\', \'gpt-4o\');
        $credits = config("models.{$model}.credits", 3);

        if (! Volta::hasAccess($userId, $credits)) {
            return response()->json([\'error\' => \'insufficient_credits\'], 402);
        }

        $client = OpenAI::client(config(\'services.openai.key\'));

        $response = $client->chat()->create([
            \'model\' => $model,
            \'messages\' => [
                [\'role\' => \'user\', \'content\' => $request->input(\'message\')],
            ],
        ]);

        Volta::charge($userId, $credits, $model);

        return response()->json([
            \'message\' => $response->choices[0]->message->content,
            \'credits_remaining\' => Volta::balance($userId),
        ]);
    }
}'])

    <h2>Image generation</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// DALL-E 3 costs more credits
$credits = 5;

if (! Volta::hasAccess($userId, $credits)) {
    return response()->json([\'error\' => \'insufficient_credits\'], 402);
}

$response = $client->images()->create([
    \'model\' => \'dall-e-3\',
    \'prompt\' => $request->input(\'prompt\'),
    \'n\' => 1,
    \'size\' => \'1024x1024\',
]);

Volta::charge($userId, $credits, \'dall-e-3\');'])
@endsection
