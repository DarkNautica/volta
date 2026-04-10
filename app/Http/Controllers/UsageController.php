<?php

namespace App\Http\Controllers;

use App\Models\UsageLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsageController extends Controller
{
    public function index(Request $request): View
    {
        $range = (int) $request->query('range', 30);
        if (! in_array($range, [7, 30, 90])) {
            $range = 30;
        }

        $user = $request->user();
        $appIds = $user->apps()->pluck('id');
        $since = now()->subDays($range);

        $totalApiCalls = UsageLog::whereIn('app_id', $appIds)
            ->where('created_at', '>=', $since)
            ->count();

        $totalCreditsUsed = UsageLog::whereIn('app_id', $appIds)
            ->where('created_at', '>=', $since)
            ->sum('credits_charged');

        $totalUniqueUsers = UsageLog::whereIn('app_id', $appIds)
            ->where('created_at', '>=', $since)
            ->distinct('app_user_id')
            ->count('app_user_id');

        $logs = UsageLog::whereIn('usage_logs.app_id', $appIds)
            ->where('usage_logs.created_at', '>=', $since)
            ->join('apps', 'apps.id', '=', 'usage_logs.app_id')
            ->leftJoin('app_models', 'app_models.id', '=', 'usage_logs.app_model_id')
            ->select(
                'usage_logs.*',
                'apps.name as app_name',
                'app_models.display_name as model_name',
            )
            ->orderByDesc('usage_logs.created_at')
            ->paginate(25)
            ->appends(['range' => $range]);

        return view('usage.index', [
            'range' => $range,
            'totalApiCalls' => $totalApiCalls,
            'totalCreditsUsed' => $totalCreditsUsed,
            'totalUniqueUsers' => $totalUniqueUsers,
            'logs' => $logs,
        ]);
    }
}
