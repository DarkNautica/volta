@extends('layouts.app')

@section('title', 'Your Apps')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <h1 class="text-4xl" style="color: var(--text);">Your Apps</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background-color: var(--surface2); color: var(--accent);">
                {{ $apps->count() }} / {{ Auth::user()->appLimit() ?? '&infin;' }} apps
            </span>
        </div>
        @if(Auth::user()->canCreateApp())
            <a href="/dashboard/apps/create" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all hover:opacity-90" style="background-color: var(--accent); color: var(--bg);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New App
            </a>
        @else
            <span title="Upgrade your plan to create more apps" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold cursor-not-allowed opacity-50" style="background-color: var(--surface2); color: var(--muted); border: 1px solid var(--border);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New App
            </span>
        @endif
    </div>

    @if(session('error'))
        <div class="rounded-xl p-4 mb-6" style="background-color: var(--surface); border: 1px solid #ef4444;">
            <p class="text-sm" style="color: #ef4444;">{{ session('error') }}</p>
        </div>
    @endif

    @if($apps->isEmpty())
        <div class="rounded-xl p-12 text-center" style="background-color: var(--surface); border: 1px solid var(--border);">
            <svg class="w-12 h-12 mx-auto mb-4" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <h3 class="text-xl mb-2" style="color: var(--text);">No apps yet</h3>
            <p class="mb-6" style="color: var(--muted);">Create your first app to start building with AI.</p>
            @if(Auth::user()->canCreateApp())
                <a href="/dashboard/apps/create" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all hover:opacity-90" style="background-color: var(--accent); color: var(--bg);">
                    Create your first app
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($apps as $app)
                <a href="/dashboard/apps/{{ $app->id }}" class="block rounded-xl p-6 transition-all hover:scale-[1.01]" style="background-color: var(--surface); border: 1px solid var(--border);">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-xl" style="color: var(--text);">{{ $app->name }}</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background-color: var(--surface2); color: var(--accent);">
                            {{ $app->ai_provider }}
                        </span>
                    </div>

                    <div x-data="{ revealed: false }" class="mb-4">
                        <p class="text-xs mb-1" style="color: var(--muted);">App Key</p>
                        <div class="flex items-center gap-2">
                            <code class="text-xs px-2 py-1 rounded" style="background-color: var(--surface2); color: var(--muted);">
                                <span x-show="!revealed">{{ Str::mask($app->app_key, '*', 8) }}</span>
                                <span x-show="revealed" x-cloak>{{ $app->app_key }}</span>
                            </code>
                            <button @click.prevent="revealed = !revealed" class="text-xs hover:underline" style="color: var(--accent);" x-text="revealed ? 'Hide' : 'Reveal'"></button>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-sm" style="color: var(--muted);">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $app->app_users_count }} users
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            {{ $app->api_calls_today_count }} calls today
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
