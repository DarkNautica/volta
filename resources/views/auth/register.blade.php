<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volta - Create Account</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #080810;
            --surface: #0e0e1a;
            --surface2: #141420;
            --accent: #00C2FF;
            --border: rgba(255,255,255,0.07);
            --text: #f0f0ff;
            --muted: rgba(240,240,255,0.5);
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            margin: 0;
        }

        h1, h2, h3 {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.04em;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <span class="text-5xl font-bold tracking-wider" style="font-family: 'Bebas Neue', sans-serif; color: var(--text);">VOLT</span>
            <span class="text-5xl font-bold tracking-wider" style="font-family: 'Bebas Neue', sans-serif; color: var(--accent);">A</span>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl p-8" style="background-color: var(--surface); border: 1px solid var(--border);">
            <h2 class="text-3xl text-center mb-6" style="color: var(--text);">Create Account</h2>

            <form method="POST" action="/register" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text);">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        placeholder="Your name"
                        class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium mb-2" style="color: var(--text);">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="you@example.com"
                        class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium mb-2" style="color: var(--text);">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="Choose a password"
                        class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: var(--text);">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        placeholder="Confirm your password"
                        class="w-full px-4 py-3 rounded-lg text-sm outline-none transition-all focus:ring-2"
                        style="background-color: var(--surface2); border: 1px solid var(--border); color: var(--text); --tw-ring-color: var(--accent);"
                    >
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-3 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                    style="background-color: var(--accent); color: var(--bg);"
                >
                    Create account
                </button>
            </form>

            <p class="text-center text-sm mt-6" style="color: var(--muted);">
                Already have an account?
                <a href="/login" class="hover:underline" style="color: var(--accent);">Sign in</a>
            </p>
        </div>
    </div>

</body>
</html>
