@extends('layouts.app')

@section('title', 'Billing')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl" style="color: var(--text);">Billing</h1>
        <p class="mt-1" style="color: var(--muted);">Manage your subscription and billing details.</p>
    </div>

    {{-- Trial / Expired Banner --}}
    @if($user->onTrial())
        <div class="rounded-xl p-5 mb-6" style="background-color: var(--surface); border: 2px solid var(--accent);">
            <p class="text-sm" style="color: var(--text);">
                You're on a free trial &mdash; <strong>{{ $user->trialDaysRemaining() }} days remaining</strong>. Choose a plan below to keep access.
            </p>
        </div>
    @elseif($user->trialExpired())
        <div class="rounded-xl p-5 mb-6" style="background-color: var(--surface); border: 2px solid #ef4444;">
            <p class="text-sm" style="color: #ef4444;">
                Your trial has ended. Select a plan to restore access.
            </p>
        </div>
    @endif

    {{-- Current Plan --}}
    <div class="rounded-xl p-6 mb-8" style="background-color: var(--surface); border: 1px solid var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm mb-1" style="color: var(--muted);">Current Plan</p>
                <p class="text-2xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">
                    {{ $user->planName() }}
                </p>
            </div>
            @if($user->plan)
                <form method="POST" action="/billing/portal">
                    @csrf
                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-all hover:opacity-80"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text);"
                    >
                        Manage Subscription
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Plans --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($plans as $key => $plan)
            <div class="rounded-xl p-8 flex flex-col" style="background-color: var(--surface); border: 1px solid {{ $user->plan === $key ? 'var(--accent)' : 'var(--border)' }};">
                <h3 class="text-2xl mb-2" style="color: var(--text);">{{ $plan['name'] }}</h3>

                <div class="mb-4">
                    <span class="text-4xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">${{ number_format($plan['price'] / 100) }}</span>
                    <span class="text-sm" style="color: var(--muted);">/month</span>
                </div>

                <ul class="space-y-3 mb-8 flex-1">
                    <li class="flex items-center gap-2 text-sm" style="color: var(--text);">
                        <svg class="w-4 h-4 flex-shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $plan['app_limit'] ? $plan['app_limit'] . ' app' . ($plan['app_limit'] > 1 ? 's' : '') : 'Unlimited apps' }}
                    </li>
                </ul>

                @if($user->plan === $key)
                    <div class="px-5 py-3 rounded-lg text-sm font-semibold text-center" style="background-color: var(--surface2); color: var(--muted); border: 1px solid var(--border);">
                        Current Plan
                    </div>
                @else
                    @php
                        $planOrder = ['indie' => 1, 'studio' => 2, 'agency' => 3];
                        $currentOrder = $planOrder[$user->plan] ?? 0;
                        $thisOrder = $planOrder[$key] ?? 0;
                        $label = $currentOrder > 0 && $thisOrder < $currentOrder ? 'Downgrade' : ($currentOrder > 0 && $thisOrder > $currentOrder ? 'Upgrade' : 'Subscribe');
                    @endphp
                    <form method="POST" action="/billing/subscribe">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $key }}">
                        <button
                            type="submit"
                            class="w-full px-5 py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                            style="background-color: var(--accent); color: var(--bg);"
                        >
                            {{ $label }}
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

    <p class="mt-6 text-sm text-center" style="color: var(--muted);">
        Upgrading takes effect immediately. Downgrading takes effect at the end of your billing period.
    </p>
@endsection
