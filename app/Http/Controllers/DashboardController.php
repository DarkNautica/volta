<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\AppModel;
use App\Models\AppUser;
use App\Models\CreditTransaction;
use App\Models\UsageLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $appIds = $user->apps()->pluck('id');

        $totalApps = $appIds->count();
        $totalEndUsers = AppUser::whereIn('app_id', $appIds)->count();
        $totalApiCallsThisMonth = UsageLog::whereIn('app_id', $appIds)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $creditsSoldThisMonth = CreditTransaction::whereIn('app_id', $appIds)
            ->where('type', 'purchase')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return view('dashboard.index', [
            'totalApps' => $totalApps,
            'totalEndUsers' => $totalEndUsers,
            'totalApiCalls' => $totalApiCallsThisMonth,
            'mrr' => $creditsSoldThisMonth / 100,
        ]);
    }

    public function apps(Request $request): View
    {
        $apps = $request->user()->apps()
            ->withCount('appUsers')
            ->withCount(['usageLogs as api_calls_today_count' => fn ($q) => $q->whereDate('created_at', today())])
            ->get();

        return view('apps.index', compact('apps'));
    }

    public function createApp(): View
    {
        return view('apps.create');
    }

    public function storeApp(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ai_provider' => 'required|in:anthropic,openai,gemini',
            'rate_limit_per_hour' => 'integer|min:1|max:10000',
        ]);

        $request->user()->apps()->create($validated);

        return redirect('/dashboard/apps')->with('success', 'App created successfully.');
    }

    public function showApp(Request $request, App $app): View
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $app->loadCount('appUsers', 'usageLogs', 'creditTransactions');
        $app->load('appModels');
        $app->setRelation('appUsers', $app->appUsers()->withCount('usageLogs')->get());

        $stats = [
            'total_users' => $app->app_users_count,
            'credits_sold' => $app->creditTransactions()->where('type', 'purchase')->sum('amount'),
            'api_calls' => $app->usage_logs_count,
            'rate_limit_alerts' => 0,
        ];

        return view('apps.show', compact('app', 'stats'));
    }

    public function storeAppModel(Request $request, App $app)
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'model_identifier' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'credits_per_call' => 'integer|min:1',
        ]);

        $app->appModels()->create($validated);

        return redirect("/dashboard/apps/{$app->id}")->with('success', 'Model added.');
    }

    public function destroyAppModel(Request $request, App $app, AppModel $appModel)
    {
        abort_unless($app->user_id === $request->user()->id, 403);
        abort_unless($appModel->app_id === $app->id, 404);

        $appModel->delete();

        return redirect("/dashboard/apps/{$app->id}")->with('success', 'Model removed.');
    }

    public function updateApp(Request $request, App $app)
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'rate_limit_per_hour' => 'integer|min:1|max:10000',
        ]);

        $app->update($validated);

        return redirect("/dashboard/apps/{$app->id}")->with('success', 'Settings updated.');
    }

    public function regenerateKey(Request $request, App $app): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $app->update(['app_key' => (string) Str::uuid()]);

        return response()->json(['app_key' => $app->app_key]);
    }

    public function destroyAppUser(Request $request, App $app, AppUser $appUser): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);
        abort_unless($appUser->app_id === $app->id, 404);

        $appUser->delete();

        return response()->json(['message' => 'User removed.']);
    }
}
