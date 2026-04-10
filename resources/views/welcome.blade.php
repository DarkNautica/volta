<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Volta - AI Billing, Handled.</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --bg: #080810;
            --surface: #0e0e1a;
            --accent: #00C2FF;
            --text: #f0f0ff;
            --muted: rgba(240,240,255,0.5);
            --border: rgba(255,255,255,0.07);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
        }

        .font-display {
            font-family: 'Bebas Neue', sans-serif;
        }

        /* Code block syntax highlighting */
        .code-block {
            background: #06060e;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .code-block .titlebar {
            background: rgba(255,255,255,0.03);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--border);
        }
        .code-block .dot {
            width: 10px; height: 10px; border-radius: 50%;
        }
        .code-block .dot-red { background: #ff5f57; }
        .code-block .dot-yellow { background: #ffbd2e; }
        .code-block .dot-green { background: #28c840; }
        .code-block pre {
            padding: 24px;
            margin: 0;
            font-size: 14px;
            line-height: 1.8;
            overflow-x: auto;
        }
        .code-block code {
            font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
        }
        .syn-class { color: #00C2FF; }
        .syn-method { color: #c792ea; }
        .syn-paren { color: rgba(240,240,255,0.4); }
        .syn-var { color: #f78c6c; }
        .syn-semi { color: rgba(240,240,255,0.3); }
        .syn-scope { color: #89ddff; }

        /* Card hover */
        .feature-card {
            transition: border-color 0.2s ease, transform 0.2s ease;
        }
        .feature-card:hover {
            border-color: rgba(0,194,255,0.2) !important;
            transform: translateY(-2px);
        }

        /* Pricing card highlight */
        .pricing-highlight {
            border-color: var(--accent) !important;
            box-shadow: 0 0 40px rgba(0,194,255,0.08);
        }

        /* Smooth scrolling */
        html { scroll-behavior: smooth; }

        /* Step connector line */
        .step-connector {
            position: absolute;
            top: 24px;
            left: 48px;
            right: -48px;
            height: 2px;
            background: var(--border);
        }

        @media (max-width: 768px) {
            .step-connector {
                display: none;
            }
        }
    </style>
</head>
<body class="antialiased" x-data="{ mobileMenu: false }">

    <!-- ===== NAV ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md" style="background: rgba(8,8,16,0.85); border-bottom: 1px solid var(--border);">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <!-- Logo -->
            <a href="#" class="font-display text-2xl tracking-wider" style="color: var(--text);">
                VOLT<span style="color: var(--accent);">A</span>
            </a>

            <!-- Desktop Links -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Features</a>
                <a href="#how-it-works" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">How it works</a>
                <a href="#pricing" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Pricing</a>
                <a href="/docs" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Docs</a>
                <a href="/register" class="text-sm font-semibold px-5 py-2 rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent); color: #080810;">
                    Start $7 trial
                </a>
            </div>

            <!-- Mobile Hamburger -->
            <button class="md:hidden" @click="mobileMenu = !mobileMenu" style="color: var(--text);">
                <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-cloak x-transition class="md:hidden px-6 pb-6 flex flex-col gap-4" style="background: rgba(8,8,16,0.95);">
            <a href="#features" @click="mobileMenu = false" class="text-sm py-2" style="color: var(--muted);">Features</a>
            <a href="#how-it-works" @click="mobileMenu = false" class="text-sm py-2" style="color: var(--muted);">How it works</a>
            <a href="#pricing" @click="mobileMenu = false" class="text-sm py-2" style="color: var(--muted);">Pricing</a>
            <a href="/docs" class="text-sm py-2" style="color: var(--muted);">Docs</a>
            <a href="/register" class="text-sm font-semibold px-5 py-2.5 rounded-lg text-center" style="background: var(--accent); color: #080810;">Start $7 trial</a>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="pt-32 pb-20 md:pt-44 md:pb-28 px-6">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <!-- Left -->
            <div>
                <h1 class="font-display text-5xl md:text-7xl lg:text-8xl leading-none tracking-wide mb-6" style="color: var(--text);">
                    AI BILLING,<br><span style="color: var(--accent);">HANDLED.</span>
                </h1>
                <p class="text-lg md:text-xl leading-relaxed mb-8 max-w-lg" style="color: var(--muted);">
                    Drop-in billing infrastructure for AI apps. Charge per token, per request, or per feature &mdash; in three lines of code.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/register" class="inline-block text-sm font-semibold px-7 py-3 rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent); color: #080810;">
                        Start $7 trial
                    </a>
                    <a href="/docs" class="inline-block text-sm font-semibold px-7 py-3 rounded-lg border transition-colors hover:bg-white/5" style="border-color: var(--border); color: var(--text);">
                        View docs
                    </a>
                </div>
            </div>

            <!-- Right: Code Block -->
            <div class="code-block">
                <div class="titlebar">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                    <span class="ml-2 text-xs" style="color: var(--muted);">app/Http/Controllers/AiController.php</span>
                </div>
                <pre><code><span class="syn-class">Volta</span><span class="syn-scope">::</span><span class="syn-method">charge</span><span class="syn-paren">(</span><span class="syn-var">$userId</span><span class="syn-paren">)</span><span class="syn-semi">;</span>

<span class="syn-class">Volta</span><span class="syn-scope">::</span><span class="syn-method">hasAccess</span><span class="syn-paren">(</span><span class="syn-var">$userId</span><span class="syn-paren">)</span><span class="syn-semi">;</span>

<span class="syn-class">Volta</span><span class="syn-scope">::</span><span class="syn-method">balance</span><span class="syn-paren">(</span><span class="syn-var">$userId</span><span class="syn-paren">)</span><span class="syn-semi">;</span></code></pre>
            </div>
        </div>
    </section>

    <!-- ===== METRICS BAR ===== -->
    <section class="py-12 px-6" style="background: var(--surface); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="font-display text-4xl md:text-5xl mb-2" style="color: var(--accent);">3</div>
                <div class="text-sm" style="color: var(--muted);">Lines to integrate</div>
            </div>
            <div>
                <div class="font-display text-4xl md:text-5xl mb-2" style="color: var(--accent);">100%</div>
                <div class="text-sm" style="color: var(--muted);">Revenue to you</div>
            </div>
            <div>
                <div class="font-display text-4xl md:text-5xl mb-2" style="color: var(--accent);">0%</div>
                <div class="text-sm" style="color: var(--muted);">Cut taken</div>
            </div>
            <div>
                <div class="font-display text-4xl md:text-5xl mb-2" style="color: var(--accent);">&infin;</div>
                <div class="text-sm" style="color: var(--muted);">End users</div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section id="features" class="py-20 md:py-28 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl md:text-5xl tracking-wide mb-4" style="color: var(--text);">EVERYTHING YOU NEED</h2>
                <p class="text-lg max-w-xl mx-auto" style="color: var(--muted);">Built for developers shipping AI products. No billing headaches.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="feature-card rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Credit System</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Flexible credit-based billing. Let users prepay for AI usage and deduct credits per call, per token, or per feature.</p>
                </div>

                <!-- Card 2 -->
                <div class="feature-card rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Stripe Zero Config</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Connect your Stripe account and go. Volta handles checkout sessions, webhooks, and subscription management automatically.</p>
                </div>

                <!-- Card 3 -->
                <div class="feature-card rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Rate Limiting</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Protect your margins with built-in rate limits. Set per-user, per-plan, or global limits to prevent runaway API costs.</p>
                </div>

                <!-- Card 4 -->
                <div class="feature-card rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Usage Dashboard</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Real-time analytics for every user. Track spend, usage patterns, and revenue with a pre-built dashboard you can customize.</p>
                </div>

                <!-- Card 5 -->
                <div class="feature-card rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Multi-Model Pricing</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Different models cost different amounts. Set per-model credit costs so GPT-4 charges more than GPT-3.5 automatically.</p>
                </div>

                <!-- Card 6 -->
                <div class="feature-card rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Embeddable Billing Portal</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Give your users a white-labeled billing portal. Embed it in your app or link to a hosted page &mdash; fully branded.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section id="how-it-works" class="py-20 md:py-28 px-6" style="background: var(--surface);">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl md:text-5xl tracking-wide mb-4" style="color: var(--text);">HOW IT WORKS</h2>
                <p class="text-lg max-w-xl mx-auto" style="color: var(--muted);">From zero to billing in under five minutes.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 md:gap-6">
                <!-- Step 1 -->
                <div class="relative text-center md:text-left">
                    <div class="hidden md:block step-connector"></div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto md:mx-0 mb-4 font-display text-xl" style="background: var(--accent); color: #080810;">1</div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Create App</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Sign up and create a new app in the Volta dashboard. Get your API key in seconds.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative text-center md:text-left">
                    <div class="hidden md:block step-connector"></div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto md:mx-0 mb-4 font-display text-xl" style="background: var(--accent); color: #080810;">2</div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Install Package</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Run <code class="px-1.5 py-0.5 rounded text-xs" style="background: rgba(0,194,255,0.1); color: var(--accent);">composer require darknautica/volta-php</code> and add your key to .env.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative text-center md:text-left">
                    <div class="hidden md:block step-connector"></div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto md:mx-0 mb-4 font-display text-xl" style="background: var(--accent); color: #080810;">3</div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Wrap AI Calls</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Wrap your AI API calls with <code class="px-1.5 py-0.5 rounded text-xs" style="background: rgba(0,194,255,0.1); color: var(--accent);">Volta::charge()</code> to bill per request.</p>
                </div>

                <!-- Step 4 -->
                <div class="relative text-center md:text-left">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto md:mx-0 mb-4 font-display text-xl" style="background: var(--accent); color: #080810;">4</div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Connect Stripe</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Link your Stripe account. Volta syncs plans, handles webhooks, and starts collecting payments.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRICING ===== -->
    <section id="pricing" class="py-20 md:py-28 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl md:text-5xl tracking-wide mb-4" style="color: var(--text);">SIMPLE PRICING</h2>
                <p class="text-lg max-w-xl mx-auto" style="color: var(--muted);">No per-transaction fees. No revenue share. Just a flat monthly price.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <!-- Indie -->
                <div class="rounded-xl p-8 flex flex-col" style="background: var(--surface); border: 1px solid var(--border);">
                    <h3 class="font-display text-2xl tracking-wide mb-1" style="color: var(--text);">INDIE</h3>
                    <p class="text-sm mb-6" style="color: var(--muted);">For solo builders</p>
                    <div class="mb-6">
                        <span class="font-display text-5xl" style="color: var(--text);">$19</span>
                        <span class="text-sm" style="color: var(--muted);">/mo</span>
                    </div>
                    <ul class="flex-1 space-y-3 mb-8 text-sm" style="color: var(--muted);">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            1 app
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited end users
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Credit system
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Usage dashboard
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Community support
                        </li>
                    </ul>
                    <a href="/register" class="block text-center text-sm font-semibold px-6 py-3 rounded-lg border transition-colors hover:bg-white/5" style="border-color: var(--border); color: var(--text);">
                        Get started
                    </a>
                </div>

                <!-- Studio (highlighted) -->
                <div class="rounded-xl p-8 flex flex-col pricing-highlight relative" style="background: var(--surface); border: 2px solid var(--accent);">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 text-xs font-semibold px-3 py-1 rounded-full" style="background: var(--accent); color: #080810;">
                        Most popular
                    </div>
                    <h3 class="font-display text-2xl tracking-wide mb-1" style="color: var(--text);">STUDIO</h3>
                    <p class="text-sm mb-6" style="color: var(--muted);">For growing teams</p>
                    <div class="mb-6">
                        <span class="font-display text-5xl" style="color: var(--text);">$49</span>
                        <span class="text-sm" style="color: var(--muted);">/mo</span>
                    </div>
                    <ul class="flex-1 space-y-3 mb-8 text-sm" style="color: var(--muted);">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            5 apps
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited end users
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Indie
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Multi-model pricing
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Embeddable billing portal
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Priority support
                        </li>
                    </ul>
                    <a href="/register" class="block text-center text-sm font-semibold px-6 py-3 rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent); color: #080810;">
                        Start $7 trial
                    </a>
                </div>

                <!-- Agency -->
                <div class="rounded-xl p-8 flex flex-col" style="background: var(--surface); border: 1px solid var(--border);">
                    <h3 class="font-display text-2xl tracking-wide mb-1" style="color: var(--text);">AGENCY</h3>
                    <p class="text-sm mb-6" style="color: var(--muted);">For agencies &amp; scale</p>
                    <div class="mb-6">
                        <span class="font-display text-5xl" style="color: var(--text);">$149</span>
                        <span class="text-sm" style="color: var(--muted);">/mo</span>
                    </div>
                    <ul class="flex-1 space-y-3 mb-8 text-sm" style="color: var(--muted);">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited apps
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited end users
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Everything in Studio
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            White-label portal
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Custom integrations
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Dedicated support
                        </li>
                    </ul>
                    <a href="mailto:hello@volta.dev" class="block text-center text-sm font-semibold px-6 py-3 rounded-lg border transition-colors hover:bg-white/5" style="border-color: var(--border); color: var(--text);">
                        Contact sales
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="py-20 md:py-28 px-6" style="background: var(--surface); border-top: 1px solid var(--border);">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="font-display text-4xl md:text-6xl tracking-wide mb-6" style="color: var(--text);">
                STOP BUILDING<br><span style="color: var(--accent);">BILLING TWICE</span>
            </h2>
            <p class="text-lg mb-10 max-w-xl mx-auto" style="color: var(--muted);">
                You already built the AI. Let Volta handle the billing so you can ship faster and keep every dollar.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/register" class="inline-block text-sm font-semibold px-8 py-3.5 rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent); color: #080810;">
                    Start your $7 trial
                </a>
                <a href="/docs" class="inline-block text-sm font-semibold px-8 py-3.5 rounded-lg border transition-colors hover:bg-white/5" style="border-color: var(--border); color: var(--text);">
                    Read the docs
                </a>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="py-12 px-6" style="border-top: 1px solid var(--border);">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <a href="#" class="font-display text-xl tracking-wider" style="color: var(--text);">
                    VOLT<span style="color: var(--accent);">A</span>
                </a>
                <span class="text-sm" style="color: var(--muted);">&copy; {{ date('Y') }} Volta. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="#features" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Features</a>
                <a href="#pricing" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Pricing</a>
                <a href="/docs" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Docs</a>
                <a href="#" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Privacy</a>
                <a href="#" class="text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Terms</a>
            </div>
        </div>
    </footer>

</body>
</html>
