<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Volta Docs</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            margin: 0;
            background-color: #ffffff;
            color: #1a1a2e;
        }

        /* Sidebar */
        .docs-sidebar {
            background-color: #080810;
            width: 260px;
            position: fixed;
            top: 56px;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 20;
            padding: 1.5rem 0;
        }

        .docs-sidebar::-webkit-scrollbar { width: 4px; }
        .docs-sidebar::-webkit-scrollbar-track { background: transparent; }
        .docs-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(240,240,255,0.35);
            padding: 0.75rem 1.5rem 0.375rem;
            margin-top: 0.5rem;
        }

        .sidebar-section-title:first-child { margin-top: 0; }

        .sidebar-page-link {
            display: block;
            padding: 0.375rem 1.5rem;
            font-size: 13px;
            color: rgba(240,240,255,0.6);
            text-decoration: none;
            transition: all 0.12s ease;
            border-left: 2px solid transparent;
        }

        .sidebar-page-link:hover {
            color: rgba(240,240,255,0.85);
            background-color: rgba(255,255,255,0.05);
        }

        .sidebar-page-link.active {
            color: #00C2FF;
            border-left-color: #00C2FF;
            background-color: rgba(0,194,255,0.06);
        }

        /* Top nav */
        .docs-topnav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background-color: #080810;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            z-index: 30;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }

        /* Content */
        .docs-content {
            margin-left: 260px;
            margin-top: 56px;
            min-height: calc(100vh - 56px);
        }

        .docs-body {
            max-width: 740px;
            padding: 3rem;
        }

        .docs-body h1 {
            font-family: 'DM Sans', sans-serif;
            font-size: 2.25rem;
            font-weight: 800;
            color: #0a0a1a;
            margin: 0 0 0.5rem;
            line-height: 1.2;
        }

        .docs-body h2 {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #0a0a1a;
            margin: 2.5rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .docs-body h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: #0a0a1a;
            margin: 2rem 0 0.75rem;
        }

        .docs-body p {
            font-size: 16px;
            line-height: 1.8;
            color: #1a1a2e;
            margin: 0 0 1rem;
        }

        .docs-body ul, .docs-body ol {
            font-size: 16px;
            line-height: 1.8;
            color: #1a1a2e;
            margin: 0 0 1rem;
            padding-left: 1.5rem;
        }

        .docs-body li { margin-bottom: 0.35rem; }

        .docs-body a {
            color: #00C2FF;
            text-decoration: none;
        }

        .docs-body a:hover { text-decoration: underline; }

        .docs-body code:not(pre code) {
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 0.15em 0.4em;
            border-radius: 4px;
            font-size: 0.875em;
            font-family: 'JetBrains Mono', monospace;
        }

        .docs-body strong { color: #0a0a1a; }

        .docs-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 1.5rem;
            font-size: 14px;
        }

        .docs-body table th {
            text-align: left;
            padding: 0.625rem 0.75rem;
            font-weight: 600;
            color: #0a0a1a;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .docs-body table td {
            padding: 0.625rem 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            color: #1a1a2e;
        }

        .docs-body table tr:last-child td { border-bottom: none; }

        .docs-body table code {
            font-size: 0.8125em;
        }

        /* Breadcrumb */
        .docs-breadcrumb {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 1.5rem;
        }

        .docs-breadcrumb a { color: #64748b; text-decoration: none; }
        .docs-breadcrumb a:hover { color: #00C2FF; }
        .docs-breadcrumb span { margin: 0 0.375rem; color: #cbd5e1; }

        /* Pagination */
        .docs-pagination {
            display: flex;
            justify-content: space-between;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .docs-pagination a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            transition: color 0.15s;
        }

        .docs-pagination a:hover { color: #00C2FF; }

        .docs-pagination .page-label {
            font-size: 12px;
            color: #94a3b8;
            display: block;
            margin-bottom: 0.25rem;
        }

        .docs-pagination .page-title {
            color: #0a0a1a;
            font-weight: 600;
        }

        .docs-pagination a:hover .page-title { color: #00C2FF; }

        /* Search modal */
        .search-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 50;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 15vh;
        }

        .search-modal {
            background: #ffffff;
            border-radius: 12px;
            width: 560px;
            max-height: 480px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        }

        .search-modal input {
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 16px;
            border: none;
            outline: none;
            border-bottom: 1px solid #e2e8f0;
            font-family: 'DM Sans', sans-serif;
        }

        .search-results {
            max-height: 380px;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .search-results a {
            display: block;
            padding: 0.5rem 0.75rem;
            font-size: 14px;
            color: #1a1a2e;
            text-decoration: none;
            border-radius: 6px;
        }

        .search-results a:hover { background-color: #f1f5f9; color: #00C2FF; }

        .search-results .section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            padding: 0.75rem 0.75rem 0.25rem;
        }

        /* Mobile */
        @media (max-width: 1023px) {
            .docs-sidebar { transform: translateX(-100%); transition: transform 0.2s ease; }
            .docs-sidebar.open { transform: translateX(0); }
            .docs-content { margin-left: 0; }
            .docs-body { padding: 1.5rem; }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false, searchOpen: false }" @keydown.window.prevent.meta.k="searchOpen = true" @keydown.window.prevent.ctrl.k="searchOpen = true">

    {{-- Top nav --}}
    <div class="docs-topnav">
        {{-- Mobile toggle --}}
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden mr-3 p-1.5 rounded-lg hover:bg-white/5">
            <svg class="w-5 h-5" style="color: rgba(240,240,255,0.6);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-0 mr-auto text-decoration-none">
            <span class="text-xl font-bold tracking-wider" style="font-family: 'DM Sans', sans-serif; color: #f0f0ff;">VOLT</span>
            <span class="text-xl font-bold tracking-wider" style="font-family: 'DM Sans', sans-serif; color: #00C2FF;">A</span>
        </a>

        {{-- Search --}}
        <button
            @click="searchOpen = true"
            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm mx-auto"
            style="background-color: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: rgba(240,240,255,0.4); min-width: 240px;"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span class="flex-1 text-left">Search docs...</span>
            <kbd class="text-xs px-1.5 py-0.5 rounded" style="background-color: rgba(255,255,255,0.08); color: rgba(240,240,255,0.35);">Ctrl K</kbd>
        </button>

        {{-- Right links --}}
        <div class="flex items-center gap-4 ml-auto">
            <a href="/dashboard" class="text-sm font-medium hover:opacity-80" style="color: rgba(240,240,255,0.6); text-decoration: none;">Dashboard &rarr;</a>
            <a href="https://github.com" class="text-sm font-medium hover:opacity-80" style="color: rgba(240,240,255,0.6); text-decoration: none;">GitHub</a>
        </div>
    </div>

    {{-- Mobile overlay --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-10 bg-black/50 lg:hidden"
        @click="sidebarOpen = false"
        style="display: none;"
    ></div>

    {{-- Sidebar --}}
    <aside class="docs-sidebar" :class="sidebarOpen && 'open'">
        <div class="px-6 mb-4">
            <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold" style="background-color: rgba(0,194,255,0.12); color: #00C2FF;">v1.0.0</span>
        </div>

        <nav>
            @foreach($navigation as $sectionKey => $section)
                <div class="sidebar-section-title">{{ $section['label'] }}</div>
                @foreach($section['pages'] as $slug => $label)
                    <a href="/docs/{{ $slug }}" class="sidebar-page-link {{ $currentSlug === $slug ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            @endforeach
        </nav>
    </aside>

    {{-- Content --}}
    <div class="docs-content">
        <div class="docs-body">
            {{-- Breadcrumb --}}
            <div class="docs-breadcrumb">
                @foreach($breadcrumb as $i => $crumb)
                    @if($crumb['href'])
                        <a href="{{ $crumb['href'] }}">{{ $crumb['label'] }}</a>
                    @else
                        <span style="color: #64748b;">{{ $crumb['label'] }}</span>
                    @endif
                    @if($i < count($breadcrumb) - 1)
                        <span>&rsaquo;</span>
                    @endif
                @endforeach
            </div>

            {{-- Title --}}
            <h1>{{ $title }}</h1>
            <p class="text-sm mb-8" style="color: #94a3b8;">Last updated April 9, 2026</p>

            {{-- Page content --}}
            @yield('content')

            {{-- Pagination --}}
            <div class="docs-pagination">
                <div>
                    @if($prev)
                        <a href="{{ $prev['href'] }}">
                            <span class="page-label">&larr; Previous</span>
                            <span class="page-title">{{ $prev['label'] }}</span>
                        </a>
                    @endif
                </div>
                <div class="text-right">
                    @if($next)
                        <a href="{{ $next['href'] }}">
                            <span class="page-label">Next &rarr;</span>
                            <span class="page-title">{{ $next['label'] }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Search modal --}}
    <template x-if="searchOpen">
        <div class="search-modal-overlay" @click.self="searchOpen = false" @keydown.escape.window="searchOpen = false">
            <div class="search-modal" @click.stop>
                <input type="text" placeholder="Search documentation..." autofocus>
                <div class="search-results">
                    @foreach($navigation as $sectionKey => $section)
                        <div class="section-label">{{ $section['label'] }}</div>
                        @foreach($section['pages'] as $slug => $label)
                            <a href="/docs/{{ $slug }}" @click="searchOpen = false">{{ $label }}</a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </template>

</body>
</html>
