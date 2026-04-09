<?php

namespace App\Http\Controllers;

use App\Services\VoltaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoltaApiController extends Controller
{
    public function __construct(protected VoltaService $volta)
    {
    }

    public function charge(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|string',
            'credits' => 'integer|min:1',
            'model' => 'nullable|string',
        ]);

        $this->volta->charge(
            $request->input('user_id'),
            $request->input('credits', 1),
            $request->input('model'),
        );

        return response()->json(['success' => true, 'message' => 'Credits charged.']);
    }

    public function balance(string $externalUserId): JsonResponse
    {
        return response()->json([
            'user_id' => $externalUserId,
            'balance' => $this->volta->balance($externalUserId),
        ]);
    }

    public function access(Request $request, string $externalUserId): JsonResponse
    {
        $credits = (int) $request->query('credits', 1);

        return response()->json([
            'user_id' => $externalUserId,
            'has_access' => $this->volta->hasAccess($externalUserId, $credits),
        ]);
    }

    public function topUp(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|string',
            'credits' => 'required|integer|min:1',
        ]);

        $this->volta->topUp(
            $request->input('user_id'),
            $request->input('credits'),
        );

        return response()->json(['success' => true, 'message' => 'Credits added.']);
    }

    public function usage(string $externalUserId): JsonResponse
    {
        return response()->json($this->volta->usage($externalUserId));
    }

    public function portalUrl(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|string',
            'options' => 'array',
        ]);

        $url = $this->volta->portalUrl(
            $request->input('user_id'),
            $request->input('options', []),
        );

        return response()->json(['url' => $url]);
    }
}
