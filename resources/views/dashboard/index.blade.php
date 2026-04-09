@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl" style="color: var(--text);">Welcome back</h1>
        <p class="mt-1" style="color: var(--muted);">Here's an overview of your platform.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Total Apps</p>
            <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">{{ number_format($totalApps) }}</p>
        </div>

        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">End Users</p>
            <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">{{ number_format($totalEndUsers) }}</p>
        </div>

        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">API Calls This Month</p>
            <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">{{ number_format($totalApiCalls) }}</p>
        </div>

        <div class="rounded-xl p-6" style="background-color: var(--surface); border: 1px solid var(--border);">
            <p class="text-sm mb-1" style="color: var(--muted);">Credits Sold (MRR proxy)</p>
            <p class="text-3xl font-bold" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">${{ number_format($mrr, 2) }}</p>
        </div>
    </div>
@endsection
