<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\AppModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $apps = $request->user()->apps()->withCount('appUsers', 'usageLogs')->get();

        return response()->json($apps);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ai_provider' => 'required|in:anthropic,openai,gemini',
            'rate_limit_per_hour' => 'integer|min:1|max:10000',
        ]);

        $plan = $request->user()->plan;
        $limit = config("volta.plans.{$plan}.app_limit");
        if ($limit !== null && $request->user()->apps()->count() >= $limit) {
            return response()->json(['error' => 'App limit reached for your plan.'], 403);
        }

        $app = $request->user()->apps()->create($validated);

        return response()->json($app, 201);
    }

    public function show(Request $request, App $app): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $app->loadCount('appUsers', 'usageLogs', 'creditTransactions');
        $app->load('appModels');

        return response()->json($app);
    }

    public function destroy(Request $request, App $app): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $app->delete();

        return response()->json(['message' => 'App deleted.']);
    }

    public function users(Request $request, App $app): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $users = $app->appUsers()
            ->withCount('usageLogs')
            ->paginate(20);

        return response()->json($users);
    }

    public function stats(Request $request, App $app): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        return response()->json([
            'total_users' => $app->appUsers()->count(),
            'total_credits_sold' => $app->creditTransactions()->where('type', 'purchase')->sum('amount'),
            'total_api_calls' => $app->usageLogs()->count(),
            'credits_used_today' => $app->usageLogs()->whereDate('created_at', today())->sum('credits_charged'),
        ]);
    }

    public function storeModel(Request $request, App $app): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'model_identifier' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'credits_per_call' => 'integer|min:1',
        ]);

        $model = $app->appModels()->create($validated);

        return response()->json($model, 201);
    }

    public function destroyModel(Request $request, App $app, AppModel $appModel): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);
        abort_unless($appModel->app_id === $app->id, 404);

        $appModel->delete();

        return response()->json(['message' => 'Model removed.']);
    }
}
