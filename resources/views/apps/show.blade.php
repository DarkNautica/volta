@extends('layouts.app')

@section('title', $app->name)

@section('content')
    <div class="mb-8">
        <a href="/dashboard/apps" class="inline-flex items-center gap-1 text-sm mb-4 hover:underline" style="color: var(--muted);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Apps
        </a>
        <div class="flex items-center gap-3">
            <h1 class="text-4xl" style="color: var(--text);">{{ $app->name }}</h1>
            <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: var(--surface2); color: var(--accent);">
                {{ $app->ai_provider }}
            </span>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'overview' }">
        <div class="flex gap-1 mb-8 overflow-x-auto" style="border-bottom: 1px solid var(--border);">
            <template x-for="t in ['overview', 'users', 'models', 'settings']" :key="t">
                <button
                    @click="tab = t"
                    :style="tab === t ? 'color: var(--accent); border-bottom-color: var(--accent)' : 'color: var(--muted); border-bottom-color: transparent'"
                    class="px-4 py-3 text-sm font-medium transition-all capitalize"
                    style="border-bottom: 2px solid transparent;"
                    x-text="t"
                ></button>
            </template>
        </div>

        {{-- Overview Tab --}}
        <div x-show="tab === 'overview'">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
                    <p class="text-sm mb-1" style="color: var(--muted);">Total Users</p>
                    <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">{{ $stats['total_users'] }}</p>
                </div>
                <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
                    <p class="text-sm mb-1" style="color: var(--muted);">Credits Sold</p>
                    <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">{{ $stats['credits_sold'] }}</p>
                </div>
                <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
                    <p class="text-sm mb-1" style="color: var(--muted);">API Calls</p>
                    <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">{{ $stats['api_calls'] }}</p>
                </div>
                <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
                    <p class="text-sm mb-1" style="color: var(--muted);">Rate Limit Alerts</p>
                    <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">{{ $stats['rate_limit_alerts'] }}</p>
                </div>
            </div>

            {{-- Usage Chart --}}
            <div
                x-data="usageChart({{ $app->id }})"
                x-init="fetchData()"
                class="rounded-xl p-6"
                style="background-color: var(--surface); border: 1px solid var(--border);"
            >
                {{-- Controls --}}
                <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                    <div class="flex items-center gap-2">
                        <template x-for="r in ['7d', '30d', '90d']" :key="r">
                            <button
                                @click="range = r; fetchData()"
                                :style="range === r ? 'background-color: var(--accent); color: var(--bg);' : 'background-color: var(--surface2); color: var(--muted);'"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                x-text="r.replace('d', 'D')"
                            ></button>
                        </template>
                    </div>
                    <button
                        @click="live = !live; if(live) startPolling(); else stopPolling();"
                        :style="live ? 'color: var(--accent);' : 'color: var(--muted);'"
                        class="flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all"
                        style="background-color: var(--surface2);"
                    >
                        <span
                            class="w-2 h-2 rounded-full"
                            :style="live ? 'background-color: #51CF66;' : 'background-color: var(--muted);'"
                            :class="live && 'animate-pulse'"
                        ></span>
                        Live
                    </button>
                </div>

                {{-- Stat pills --}}
                <div class="flex items-center gap-4 mb-4 text-xs flex-wrap" style="color: var(--muted);">
                    <span><strong x-text="totals.api_calls?.toLocaleString() ?? '—'" style="color: var(--text);"></strong> API calls</span>
                    <span>&middot;</span>
                    <span><strong x-text="totals.credits_used?.toLocaleString() ?? '—'" style="color: var(--text);"></strong> credits used</span>
                    <span>&middot;</span>
                    <span><strong x-text="totals.new_users?.toLocaleString() ?? '—'" style="color: var(--text);"></strong> new users</span>
                </div>

                {{-- Chart container --}}
                <div style="height: 280px; position: relative;">
                    {{-- Loading skeleton --}}
                    <div x-show="loading" class="absolute inset-0 rounded-lg animate-pulse" style="background-color: var(--surface2);"></div>
                    <canvas x-ref="chart" x-show="!loading" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- Users Tab --}}
        <div x-show="tab === 'users'" x-cloak>
            <div class="rounded-xl overflow-hidden" style="background-color: var(--surface); border: 1px solid var(--border);">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <th class="text-left px-6 py-4 font-medium" style="color: var(--muted);">External User ID</th>
                                <th class="text-left px-6 py-4 font-medium" style="color: var(--muted);">Credit Balance</th>
                                <th class="text-left px-6 py-4 font-medium" style="color: var(--muted);">Usage Logs</th>
                                <th class="text-left px-6 py-4 font-medium" style="color: var(--muted);">Last Updated</th>
                                <th class="text-right px-6 py-4 font-medium" style="color: var(--muted);">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($app->appUsers as $user)
                                <tr
                                    x-data="{ confirmDelete: false }"
                                    style="border-bottom: 1px solid var(--border);"
                                >
                                    <td class="px-6 py-4" style="color: var(--text);">{{ $user->external_user_id }}</td>
                                    <td class="px-6 py-4" style="color: var(--accent);">{{ $user->credit_balance }}</td>
                                    <td class="px-6 py-4" style="color: var(--text);">{{ $user->usage_logs_count }}</td>
                                    <td class="px-6 py-4" style="color: var(--muted);">{{ $user->updated_at->diffForHumans() }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <template x-if="!confirmDelete">
                                            <button @click="confirmDelete = true" class="text-xs px-2 py-1 rounded hover:opacity-80" style="color: #FF6B6B;">
                                                Delete
                                            </button>
                                        </template>
                                        <template x-if="confirmDelete">
                                            <span class="inline-flex gap-2 items-center">
                                                <span class="text-xs" style="color: var(--muted);">Sure?</span>
                                                <button
                                                    @click="fetch('/dashboard/apps/{{ $app->id }}/users/{{ $user->id }}', {
                                                        method: 'DELETE',
                                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                                    }).then(() => $el.closest('tr').remove())"
                                                    class="text-xs px-2 py-1 rounded font-semibold" style="color: #FF6B6B;"
                                                >Yes</button>
                                                <button @click="confirmDelete = false" class="text-xs px-2 py-1 rounded" style="color: var(--muted);">No</button>
                                            </span>
                                        </template>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center" style="color: var(--muted);">No users yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Models Tab --}}
        <div x-show="tab === 'models'" x-cloak>
            @if($app->appModels->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
                    @foreach($app->appModels as $model)
                        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
                            <h4 class="text-lg mb-1" style="color: var(--text);">{{ $model->display_name }}</h4>
                            <code class="text-xs px-2 py-1 rounded inline-block mb-3" style="background-color: var(--surface2); color: var(--muted);">{{ $model->model_identifier }}</code>
                            <p class="text-sm" style="color: var(--accent);">{{ $model->credits_per_call }} credits/call</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl p-8 text-center mb-8" style="background-color: var(--surface); border: 1px solid var(--border);">
                    <p style="color: var(--muted);">No models configured yet. Add one below.</p>
                </div>
            @endif

            <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
                <h3 class="text-xl mb-4" style="color: var(--text);">Add Model</h3>
                <form method="POST" action="/dashboard/apps/{{ $app->id }}/models" class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="model_identifier" placeholder="e.g. claude-sonnet-4-20250514" required
                            class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                            style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);">
                    </div>
                    <div class="flex-1">
                        <input type="text" name="display_name" placeholder="Display Name" required
                            class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                            style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);">
                    </div>
                    <div class="w-full sm:w-32">
                        <input type="number" name="credits_per_call" placeholder="Credits" required min="1"
                            class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                            style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);">
                    </div>
                    <button type="submit" class="px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90 whitespace-nowrap"
                        style="background-color: var(--accent); color: var(--bg);">
                        Add Model
                    </button>
                </form>
            </div>
        </div>

        {{-- Settings Tab --}}
        <div x-show="tab === 'settings'" x-cloak>
            <div class="max-w-xl space-y-8">
                {{-- App Key --}}
                <div
                    x-data="{ showKey: false, showConfirm: false, appKey: '{{ $app->app_key }}', regenerating: false }"
                    class="rounded-xl p-6"
                    style="background-color: var(--surface); border: 1px solid var(--border);"
                >
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">App Key</label>
                    <div class="flex items-center gap-3 mb-3">
                        <code
                            class="flex-1 px-4 py-3 rounded-lg text-sm font-mono"
                            style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--muted);"
                        >
                            <span x-show="!showKey">****-****-****-<span x-text="appKey.slice(-12)"></span></span>
                            <span x-show="showKey" x-text="appKey" style="color: var(--text);"></span>
                        </code>
                        <button @click="showKey = !showKey" class="text-xs px-3 py-2 rounded-lg"
                            style="background-color: var(--surface2); color: var(--muted);"
                            x-text="showKey ? 'Hide' : 'Reveal'"></button>
                    </div>

                    {{-- Regenerate --}}
                    <template x-if="!showConfirm">
                        <button @click="showConfirm = true" class="text-xs px-3 py-2 rounded-lg"
                            style="background-color: var(--surface2); color: #FF6B6B;">
                            Regenerate Key
                        </button>
                    </template>
                    <template x-if="showConfirm">
                        <div class="rounded-lg p-4 mt-2" style="background-color: var(--surface2); border: 1px solid #FF6B6B40;">
                            <p class="text-sm mb-3" style="color: #FF6B6B;">This will break any existing integrations. Are you sure?</p>
                            <div class="flex gap-2">
                                <button
                                    @click="regenerating = true; fetch('/dashboard/apps/{{ $app->id }}/regenerate-key', {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                    }).then(r => r.json()).then(data => { appKey = data.app_key; showKey = true; showConfirm = false; regenerating = false; })"
                                    :disabled="regenerating"
                                    class="text-xs px-3 py-2 rounded-lg font-semibold"
                                    style="background-color: #FF6B6B; color: white;"
                                >
                                    <span x-show="!regenerating">Yes, regenerate</span>
                                    <span x-show="regenerating">Regenerating...</span>
                                </button>
                                <button @click="showConfirm = false" class="text-xs px-3 py-2 rounded-lg"
                                    style="background-color: var(--surface); color: var(--muted);">Cancel</button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Settings form --}}
                <form method="POST" action="/dashboard/apps/{{ $app->id }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="settings_name" class="block text-sm font-medium mb-2" style="color: var(--text);">App Name</label>
                        <input type="text" id="settings_name" name="name" value="{{ old('name', $app->name) }}" required
                            class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                            style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="settings_rate_limit" class="block text-sm font-medium mb-2" style="color: var(--text);">Rate Limit (per hour)</label>
                        <input type="number" id="settings_rate_limit" name="rate_limit_per_hour"
                            value="{{ old('rate_limit_per_hour', $app->rate_limit_per_hour) }}" required min="1"
                            class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                            style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);">
                        @error('rate_limit_per_hour')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                            style="background-color: var(--accent); color: var(--bg);">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        function usageChart(appId) {
            return {
                range: '7d',
                live: false,
                loading: true,
                totals: {},
                chart: null,
                pollInterval: null,

                async fetchData() {
                    this.loading = true;
                    try {
                        const res = await fetch(`/api/v1/apps/${appId}/chart-data?range=${this.range}`, {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        const data = await res.json();
                        this.totals = data.totals;
                        this.renderChart(data);
                    } catch (e) {
                        console.error('Chart fetch error:', e);
                    }
                    this.loading = false;
                },

                renderChart(data) {
                    const ctx = this.$refs.chart;
                    if (this.chart) this.chart.destroy();

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'API Calls',
                                    data: data.datasets.api_calls,
                                    borderColor: '#00C2FF',
                                    backgroundColor: 'rgba(0, 194, 255, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                },
                                {
                                    label: 'Credits Used',
                                    data: data.datasets.credits_used,
                                    borderColor: '#FF6B6B',
                                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                },
                                {
                                    label: 'New Users',
                                    data: data.datasets.new_users,
                                    borderColor: '#51CF66',
                                    backgroundColor: 'rgba(81, 207, 102, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: {
                                legend: {
                                    labels: { color: 'rgba(240, 240, 255, 0.6)', font: { family: 'DM Sans' } }
                                },
                                tooltip: { mode: 'index', intersect: false },
                            },
                            scales: {
                                x: {
                                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                    ticks: { color: 'rgba(240, 240, 255, 0.4)', font: { family: 'DM Sans' } },
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                    ticks: { color: 'rgba(240, 240, 255, 0.4)', font: { family: 'DM Sans' } },
                                },
                            },
                        },
                    });
                },

                startPolling() {
                    this.pollInterval = setInterval(() => this.fetchData(), 30000);
                },

                stopPolling() {
                    if (this.pollInterval) {
                        clearInterval(this.pollInterval);
                        this.pollInterval = null;
                    }
                },
            };
        }
    </script>
@endsection
