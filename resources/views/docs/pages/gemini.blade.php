@extends('docs.layout')

@section('content')
    <h2>Overview</h2>
    <p>Volta supports Google's Gemini models. This guide covers integrating Volta billing with the Gemini API.</p>

    <h2>Setup</h2>
    @include('docs.partials._code-block', ['language' => 'bash', 'code' => 'composer require google-gemini-php/client'])

    <p>Add your Gemini API key to <code>.env</code>:</p>
    @include('docs.partials._code-block', ['language' => 'env', 'code' => 'GEMINI_API_KEY=your-gemini-key-here'])

    <h2>Model pricing</h2>
    <table>
        <thead>
            <tr>
                <th>Model</th>
                <th>Suggested Credits</th>
            </tr>
        </thead>
        <tbody>
            <tr><td><code>gemini-pro</code></td><td>1</td></tr>
            <tr><td><code>gemini-1.5-pro</code></td><td>2</td></tr>
            <tr><td><code>gemini-ultra</code></td><td>4</td></tr>
        </tbody>
    </table>

    <h2>Complete example</h2>
    @include('docs.partials._code-block', ['language' => 'php', 'code' => 'use Gemini;
use Volta\Facades\Volta;

class GeminiController extends Controller
{
    public function chat(Request $request)
    {
        $userId = (string) $request->user()->id;
        $model = \'gemini-pro\';
        $credits = 1;

        if (! Volta::hasAccess($userId, $credits)) {
            return response()->json([\'error\' => \'insufficient_credits\'], 402);
        }

        $client = Gemini::client(config(\'services.gemini.key\'));

        $response = $client->geminiPro()->generateContent(
            $request->input(\'message\')
        );

        Volta::charge($userId, $credits, $model);

        return response()->json([
            \'message\' => $response->text(),
            \'credits_remaining\' => Volta::balance($userId),
        ]);
    }
}'])

    @include('docs.partials._callout', ['type' => 'info', 'title' => 'Gemini free tier', 'content' => 'Gemini offers a generous free tier. You may want to set lower credit costs for Gemini models compared to OpenAI or Anthropic to reflect your actual API costs.'])
@endsection
