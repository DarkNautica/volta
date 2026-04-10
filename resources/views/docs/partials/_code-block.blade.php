@php
    $language = $language ?? 'php';
    $lineNumbers = $lineNumbers ?? false;
@endphp

<style>
.code-block-wrap { position: relative; }
.code-block-wrap pre {
    background: #0f1117;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 10px;
    padding: 1.25rem 1.5rem;
    overflow-x: auto;
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: 13.5px;
    line-height: 1.8;
    margin: 0;
}
.tok-keyword  { color: #7dd3fc; }
.tok-class    { color: #00C2FF; }
.tok-method   { color: #c792ea; }
.tok-string   { color: #86efac; }
.tok-comment  { color: rgba(240,240,255,0.35); font-style: italic; }
.tok-variable { color: #f78c6c; }
.tok-number   { color: #fca5a5; }
.tok-operator { color: #89ddff; }
.tok-plain    { color: #e2e8f0; }
.lang-badge {
    position: absolute; top: 10px; right: 14px;
    font-size: 11px; font-weight: 600; letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #00C2FF;
    font-family: 'DM Sans', sans-serif;
}
.copy-btn {
    position: absolute; top: 8px; right: 60px;
    font-size: 11px; padding: 3px 10px;
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 4px; background: transparent;
    color: rgba(240,240,255,0.5); cursor: pointer;
    transition: all 0.15s;
}
.copy-btn:hover { border-color: #00C2FF; color: #00C2FF; }
.copy-btn.copied { color: #86efac; border-color: #86efac; }
</style>

<div x-data="{ copied: false }" class="code-block-wrap mb-6">
    <span class="lang-badge">{{ $language }}</span>
    <button
        class="copy-btn"
        :class="copied && 'copied'"
        @click="
            const code = $el.closest('.code-block-wrap').querySelector('code').innerText;
            navigator.clipboard.writeText(code);
            copied = true;
            setTimeout(() => copied = false, 2000);
        "
    >
        <span x-show="!copied">Copy</span>
        <span x-show="copied" x-cloak>Copied!</span>
    </button>
    <pre><code class="tok-plain" data-lang="{{ $language }}">{{ $code }}</code></pre>
</div>

<script>
(function() {
    function highlightPHP(text) {
        var tokens = [];
        var re = /(\/\/[^\n]*|\/\*[\s\S]*?\*\/)|('(?:[^'\\]|\\.)*'|"(?:[^"\\]|\\.)*")|(\$[a-zA-Z_]\w*)|(\b\d+(?:\.\d+)?\b)|(::|\->|=>|=|\.|!==|===|!= |==|\|\||&&)|(\b(?:use|return|namespace|class|function|if|else|elseif|try|catch|throw|new|public|protected|private|static|const|foreach|as|for|while|do|switch|case|break|continue|default|array|true|false|null|fn|match|enum|interface|abstract|extends|implements|yield|readonly)\b)|(\b[A-Z][a-zA-Z0-9_]*\b)/g;
        var lastIndex = 0;
        var m;
        while ((m = re.exec(text)) !== null) {
            if (m.index > lastIndex) {
                tokens.push({ type: 'plain', value: text.slice(lastIndex, m.index) });
            }
            if (m[1]) tokens.push({ type: 'comment', value: m[0] });
            else if (m[2]) tokens.push({ type: 'string', value: m[0] });
            else if (m[3]) tokens.push({ type: 'variable', value: m[0] });
            else if (m[4]) tokens.push({ type: 'number', value: m[0] });
            else if (m[5]) tokens.push({ type: 'operator', value: m[0] });
            else if (m[6]) tokens.push({ type: 'keyword', value: m[0] });
            else if (m[7]) tokens.push({ type: 'class', value: m[0] });
            lastIndex = re.lastIndex;
        }
        if (lastIndex < text.length) {
            tokens.push({ type: 'plain', value: text.slice(lastIndex) });
        }
        return tokens;
    }

    function highlightBash(text) {
        var tokens = [];
        var re = /(#[^\n]*)|((?:'[^']*'|"[^"]*"))|(\s--?[a-zA-Z][\w-]*)|(\b(?:composer|php|artisan|npm|npx|yarn|curl|git|mkdir|cd|cp|mv|rm|echo|cat|sudo|apt|brew|pip|python|node)\b)/g;
        var lastIndex = 0;
        var m;
        while ((m = re.exec(text)) !== null) {
            if (m.index > lastIndex) {
                tokens.push({ type: 'plain', value: text.slice(lastIndex, m.index) });
            }
            if (m[1]) tokens.push({ type: 'comment', value: m[0] });
            else if (m[2]) tokens.push({ type: 'string', value: m[0] });
            else if (m[3]) tokens.push({ type: 'variable', value: m[0] });
            else if (m[4]) tokens.push({ type: 'string', value: m[0] });
            lastIndex = re.lastIndex;
        }
        if (lastIndex < text.length) {
            tokens.push({ type: 'plain', value: text.slice(lastIndex) });
        }
        return tokens;
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function applyHighlighting() {
        document.querySelectorAll('.code-block-wrap code[data-lang]').forEach(function(el) {
            if (el.dataset.highlighted) return;
            el.dataset.highlighted = '1';
            var lang = el.dataset.lang;
            var text = el.textContent;
            var tokens;
            if (lang === 'bash' || lang === 'shell') {
                tokens = highlightBash(text);
            } else if (lang === 'php' || lang === 'env' || lang === 'text') {
                tokens = highlightPHP(text);
            } else {
                tokens = highlightPHP(text);
            }
            var html = tokens.map(function(t) {
                return '<span class="tok-' + t.type + '">' + escapeHtml(t.value) + '</span>';
            }).join('');
            el.innerHTML = html;
        });
    }

    // Check if the PHP code also needs method highlighting after :: or ->
    // We handle this by post-processing: find operator spans followed by plain spans starting with a word
    function enhanceMethods() {
        document.querySelectorAll('.code-block-wrap code[data-lang]').forEach(function(el) {
            if (el.dataset.methodsEnhanced) return;
            el.dataset.methodsEnhanced = '1';
            el.innerHTML = el.innerHTML.replace(
                /(<span class="tok-operator">::<\/span>|<span class="tok-operator">-&gt;<\/span>)(<span class="tok-plain">)(\w+)/g,
                '$1<span class="tok-method">$3</span'
            );
            // Also fix method calls that ended up as class tokens after :: or ->
            el.innerHTML = el.innerHTML.replace(
                /(<span class="tok-operator">::<\/span>|<span class="tok-operator">-&gt;<\/span>)<span class="tok-class">(\w+)<\/span>/g,
                '$1<span class="tok-method">$2</span>'
            );
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { applyHighlighting(); enhanceMethods(); });
    } else {
        applyHighlighting(); enhanceMethods();
    }
})();
</script>
