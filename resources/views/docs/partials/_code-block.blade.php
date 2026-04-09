@php
    $language = $language ?? 'php';
    $lineNumbers = $lineNumbers ?? false;
@endphp

<div x-data="{ copied: false }" class="relative rounded-lg overflow-hidden mb-6" style="background-color: #0f1117;">
    <div class="flex items-center justify-between px-4 py-2" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
        <span class="text-xs font-semibold uppercase tracking-wider" style="color: rgba(226,232,240,0.4); font-family: 'JetBrains Mono', monospace;">{{ $language }}</span>
        <button
            @click="
                const code = $el.closest('.relative').querySelector('code').innerText;
                navigator.clipboard.writeText(code);
                copied = true;
                setTimeout(() => copied = false, 2000);
            "
            class="text-xs px-2 py-1 rounded transition-colors"
            style="color: rgba(226,232,240,0.5); background-color: rgba(255,255,255,0.05);"
            :style="copied && 'color: #00C2FF;'"
        >
            <span x-show="!copied">Copy</span>
            <span x-show="copied" x-cloak>Copied!</span>
        </button>
    </div>
    <pre class="overflow-x-auto p-4 text-sm leading-relaxed" style="margin: 0; color: #e2e8f0; font-family: 'JetBrains Mono', monospace;"><code>{{ $code }}</code></pre>
</div>
