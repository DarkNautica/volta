<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request, StripeService $stripeService): JsonResponse
    {
        $stripeService->handleWebhook($request);

        return response()->json(['received' => true]);
    }
}
