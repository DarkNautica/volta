@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl" style="color: var(--text);">Settings</h1>
        <p class="mt-1" style="color: var(--muted);">Manage your account and security settings.</p>
    </div>

    <div class="max-w-xl space-y-8">
        {{-- Profile --}}
        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <h2 class="text-2xl mb-4" style="color: var(--text);">Profile</h2>
            <form method="POST" action="/dashboard/settings/profile" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text);">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Email</label>
                    <input
                        type="email"
                        value="{{ $user->email }}"
                        disabled
                        class="w-full px-4 py-3 rounded-lg text-sm"
                        style="background-color: var(--bg); border: 1px solid var(--border); color: var(--muted); cursor: not-allowed;"
                    >
                    <p class="mt-1 text-xs" style="color: var(--muted);">Contact support to change your email.</p>
                </div>
                <button
                    type="submit"
                    class="px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                    style="background-color: var(--accent); color: var(--bg);"
                >Save Profile</button>
            </form>
        </div>

        {{-- Security --}}
        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <h2 class="text-2xl mb-4" style="color: var(--text);">Security</h2>
            <form method="POST" action="/dashboard/settings/password" class="space-y-4">
                @csrf
                <div>
                    <label for="current_password" class="block text-sm font-medium mb-2" style="color: var(--text);">Current Password</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        required
                        class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                    >
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-2" style="color: var(--text);">New Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: var(--text);">Confirm New Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                    >
                </div>
                <button
                    type="submit"
                    class="px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                    style="background-color: var(--accent); color: var(--bg);"
                >Update Password</button>
            </form>
        </div>

        {{-- Danger Zone --}}
        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid rgba(239,68,68,0.3);" x-data="{ confirmDelete: false }">
            <h2 class="text-2xl mb-2" style="color: #ef4444;">Danger Zone</h2>
            <p class="text-sm mb-4" style="color: var(--muted);">Permanently delete your account and all associated data.</p>

            <button
                @click="confirmDelete = true"
                class="px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                style="background-color: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3);"
            >Delete Account</button>

            {{-- Confirmation Modal --}}
            <template x-if="confirmDelete">
                <div class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.7);" @click.self="confirmDelete = false" @keydown.escape.window="confirmDelete = false">
                    <div class="rounded-xl p-8 max-w-md w-full mx-4" style="background-color: var(--surface); border: 1px solid var(--border);">
                        <h3 class="text-2xl mb-3" style="color: #ef4444;">Delete Account</h3>
                        <p class="text-sm mb-6" style="color: var(--muted);">
                            This will permanently delete your account and all apps. This cannot be undone.
                        </p>
                        <div class="flex gap-3">
                            <form method="POST" action="/dashboard/settings/account">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                                    style="background-color: #ef4444; color: #fff;"
                                >Yes, delete my account</button>
                            </form>
                            <button
                                @click="confirmDelete = false"
                                class="px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-80"
                                style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text);"
                            >Cancel</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endsection
