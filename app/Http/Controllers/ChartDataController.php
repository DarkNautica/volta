<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\AppUser;
use App\Models\UsageLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChartDataController extends Controller
{
    public function __invoke(Request $request, App $app): JsonResponse
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $range = $request->query('range', '7d');
        $days = match ($range) {
            '30d' => 30,
            '90d' => 90,
            default => 7,
        };

        $startDate = Carbon::today()->subDays($days - 1);
        $endDate = Carbon::today();

        // Build date range
        $labels = [];
        $dateMap = [];
        $period = $startDate->copy();
        while ($period->lte($endDate)) {
            $key = $period->format('Y-m-d');
            $labels[] = $period->format('M d');
            $dateMap[$key] = ['api_calls' => 0, 'credits_used' => 0, 'new_users' => 0];
            $period->addDay();
        }

        // API calls and credits used per day
        $usageData = UsageLog::where('app_id', $app->id)
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as api_calls'),
                DB::raw('SUM(credits_charged) as credits_used'),
            )
            ->groupBy('date')
            ->get();

        foreach ($usageData as $row) {
            if (isset($dateMap[$row->date])) {
                $dateMap[$row->date]['api_calls'] = (int) $row->api_calls;
                $dateMap[$row->date]['credits_used'] = (int) $row->credits_used;
            }
        }

        // New users per day
        $newUsers = AppUser::where('app_id', $app->id)
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
            )
            ->groupBy('date')
            ->get();

        foreach ($newUsers as $row) {
            if (isset($dateMap[$row->date])) {
                $dateMap[$row->date]['new_users'] = (int) $row->count;
            }
        }

        $apiCalls = array_column(array_values($dateMap), 'api_calls');
        $creditsUsed = array_column(array_values($dateMap), 'credits_used');
        $newUsersArr = array_column(array_values($dateMap), 'new_users');

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                'api_calls' => $apiCalls,
                'credits_used' => $creditsUsed,
                'new_users' => $newUsersArr,
            ],
            'totals' => [
                'api_calls' => array_sum($apiCalls),
                'credits_used' => array_sum($creditsUsed),
                'new_users' => array_sum($newUsersArr),
            ],
        ]);
    }
}
