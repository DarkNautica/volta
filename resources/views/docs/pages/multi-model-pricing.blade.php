@extends('docs.layout')

@section('content')
    <h2>What is multi-model pricing?</h2>
    <p>Different AI models have different costs. GPT-4 is more expensive than GPT-3.5, and Claude Opus costs more than Claude Haiku. Volta lets you assign a different credit cost to each model so your pricing reflects your actual costs.</p>
    <p>This means a user with 100 credits might get 100 GPT-3.5 calls (1 credit each) or 33 GPT-4 calls (3 credits each). Fair pricing for everyone.</p>

    <h2>Registering models</h2>
    <p>Add models to your app in the <a href="/dashboard">Volta dashboard</a> under the <strong>Models</strong> tab. Each model needs:</p>
    <ul>
        <li><strong>Model Identifier</strong> — the provider's model ID (e.g., <code>gpt-4o</code>, <code>claude-3-5-sonnet</code>)</li>
        <li><strong>Display Name</strong> — a human-readable name for the dashboard</li>
        <li><strong>Credits Per Call</strong> — how many credits each call to this model costs</li>
    </ul>

    <p>Example configuration:</p>
    <table>
        <thead>
            <tr>
                <th>Model</th>
                <th>Display Name</th>
                <th>Credits/Call</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>gpt-3.5-turbo</code></td>
                <td>GPT-3.5 Turbo</td>
                <td>1</td>
            </tr>
            <tr>
                <td><code>gpt-4o</code></td>
                <td>GPT-4o</td>
                <td>3</td>
            </tr>
            <tr>
                <td><code>claude-3-5-sonnet</code></td>
                <td>Claude 3.5 Sonnet</td>
                <td>2</td>
            </tr>
            <tr>
                <td><code>claude-3-opus</code></td>
                <td>Claude 3 Opus</td>
                <td>5</td>
            </tr>
        </tbody>
    </table>

    <h2>Charging with a model</h2>
    <p>Pass the model identifier as the third argument to <code>Volta::charge()</code>:</p>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => '// Charge 3 credits for a GPT-4o call
Volta::charge($userId, 3, \'gpt-4o\');

// Charge 1 credit for a GPT-3.5 call
Volta::charge($userId, 1, \'gpt-3.5-turbo\');'])

    @include('docs.partials._callout', ['type' => 'tip', 'title' => 'Tip', 'content' => 'The model identifier is used for analytics and tracking in the dashboard. It doesn\'t validate against your AI provider — you control the credit amount in your code.'])

    <h2>Dynamic model pricing</h2>
    <p>For a more maintainable approach, look up the credit cost from the model configuration instead of hardcoding it:</p>

    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'use Volta\Facades\Volta;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $model = $request->input(\'model\', \'gpt-3.5-turbo\');
        $userId = (string) $request->user()->id;

        // Look up credits from your model config
        $credits = config("models.{$model}.credits", 1);

        if (! Volta::hasAccess($userId, $credits)) {
            return response()->json([\'error\' => \'insufficient_credits\'], 402);
        }

        $response = AiService::chat($model, $request->input(\'message\'));

        Volta::charge($userId, $credits, $model);

        return response()->json([
            \'message\' => $response,
            \'credits_used\' => $credits,
            \'balance\' => Volta::balance($userId),
        ]);
    }
}'])
@endsection
