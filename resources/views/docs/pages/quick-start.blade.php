@extends('docs.layout')

@section('content')
    <p class="text-lg" style="color: #64748b;">From zero to billing in 5 minutes.</p>

    <h2>Complete working example</h2>
    <p>Here's a full controller that checks a user's access, calls an AI endpoint, and charges credits — all in one go.</p>

@php
$chatControllerCode = <<<'CODEBLOCK'
namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;
use Volta\Facades\Volta;
use Volta\Exceptions\InsufficientCreditsException;
use Volta\Exceptions\RateLimitExceededException;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $userId = (string) $request->user()->id;
        $model = $request->input('model', 'claude-3-5-sonnet');
        $creditsRequired = 2; // Credits for this model

        // 1. Check if the user has enough credits
        if (! Volta::hasAccess($userId, $creditsRequired)) {
            return response()->json([
                'error' => 'Insufficient credits',
                'balance' => Volta::balance($userId),
            ], 402);
        }

        // 2. Call your AI provider
        $response = AiService::chat(
            model: $model,
            message: $request->input('message'),
        );

        // 3. Charge the user (only after a successful AI call)
        Volta::charge($userId, $creditsRequired, $model);

        return response()->json([
            'message' => $response,
            'credits_remaining' => Volta::balance($userId),
        ]);
    }
}
CODEBLOCK;
@endphp
    @include('docs.partials._code-block', ['language' => 'php', 'code' => $chatControllerCode])

    <h2>Using the VoltaGate middleware</h2>
    <p>Instead of manually checking access, you can use the <code>volta.gate</code> middleware to automatically reject requests when a user has insufficient credits.</p>

@php
$middlewareCode = <<<'CODEBLOCK'
// routes/api.php
Route::middleware('volta.gate:2,claude-3-5-sonnet')->group(function () {
    Route::post('/chat', [ChatController::class, 'send']);
});

// The middleware automatically returns a 402 JSON response
// if the user doesn't have enough credits.
CODEBLOCK;
@endphp
    @include('docs.partials._code-block', ['language' => 'php', 'code' => $middlewareCode])

    <h2>Handling errors</h2>
@php
$errorCode = <<<'CODEBLOCK'
use Volta\Facades\Volta;
use Volta\Exceptions\InsufficientCreditsException;
use Volta\Exceptions\RateLimitExceededException;

try {
    Volta::charge($userId, 3, 'gpt-4o');
} catch (InsufficientCreditsException $e) {
    // User doesn't have enough credits
    return response()->json([
        'error' => 'insufficient_credits',
        'balance' => $e->getBalance(),
        'required' => $e->getRequired(),
    ], 402);
} catch (RateLimitExceededException $e) {
    // User has hit the rate limit
    return response()->json([
        'error' => 'rate_limited',
        'retry_after' => $e->getRetryAfter(),
    ], 429);
}
CODEBLOCK;
@endphp
    @include('docs.partials._code-block', ['language' => 'php', 'code' => $errorCode])

    <h2>Checking balance in Blade</h2>
    <p>Use the <code>@@voltaBalance</code> directive to display a user's credit balance directly in your templates.</p>

@php
$bladeCode = <<<'CODEBLOCK'
<div class="credit-display">
    <span>Credits: {{ Volta::balance($userId) }}</span>
</div>

@voltaHasAccess($userId, 5)
    <button>Generate Image (5 credits)</button>
@endvoltaHasAccess

@voltaNoAccess($userId, 5)
    <button disabled>Not enough credits</button>
    <a href="{{ Volta::portalUrl($userId) }}">Buy credits</a>
@endvoltaNoAccess
CODEBLOCK;
@endphp
    @include('docs.partials._code-block', ['language' => 'html', 'code' => $bladeCode])

    <h2>What's next?</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <a href="/docs/charge" class="block p-5 rounded-lg transition-all hover:scale-[1.01]" style="border: 1px solid #e2e8f0; text-decoration: none;">
            <p class="font-semibold mb-1" style="color: #0a0a1a;">SDK Reference &rarr;</p>
            <p class="text-sm" style="color: #64748b; margin: 0;">Explore every method in the Volta SDK.</p>
        </a>
        <a href="/docs/stripe-setup" class="block p-5 rounded-lg transition-all hover:scale-[1.01]" style="border: 1px solid #e2e8f0; text-decoration: none;">
            <p class="font-semibold mb-1" style="color: #0a0a1a;">Stripe Setup &rarr;</p>
            <p class="text-sm" style="color: #64748b; margin: 0;">Connect Stripe for real credit purchases.</p>
        </a>
    </div>
@endsection
