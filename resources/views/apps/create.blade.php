@extends('layouts.app')

@section('title', 'Create App')

@section('content')
    <div class="mb-8">
        <a href="/dashboard/apps" class="inline-flex items-center gap-1 text-sm mb-4 hover:underline" style="color: var(--muted);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Apps
        </a>
        <h1 class="text-4xl" style="color: var(--text);">Create New App</h1>
        <p class="mt-1" style="color: var(--muted);">Set up a new AI-powered application.</p>
    </div>

    <div class="max-w-xl">
        <form method="POST" action="/dashboard/apps" class="space-y-6">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text);">App Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="My AI App"
                    class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                    style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- AI Provider --}}
            <div>
                <label for="ai_provider" class="block text-sm font-medium mb-2" style="color: var(--text);">AI Provider</label>
                <select
                    id="ai_provider"
                    name="ai_provider"
                    required
                    class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2 appearance-none"
                    style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                >
                    <option value="anthropic" {{ old('ai_provider') === 'anthropic' ? 'selected' : '' }}>Anthropic</option>
                    <option value="openai" {{ old('ai_provider') === 'openai' ? 'selected' : '' }}>OpenAI</option>
                    <option value="gemini" {{ old('ai_provider') === 'gemini' ? 'selected' : '' }}>Gemini</option>
                </select>
                @error('ai_provider')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rate Limit --}}
            <div>
                <label for="rate_limit_per_hour" class="block text-sm font-medium mb-2" style="color: var(--text);">Rate Limit (per hour)</label>
                <input
                    type="number"
                    id="rate_limit_per_hour"
                    name="rate_limit_per_hour"
                    value="{{ old('rate_limit_per_hour', 60) }}"
                    required
                    min="1"
                    class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                    style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                >
                @error('rate_limit_per_hour')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                    style="background-color: var(--accent); color: var(--bg);"
                >
                    Create App
                </button>
            </div>
        </form>
    </div>
@endsection
