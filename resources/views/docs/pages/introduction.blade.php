@extends('docs.layout')

@section('content')
    <h2>What is Volta?</h2>
    <p>
        Volta is a drop-in billing and usage platform for AI-powered Laravel applications. Think of it as
        <strong>Stripe for AI billing</strong> — it handles credit-based pricing, per-model metering, rate limiting,
        and an embeddable end-user portal so you can focus on building your product instead of billing infrastructure.
    </p>
    <p>
        Whether you're wrapping OpenAI, Anthropic, Gemini, or any other AI provider, Volta gives you a single SDK
        to charge users, track usage, and manage subscriptions. You install the package, drop in a few lines of code,
        and your billing is production-ready.
    </p>

    <h2>Who is it for?</h2>
    <p>
        Volta is designed for <strong>Laravel developers building AI SaaS products</strong>. If you're building a chatbot
        platform, an AI writing tool, an image generation app, or any product where users consume AI credits — Volta is
        the fastest way to monetize it.
    </p>

    <h2>How it works</h2>
    <p>Volta integrates into your Laravel app in three steps:</p>

    <div class="flex flex-col gap-4 my-6">
        <div class="flex items-start gap-4 p-4 rounded-lg" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #00C2FF; color: #0a0a1a;">1</div>
            <div>
                <p class="font-semibold mb-1" style="color: #0a0a1a;">Install the SDK</p>
                <p class="text-sm" style="color: #64748b; margin: 0;">Add the <code>volta-php</code> package to your Laravel app via Composer.</p>
            </div>
        </div>
        <div class="flex items-start gap-4 p-4 rounded-lg" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #00C2FF; color: #0a0a1a;">2</div>
            <div>
                <p class="font-semibold mb-1" style="color: #0a0a1a;">Connect your app</p>
                <p class="text-sm" style="color: #64748b; margin: 0;">Add your Volta app key and configure your AI models in the dashboard.</p>
            </div>
        </div>
        <div class="flex items-start gap-4 p-4 rounded-lg" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #00C2FF; color: #0a0a1a;">3</div>
            <div>
                <p class="font-semibold mb-1" style="color: #0a0a1a;">Charge &amp; track</p>
                <p class="text-sm" style="color: #64748b; margin: 0;">Use <code>Volta::charge()</code> to deduct credits and <code>Volta::balance()</code> to display balances.</p>
            </div>
        </div>
    </div>

    <h2>Architecture</h2>
    <div class="rounded-lg p-6 my-6 overflow-x-auto" style="background-color: #0f1117;">
        <pre class="text-sm" style="color: #e2e8f0; font-family: 'JetBrains Mono', monospace; margin: 0; line-height: 1.8;">┌──────────────┐     ┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│              │     │              │     │              │     │              │
│   Your App   │────▶│  volta-php   │────▶│  Volta API   │────▶│    Stripe    │
│              │     │    SDK       │     │              │     │              │
└──────────────┘     └──────────────┘     └──────────────┘     └──────────────┘
                                                │
                                                ▼
                                         ┌──────────────┐
                                         │   Dashboard   │
                                         │  &amp; Analytics  │
                                         └──────────────┘</pre>
    </div>

    <h2>Get started</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <a href="/docs/installation" class="block p-5 rounded-lg transition-all hover:scale-[1.01]" style="border: 1px solid #e2e8f0; text-decoration: none;">
            <p class="font-semibold mb-1" style="color: #0a0a1a;">Installation &rarr;</p>
            <p class="text-sm" style="color: #64748b; margin: 0;">Get Volta installed in your Laravel project in under 2 minutes.</p>
        </a>
        <a href="/docs/quick-start" class="block p-5 rounded-lg transition-all hover:scale-[1.01]" style="border: 1px solid #e2e8f0; text-decoration: none;">
            <p class="font-semibold mb-1" style="color: #0a0a1a;">Quick Start &rarr;</p>
            <p class="text-sm" style="color: #64748b; margin: 0;">From zero to billing in 5 minutes with a complete working example.</p>
        </a>
    </div>
@endsection
