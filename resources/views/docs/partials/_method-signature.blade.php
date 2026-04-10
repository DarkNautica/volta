<style>
.method-sig {
    background: #0a0a14;
    border: 1px solid rgba(0,194,255,0.2);
    border-radius: 8px;
    padding: 1rem 1.5rem;
    font-family: 'JetBrains Mono', monospace;
    font-size: 14px;
    margin: 1.5rem 0;
    overflow-x: auto;
    white-space: nowrap;
}
.sig-class  { color: #00C2FF; font-weight: 600; }
.sig-method { color: #c792ea; font-weight: 600; }
.sig-scope  { color: #89ddff; }
.sig-param  { color: #f78c6c; }
.sig-type   { color: rgba(240,240,255,0.45); font-size: 13px; }
.sig-return { color: #86efac; }
</style>

<div class="method-sig mb-6">
    <code>
        <span class="sig-class">{{ $method }}</span><span class="sig-scope">(</span><span class="sig-param">{{ $params ?? '' }}</span><span class="sig-scope">): </span><span class="sig-return">{{ $returns ?? 'void' }}</span>
    </code>
</div>
