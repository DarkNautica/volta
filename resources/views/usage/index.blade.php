@extends('layouts.app')

@section('title', 'Usage')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl" style="color: var(--text);">Usage</h1>
        <p class="mt-1" style="color: var(--muted);">API calls and credit usage across all your apps.</p>
    </div>

    {{-- Date Range Selector --}}
    <div class="flex gap-2 mb-8" x-data>
        @foreach([7, 30, 90] as $r)
            <a
                href="/dashboard/usage?range={{ $r }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                style="{{ $range === $r
                    ? 'background-color: var(--accent); color: var(--bg);'
                    : 'background-color: var(--surface); border: 1px solid var(--border); color: var(--muted);' }}"
            >{{ $r }}D</a>
        @endforeach
    </div>

    {{-- Summary Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Total API Calls</p>
            <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">{{ number_format($totalApiCalls) }}</p>
        </div>
        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Credits Used</p>
            <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">{{ number_format($totalCreditsUsed) }}</p>
        </div>
        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Unique Users</p>
            <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">{{ number_format($totalUniqueUsers) }}</p>
        </div>
    </div>

    {{-- Usage Logs Table --}}
    <div class="rounded-xl overflow-hidden" style="background-color: var(--surface); border: 1px solid var(--border);">
        <table class="w-full text-sm">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th class="text-left px-5 py-3 font-semibold" style="color: var(--muted);">Date</th>
                    <th class="text-left px-5 py-3 font-semibold" style="color: var(--muted);">App</th>
                    <th class="text-left px-5 py-3 font-semibold" style="color: var(--muted);">User</th>
                    <th class="text-left px-5 py-3 font-semibold" style="color: var(--muted);">Model</th>
                    <th class="text-left px-5 py-3 font-semibold" style="color: var(--muted);">Credits</th>
                    <th class="text-left px-5 py-3 font-semibold" style="color: var(--muted);">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td class="px-5 py-3" style="color: var(--text);">{{ $log->created_at->format('M j, H:i') }}</td>
                        <td class="px-5 py-3" style="color: var(--text);">{{ $log->app_name }}</td>
                        <td class="px-5 py-3" style="color: var(--muted);">{{ $log->app_user_id }}</td>
                        <td class="px-5 py-3" style="color: var(--muted);">{{ $log->model_name ?? '—' }}</td>
                        <td class="px-5 py-3" style="color: var(--accent);">{{ $log->credits_charged }}</td>
                        <td class="px-5 py-3">
                            @if($log->success)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold" style="background-color: rgba(134,239,172,0.15); color: #86efac;">OK</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold" style="background-color: rgba(239,68,68,0.15); color: #ef4444;">Fail</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center" style="color: var(--muted);">
                            No usage data for the last {{ $range }} days.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $logs->links() }}
        </div>
    @endif
@endsection
