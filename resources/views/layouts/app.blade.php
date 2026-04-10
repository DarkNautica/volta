<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volta - @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --bg: #080810;
            --surface: #0e0e1a;
            --surface2: #141420;
            --accent: #00C2FF;
            --border: rgba(255,255,255,0.07);
            --text: #f0f0ff;
            --muted: rgba(240,240,255,0.5);
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            margin: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.04em;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--surface2); border-radius: 3px; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: var(--text);
            background-color: var(--surface2);
        }

        .sidebar-link.active {
            color: var(--accent);
        }
    </style>
</head>
<body class="min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-30 bg-black/60 lg:hidden"
        @click="sidebarOpen = false"
        style="display: none;"
    ></div>

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 z-40 h-full w-64 flex flex-col transition-transform duration-200 lg:translate-x-0"
        style="background-color: var(--surface); border-right: 1px solid var(--border);"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-0 px-6 py-6">
            <span class="text-3xl font-bold tracking-wider" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">VOLT</span>
            <span class="text-3xl font-bold tracking-wider" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">A</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 space-y-1">
            <a href="/dashboard" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/></svg>
                Dashboard
            </a>
            <a href="/dashboard/apps" class="sidebar-link {{ request()->is('dashboard/apps*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Apps
            </a>
            <a href="/dashboard/usage" class="sidebar-link {{ request()->is('dashboard/usage*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Usage
            </a>
            <a href="/dashboard/billing" class="sidebar-link {{ request()->is('dashboard/billing*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Billing
            </a>
            <a href="/docs" class="sidebar-link {{ request()->is('docs*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Docs
            </a>
            <a href="/dashboard/settings" class="sidebar-link {{ request()->is('dashboard/settings*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </nav>

        {{-- User section --}}
        <div class="px-4 py-4" style="border-top: 1px solid var(--border);">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" style="background-color: var(--accent); color: var(--bg);">
                    {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-sm truncate" style="color: var(--muted);">{{ Auth::user()->email }}</span>
                    @if(Auth::user()->onTrial())
                        <span class="inline-flex mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(234,179,8,0.15); color: #eab308;">
                            Trial &mdash; {{ Auth::user()->trialDaysRemaining() }} days
                        </span>
                    @elseif(Auth::user()->trialExpired())
                        <span class="inline-flex mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(239,68,68,0.15); color: #ef4444;">
                            Expired
                        </span>
                    @elseif(Auth::user()->plan)
                        <span class="inline-flex mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(0,194,255,0.15); color: var(--accent);">
                            {{ strtoupper(Auth::user()->plan) }}
                        </span>
                    @endif
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left" style="color: var(--muted);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="lg:ml-64 min-h-screen">
        {{-- Mobile header --}}
        <header class="lg:hidden flex items-center justify-between px-4 py-3" style="background-color: var(--surface); border-bottom: 1px solid var(--border);">
            <button @click="sidebarOpen = true" class="p-2 rounded-lg" style="color: var(--text);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex items-center gap-0">
                <span class="text-xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">VOLT</span>
                <span class="text-xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">A</span>
            </div>
            <div class="w-10"></div>
        </header>

        <main class="p-6 lg:p-10">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-show="show"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="rounded-lg p-4 mb-6"
                    style="border-left: 3px solid #86efac; background-color: rgba(134,239,172,0.08);"
                >
                    <p class="text-sm" style="color: #86efac;">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    class="rounded-lg p-4 mb-6 flex items-center justify-between"
                    style="border-left: 3px solid #ef4444; background-color: rgba(239,68,68,0.08);"
                >
                    <p class="text-sm" style="color: #ef4444;">{{ session('error') }}</p>
                    <button @click="show = false" class="ml-4" style="color: #ef4444;">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
