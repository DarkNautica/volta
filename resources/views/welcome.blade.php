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

    <!-- Alpine.js Intersect Plugin + Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
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
            opacity: 0;
            animation: pageFadeIn 0.3s ease forwards;
        }

        @keyframes pageFadeIn {
            to { opacity: 1; }
        }

        .font-display {
            font-family: 'Bebas Neue', sans-serif;
        }

        /* ===== NAV ===== */
        .nav-bar {
            background: transparent;
            border-bottom: 1px solid transparent;
            transition: background 0.3s, border-color 0.3s;
        }
        .nav-bar.scrolled {
            background: rgba(8,8,16,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom-color: rgba(255,255,255,0.07);
        }

        .nav-link {
            position: relative;
            transition: color 0.2s;
        }
        .nav-link.active {
            color: var(--accent) !important;
        }

        /* CTA pulse */
        @keyframes ctaPulse {
            0% { box-shadow: 0 0 0 0 rgba(0,194,255,0.5); }
            70% { box-shadow: 0 0 0 12px rgba(0,194,255,0); }
            100% { box-shadow: 0 0 0 0 rgba(0,194,255,0); }
        }
        .cta-pulse {
            animation: ctaPulse 0.8s ease-out 0.5s 1;
        }

        /* ===== HERO ===== */
        /* Typewriter */
        @keyframes typewriter {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blinkCaret {
            0%, 100% { border-color: var(--accent); }
            50% { border-color: transparent; }
        }
        .typewriter-wrapper {
            display: inline-block;
            position: relative;
        }
        .typewriter-text {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            width: 0;
            border-right: 3px solid var(--accent);
            animation: typewriter 0.6s steps(8) 0.3s forwards, blinkCaret 0.5s step-end 0.3s 3;
        }

        /* Hero glow */
        .hero-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,194,255,0.08) 0%, transparent 70%);
            pointer-events: none;
            animation: drift 8s ease-in-out infinite;
            top: -100px;
            right: -100px;
        }
        @keyframes drift {
            0%   { transform: translate(0, 0); }
            50%  { transform: translate(30px, -20px); }
            100% { transform: translate(0, 0); }
        }

        /* Hero buttons */
        .hero-btn-primary {
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s;
        }
        .hero-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,194,255,0.25);
        }
        .hero-btn-ghost {
            transition: border-color 0.2s ease, background 0.2s ease;
        }
        .hero-btn-ghost:hover {
            border-color: var(--accent) !important;
            background: rgba(255,255,255,0.03);
        }

        /* Scroll indicator */
        @keyframes bounce-down {
            0%, 100% { transform: translateY(0); opacity: 0.6; }
            50% { transform: translateY(8px); opacity: 1; }
        }
        .scroll-indicator {
            animation: bounce-down 2s ease-in-out infinite;
            transition: opacity 0.3s;
        }

        /* Code block */
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
        .code-tab {
            padding: 8px 16px;
            font-size: 12px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: color 0.2s, border-color 0.2s;
            color: var(--muted);
            font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
        }
        .code-tab:hover { color: var(--text); }
        .code-tab.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }
        .code-block pre {
            padding: 24px;
            margin: 0;
            font-size: 14px;
            line-height: 1.8;
            overflow-x: auto;
            min-height: 110px;
        }
        .code-block code {
            font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
        }
        .code-fade-enter { animation: codeFadeIn 0.15s ease forwards; }
        @keyframes codeFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .syn-class { color: #00C2FF; }
        .syn-method { color: #c792ea; }
        .syn-paren { color: rgba(240,240,255,0.4); }
        .syn-var { color: #f78c6c; }
        .syn-semi { color: rgba(240,240,255,0.3); }
        .syn-scope { color: #89ddff; }
        .syn-comment { color: rgba(240,240,255,0.3); font-style: italic; }
        .syn-string { color: #c3e88d; }
        .syn-keyword { color: #c792ea; }
        .syn-num { color: #f78c6c; }

        /* ===== METRICS ===== */
        .metrics-line {
            height: 2px;
            background: var(--accent);
            transform-origin: left;
            transform: scaleX(0);
            transition: transform 1.2s ease-out;
        }
        .metrics-line.animate { transform: scaleX(1); }

        /* ===== FEATURE CARDS ===== */
        .feature-card {
            transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }
        .feature-card:hover {
            border-color: rgba(0,194,255,0.2) !important;
            transform: translateY(-2px);
            box-shadow: 0 0 0 1px rgba(0,194,255,0.15), 0 8px 32px rgba(0,194,255,0.06);
        }
        .feature-icon {
            transition: background 0.3s ease;
        }
        .feature-card:hover .feature-icon {
            animation: iconPulse 0.4s ease;
        }
        @keyframes iconPulse {
            0% { background: rgba(0,194,255,0.1); }
            50% { background: rgba(0,194,255,0.2); }
            100% { background: rgba(0,194,255,0.1); }
        }

        /* ===== Scroll animations — progressive enhancement ===== */
        /* Default: always visible */
        .scroll-animate {
            opacity: 1;
            transform: none;
        }
        /* Only hidden when JS has confirmed IntersectionObserver support */
        .scroll-animate.animate-ready {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease-out, transform 0.4s ease-out;
        }
        /* Visible once scrolled into view */
        .scroll-animate.animate-ready.animate-in {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== HOW IT WORKS stepper ===== */
        .stepper-line {
            position: absolute;
            top: 24px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border);
        }
        .stepper-line-fill {
            position: absolute;
            top: 24px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 1.2s ease-out;
        }
        .stepper-line-fill.animate {
            width: 100%;
        }

        /* Mobile vertical line */
        @media (max-width: 767px) {
            .stepper-line, .stepper-line-fill { display: none; }
            .mobile-vertical-line {
                position: absolute;
                left: 24px;
                top: 48px;
                bottom: 0;
                width: 2px;
                background: var(--border);
            }
            .mobile-vertical-line-fill {
                position: absolute;
                left: 24px;
                top: 48px;
                width: 2px;
                height: 0;
                background: var(--accent);
                transition: height 1.2s ease-out;
            }
            .mobile-vertical-line-fill.animate {
                height: calc(100% - 48px);
            }
        }
        @media (min-width: 768px) {
            .mobile-vertical-line, .mobile-vertical-line-fill { display: none; }
        }

        /* ===== PRICING ===== */
        .pricing-highlight {
            border-color: var(--accent) !important;
            box-shadow: 0 -4px 24px rgba(0,194,255,0.15), 0 0 40px rgba(0,194,255,0.08);
        }

        .toggle-switch {
            width: 48px;
            height: 26px;
            border-radius: 13px;
            background: rgba(255,255,255,0.1);
            position: relative;
            cursor: pointer;
            transition: background 0.2s;
        }
        .toggle-switch.active {
            background: var(--accent);
        }
        .toggle-switch .toggle-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            position: absolute;
            top: 3px;
            left: 3px;
            transition: transform 0.2s;
        }
        .toggle-switch.active .toggle-dot {
            transform: translateX(22px);
        }

        .price-fade {
            transition: opacity 0.15s ease;
        }

        /* ===== CTA SECTION ===== */
        @keyframes grid-scroll {
            from { background-position: 0 0; }
            to { background-position: 40px 40px; }
        }
        .cta-grid-bg {
            background-image:
                linear-gradient(rgba(0,194,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,194,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: grid-scroll 4s linear infinite;
        }

        /* Smooth scrolling */
        html { scroll-behavior: smooth; }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="antialiased"
      x-data="{
          mobileMenu: false,
          scrolled: false,
          activeSection: '',
          heroVisible: true
      }"
      @scroll.window="
          scrolled = window.scrollY > 60;
          heroVisible = window.scrollY < 600;
          let sections = ['features', 'how-it-works', 'pricing'];
          let current = '';
          sections.forEach(id => {
              let el = document.getElementById(id);
              if (el && el.getBoundingClientRect().top < 200) current = id;
          });
          activeSection = current;
      ">

    <!-- ===== NAV ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 nav-bar" :class="{ 'scrolled': scrolled }">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <!-- Logo -->
            <a href="#" class="font-display text-2xl tracking-wider" style="color: var(--text);">
                VOLT<span style="color: var(--accent);">A</span>
            </a>

            <!-- Desktop Links -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="nav-link text-sm hover:opacity-100 transition-opacity" :class="{ 'active': activeSection === 'features' }" style="color: var(--muted);">Features</a>
                <a href="#how-it-works" class="nav-link text-sm hover:opacity-100 transition-opacity" :class="{ 'active': activeSection === 'how-it-works' }" style="color: var(--muted);">How it works</a>
                <a href="#pricing" class="nav-link text-sm hover:opacity-100 transition-opacity" :class="{ 'active': activeSection === 'pricing' }" style="color: var(--muted);">Pricing</a>
                <a href="/docs" class="nav-link text-sm hover:opacity-100 transition-opacity" style="color: var(--muted);">Docs</a>
                <a href="/register" class="cta-pulse text-sm font-semibold px-5 py-2 rounded-lg transition-opacity hover:opacity-90" style="background: var(--accent); color: #080810;">
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
    <section class="relative pt-32 pb-20 md:pt-44 md:pb-28 px-6 overflow-hidden">
        <div class="hero-glow"></div>
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center relative z-10">
            <!-- Left -->
            <div>
                <h1 class="font-display text-5xl md:text-7xl lg:text-8xl leading-none tracking-wide mb-6" style="color: var(--text);">
                    AI BILLING,<br>
                    <span class="typewriter-wrapper" style="color: var(--accent);">
                        <span class="typewriter-text">HANDLED.</span>
                    </span>
                </h1>
                <p class="text-lg md:text-xl leading-relaxed mb-8 max-w-lg" style="color: var(--muted);">
                    Drop-in billing infrastructure for AI apps. Charge per token, per request, or per feature &mdash; in three lines of code.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="/register" class="hero-btn-primary inline-block text-sm font-semibold px-7 py-3 rounded-lg" style="background: var(--accent); color: #080810;">
                        Start $7 trial
                    </a>
                    <a href="/docs" class="hero-btn-ghost inline-block text-sm font-semibold px-7 py-3 rounded-lg border" style="border-color: var(--border); color: var(--text);">
                        View docs
                    </a>
                </div>
            </div>

            <!-- Right: Code Block with Tabs -->
            <div class="code-block" x-data="{ tab: 'charge' }">
                <div class="titlebar">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                </div>
                <div class="flex border-b" style="border-color: var(--border);">
                    <button class="code-tab" :class="{ 'active': tab === 'charge' }" @click="tab = 'charge'">charge.php</button>
                    <button class="code-tab" :class="{ 'active': tab === 'hasAccess' }" @click="tab = 'hasAccess'">hasAccess.php</button>
                    <button class="code-tab" :class="{ 'active': tab === 'balance' }" @click="tab = 'balance'">balance.php</button>
                </div>
                <pre><code><template x-if="tab === 'charge'"><span class="code-fade-enter"><span class="syn-comment">// Deduct credits after successful AI call</span>
<span class="syn-class">Volta</span><span class="syn-scope">::</span><span class="syn-method">charge</span><span class="syn-paren">(</span><span class="syn-var">$userId</span><span class="syn-paren">,</span> <span class="syn-num">1</span><span class="syn-paren">,</span> <span class="syn-string">'claude-3-5-sonnet'</span><span class="syn-paren">)</span><span class="syn-semi">;</span></span></template><template x-if="tab === 'hasAccess'"><span class="code-fade-enter"><span class="syn-comment">// Check before calling AI</span>
<span class="syn-keyword">if</span> <span class="syn-paren">(!</span><span class="syn-class">Volta</span><span class="syn-scope">::</span><span class="syn-method">hasAccess</span><span class="syn-paren">(</span><span class="syn-var">$userId</span><span class="syn-paren">,</span> <span class="syn-num">2</span><span class="syn-paren">))</span> <span class="syn-paren">{</span>
    <span class="syn-keyword">return</span> <span class="syn-method">response</span><span class="syn-paren">()</span><span class="syn-scope">-></span><span class="syn-method">json</span><span class="syn-paren">([</span><span class="syn-string">'error'</span> <span class="syn-scope">=></span> <span class="syn-string">'insufficient_credits'</span><span class="syn-paren">],</span> <span class="syn-num">402</span><span class="syn-paren">)</span><span class="syn-semi">;</span>
<span class="syn-paren">}</span></span></template><template x-if="tab === 'balance'"><span class="code-fade-enter"><span class="syn-comment">// Display in your UI</span>
<span class="syn-var">$balance</span> <span class="syn-scope">=</span> <span class="syn-class">Volta</span><span class="syn-scope">::</span><span class="syn-method">balance</span><span class="syn-paren">(</span><span class="syn-var">$userId</span><span class="syn-paren">)</span><span class="syn-semi">;</span> <span class="syn-comment">// &rarr; 142</span></span></template></code></pre>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="flex justify-center mt-12 md:mt-16 scroll-indicator" :style="heroVisible ? 'opacity:1' : 'opacity:0; pointer-events:none'">
            <svg class="w-6 h-6" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </section>

    <!-- ===== METRICS BAR ===== -->
    <section class="relative py-12 px-6" style="background: var(--surface); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);"
             x-data="{ started: false }"
             x-intersect.once="started = true">
        <div class="metrics-line absolute top-0 left-0 right-0" :class="{ 'animate': started }"></div>
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="font-display text-4xl md:text-5xl mb-2" style="color: var(--accent);"
                     x-data="{ val: 3 }"
                     x-effect="if (started) { val = 0; let n = 0; let t = setInterval(() => { n++; val = n; if (n >= 3) clearInterval(t); }, 200); }"
                     x-text="val">3</div>
                <div class="text-sm" style="color: var(--muted);">Lines to integrate</div>
            </div>
            <div>
                <div class="font-display text-4xl md:text-5xl mb-2" style="color: var(--accent);"
                     x-data="{ val: 100 }"
                     x-effect="if (started) { val = 0; let n = 0; let t = setInterval(() => { n += 5; val = n; if (n >= 100) clearInterval(t); }, 60); }"
                     x-text="val + '%'">100%</div>
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
                <div class="feature-card scroll-animate rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border); transition-delay: 0ms;">
                    <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Credit System</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Flexible credit-based billing. Let users prepay for AI usage and deduct credits per call, per token, or per feature.</p>
                </div>

                <!-- Card 2 -->
                <div class="feature-card scroll-animate rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border); transition-delay: 100ms;">
                    <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Stripe Zero Config</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Connect your Stripe account and go. Volta handles checkout sessions, webhooks, and subscription management automatically.</p>
                </div>

                <!-- Card 3 -->
                <div class="feature-card scroll-animate rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border); transition-delay: 200ms;">
                    <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Rate Limiting</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Protect your margins with built-in rate limits. Set per-user, per-plan, or global limits to prevent runaway API costs.</p>
                </div>

                <!-- Card 4 -->
                <div class="feature-card scroll-animate rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border); transition-delay: 300ms;">
                    <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Usage Dashboard</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Real-time analytics for every user. Track spend, usage patterns, and revenue with a pre-built dashboard you can customize.</p>
                </div>

                <!-- Card 5 -->
                <div class="feature-card scroll-animate rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border); transition-delay: 400ms;">
                    <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Multi-Model Pricing</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--muted);">Different models cost different amounts. Set per-model credit costs so GPT-4 charges more than GPT-3.5 automatically.</p>
                </div>

                <!-- Card 6 -->
                <div class="feature-card scroll-animate rounded-xl p-6" style="background: var(--surface); border: 1px solid var(--border); transition-delay: 500ms;">
                    <div class="feature-icon w-10 h-10 rounded-lg flex items-center justify-center mb-4 text-lg font-bold" style="background: rgba(0,194,255,0.1); color: var(--accent);">
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

            <div class="relative"
                 x-data="{ shown: false }"
                 x-intersect.once="shown = true">
                <!-- Desktop horizontal line -->
                <div class="hidden md:block stepper-line"></div>
                <div class="hidden md:block stepper-line-fill" :class="{ 'animate': shown }"></div>

                <!-- Mobile vertical line -->
                <div class="md:hidden mobile-vertical-line"></div>
                <div class="md:hidden mobile-vertical-line-fill" :class="{ 'animate': shown }"></div>

                <div class="grid md:grid-cols-4 gap-8 md:gap-6">
                    <!-- Step 1 -->
                    <div class="relative scroll-animate md:text-left flex md:block items-start gap-4" style="transition-delay: 0ms;">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 md:mb-4 font-display text-xl" style="background: var(--accent); color: #080810;">1</div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Create App</h3>
                            <p class="text-sm leading-relaxed" style="color: var(--muted);">Sign up and create a new app in the Volta dashboard. Get your API key in seconds.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative scroll-animate md:text-left flex md:block items-start gap-4" style="transition-delay: 150ms;">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 md:mb-4 font-display text-xl" style="background: var(--accent); color: #080810;">2</div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Install Package</h3>
                            <p class="text-sm leading-relaxed" style="color: var(--muted);">Run <code class="px-1.5 py-0.5 rounded text-xs" style="background: rgba(0,194,255,0.1); color: var(--accent);">composer require darknautica/volta-php</code> and add your key to .env.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative scroll-animate md:text-left flex md:block items-start gap-4" style="transition-delay: 300ms;">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 md:mb-4 font-display text-xl" style="background: var(--accent); color: #080810;">3</div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Wrap AI Calls</h3>
                            <p class="text-sm leading-relaxed" style="color: var(--muted);">Wrap your AI API calls with <code class="px-1.5 py-0.5 rounded text-xs" style="background: rgba(0,194,255,0.1); color: var(--accent);">Volta::charge()</code> to bill per request.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative scroll-animate md:text-left flex md:block items-start gap-4" style="transition-delay: 450ms;">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 md:mb-4 font-display text-xl" style="background: var(--accent); color: #080810;">4</div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2" style="color: var(--text);">Connect Stripe</h3>
                            <p class="text-sm leading-relaxed" style="color: var(--muted);">Link your Stripe account. Volta syncs plans, handles webhooks, and starts collecting payments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRICING ===== -->
    <section id="pricing" class="py-20 md:py-28 px-6">
        <div class="max-w-6xl mx-auto" x-data="{ annual: false }">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl md:text-5xl tracking-wide mb-4" style="color: var(--text);">SIMPLE PRICING</h2>
                <p class="text-lg max-w-xl mx-auto mb-8" style="color: var(--muted);">No per-transaction fees. No revenue share. Just a flat monthly price.</p>

                <!-- Billing toggle -->
                <div class="flex items-center justify-center gap-3">
                    <span class="text-sm" :style="!annual ? 'color: var(--text)' : 'color: var(--muted)'">Monthly</span>
                    <div class="toggle-switch" :class="{ 'active': annual }" @click="annual = !annual">
                        <div class="toggle-dot"></div>
                    </div>
                    <span class="text-sm" :style="annual ? 'color: var(--text)' : 'color: var(--muted)'">Annual</span>
                    <span x-show="annual" x-transition class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background: rgba(0,194,255,0.15); color: var(--accent);">Save 20%</span>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <!-- Indie -->
                <div class="scroll-animate rounded-xl p-8 flex flex-col" style="background: var(--surface); border: 1px solid var(--border); transition-delay: 0ms;">
                    <h3 class="font-display text-2xl tracking-wide mb-1" style="color: var(--text);">INDIE</h3>
                    <p class="text-sm mb-6" style="color: var(--muted);">For solo builders</p>
                    <div class="mb-6 price-fade">
                        <span class="font-display text-5xl" style="color: var(--text);" x-text="annual ? '$15' : '$19'">$19</span>
                        <span class="text-sm" style="color: var(--muted);">/mo</span>
                        <div x-show="annual" x-transition class="text-xs mt-1" style="color: var(--muted);">billed $182/yr</div>
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
                <div class="scroll-animate rounded-xl p-8 flex flex-col pricing-highlight relative" style="background: var(--surface); border: 2px solid var(--accent); transition-delay: 100ms;">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 text-xs font-semibold px-3 py-1 rounded-full" style="background: var(--accent); color: #080810;">
                        Most popular
                    </div>
                    <h3 class="font-display text-2xl tracking-wide mb-1" style="color: var(--text);">STUDIO</h3>
                    <p class="text-sm mb-6" style="color: var(--muted);">For growing teams</p>
                    <div class="mb-6 price-fade">
                        <span class="font-display text-5xl" style="color: var(--text);" x-text="annual ? '$39' : '$49'">$49</span>
                        <span class="text-sm" style="color: var(--muted);">/mo</span>
                        <div x-show="annual" x-transition class="text-xs mt-1" style="color: var(--muted);">billed $468/yr</div>
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
                <div class="scroll-animate rounded-xl p-8 flex flex-col" style="background: var(--surface); border: 1px solid var(--border); transition-delay: 200ms;">
                    <h3 class="font-display text-2xl tracking-wide mb-1" style="color: var(--text);">AGENCY</h3>
                    <p class="text-sm mb-6" style="color: var(--muted);">For agencies &amp; scale</p>
                    <div class="mb-6 price-fade">
                        <span class="font-display text-5xl" style="color: var(--text);" x-text="annual ? '$119' : '$149'">$149</span>
                        <span class="text-sm" style="color: var(--muted);">/mo</span>
                        <div x-show="annual" x-transition class="text-xs mt-1" style="color: var(--muted);">billed $1,428/yr</div>
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
    <section class="relative py-20 md:py-28 px-6 cta-grid-bg" style="background-color: var(--surface); border-top: 1px solid var(--border);">
        <div class="max-w-3xl mx-auto text-center relative z-10">
            <h2 class="font-display text-4xl md:text-6xl tracking-wide mb-6 scroll-animate" style="color: var(--text);">
                STOP BUILDING<br><span style="color: var(--accent);">BILLING TWICE</span>
            </h2>
            <p class="text-lg mb-10 max-w-xl mx-auto scroll-animate" style="color: var(--muted); transition-delay: 100ms;">
                You already built the AI. Let Volta handle the billing so you can ship faster and keep every dollar.
            </p>
            <div class="flex flex-wrap justify-center gap-4 scroll-animate" style="transition-delay: 200ms;">
                <a href="/register" class="hero-btn-primary inline-block font-semibold rounded-lg" style="background: var(--accent); color: #080810; padding: 16px 48px; font-size: 16px;">
                    Start your $7 trial
                </a>
                <a href="/docs" class="hero-btn-ghost inline-block font-semibold rounded-lg border" style="border-color: var(--border); color: var(--text); padding: 16px 48px; font-size: 16px;">
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

    <!-- Scroll animation progressive enhancement -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!('IntersectionObserver' in window)) return;

            document.querySelectorAll('.scroll-animate').forEach(function(el) {
                el.classList.add('animate-ready');
            });

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.scroll-animate').forEach(function(el) {
                observer.observe(el);
            });
        });
    </script>

</body>
</html>
