<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }
    </style>
</head>
<body
    style="
        --bg: {{ $theme === 'dark' ? '#080810' : '#f8f9fc' }};
        --surface: {{ $theme === 'dark' ? '#0e0e1a' : '#ffffff' }};
        --accent: {{ $theme === 'dark' ? '#00C2FF' : '#0099cc' }};
        --text: {{ $theme === 'dark' ? '#f0f0ff' : '#111' }};
        --muted: {{ $theme === 'dark' ? 'rgba(240,240,255,0.5)' : '#666' }};
        --border: {{ $theme === 'dark' ? 'rgba(255,255,255,0.07)' : '#e5e7eb' }};
        background-color: var(--bg);
        color: var(--text);
        margin: 0;
        min-height: 100vh;
    "
>
    <div class="max-w-lg mx-auto px-4 py-8 space-y-6">

        {{-- Success Banner --}}
        <div
            x-data="{ show: new URLSearchParams(window.location.search).has('success') }"
            x-show="show"
            x-init="if(show) setTimeout(() => show = false, 5000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="rounded-lg px-4 py-3 text-sm font-medium"
            style="background-color: #065f46; color: #d1fae5;"
        >
            Credits added! Your balance is now {{ $appUser->credit_balance }} credits.
        </div>

        {{-- Balance Card --}}
        <div class="rounded-xl p-6 text-center" style="background-color: var(--surface); border: 1px solid var(--border);">
            <div class="text-5xl font-bold tracking-tight" style="color: var(--accent);">
                {{ number_format($appUser->credit_balance) }}
            </div>
            <div class="mt-1 text-sm font-medium" style="color: var(--muted);">
                Credits remaining
            </div>
            <div class="mt-3 text-xs" style="color: var(--muted);">
                Last topped up: {{ $lastTopUp?->created_at?->diffForHumans() ?? 'Never' }}
            </div>
        </div>

        {{-- Buy Credits --}}
        <div>
            <h2 class="text-lg font-semibold mb-3" style="color: var(--text);">Buy Credits</h2>
            <div class="grid grid-cols-2 gap-3">
                @foreach($packages as $index => $package)
                    <form method="POST" action="/portal/checkout?{{ http_build_query($signatureParams) }}">
                        @csrf
                        @foreach($signatureParams as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="hidden" name="package_index" value="{{ $index }}">
                        <button
                            type="submit"
                            class="w-full rounded-lg px-4 py-4 text-left transition-all duration-150 cursor-pointer"
                            style="background-color: var(--surface); border: 1px solid var(--border); color: var(--text);"
                            onmouseover="this.style.borderColor='var(--accent)'"
                            onmouseout="this.style.borderColor='var(--border)'"
                        >
                            <div class="text-xl font-bold">{{ number_format($package[0]) }}</div>
                            <div class="text-sm mt-0.5" style="color: var(--muted);">${{ number_format($package[1] / 100, 2) }}</div>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

        {{-- Usage History --}}
        <div x-data="{ showAll: false }">
            <h2 class="text-lg font-semibold mb-3" style="color: var(--text);">Recent Usage</h2>
            @if($logs->isEmpty())
                <div class="text-sm py-4 text-center" style="color: var(--muted);">No usage yet.</div>
            @else
                <div class="rounded-xl overflow-hidden" style="background-color: var(--surface); border: 1px solid var(--border);">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <th class="text-left px-4 py-2.5 font-medium text-xs uppercase tracking-wider" style="color: var(--muted);">Date</th>
                                <th class="text-left px-4 py-2.5 font-medium text-xs uppercase tracking-wider" style="color: var(--muted);">Model</th>
                                <th class="text-right px-4 py-2.5 font-medium text-xs uppercase tracking-wider" style="color: var(--muted);">Credits</th>
                                <th class="text-left px-4 py-2.5 font-medium text-xs uppercase tracking-wider" style="color: var(--muted);">Endpoint</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $i => $log)
                                <tr
                                    style="border-bottom: 1px solid var(--border);"
                                    @if($i >= 10) x-show="showAll" x-cloak @endif
                                >
                                    <td class="px-4 py-2.5 whitespace-nowrap">{{ $log->created_at->format('M d, H:i') }}</td>
                                    <td class="px-4 py-2.5 whitespace-nowrap" style="color: var(--muted);">{{ $log->appModel->display_name ?? '—' }}</td>
                                    <td class="px-4 py-2.5 text-right whitespace-nowrap font-medium">{{ $log->credits_charged }}</td>
                                    <td class="px-4 py-2.5 whitespace-nowrap text-xs" style="color: var(--muted);">{{ $log->endpoint ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($logs->count() > 10)
                    <button
                        x-show="!showAll"
                        @click="showAll = true"
                        class="mt-2 text-sm font-medium cursor-pointer"
                        style="color: var(--accent); background: none; border: none;"
                    >
                        Show more
                    </button>
                    <button
                        x-show="showAll"
                        @click="showAll = false"
                        class="mt-2 text-sm font-medium cursor-pointer"
                        style="color: var(--accent); background: none; border: none;"
                    >
                        Show less
                    </button>
                @endif
            @endif
        </div>

        {{-- Back Link --}}
        @if($returnUrl)
            <div class="pt-2">
                <a
                    href="{{ $returnUrl }}"
                    class="text-sm font-medium inline-flex items-center gap-1"
                    style="color: var(--accent);"
                >
                    &larr; Back
                </a>
            </div>
        @endif

    </div>
</body>
</html>
