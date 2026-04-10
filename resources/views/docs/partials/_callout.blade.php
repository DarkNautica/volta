@php
    $calloutType = $type ?? 'info';
    $calloutTitle = $title ?? null;
    $content = $content ?? '';

    $icons = [
        'info'    => 'ℹ',
        'warning' => '⚠',
        'danger'  => '✕',
        'tip'     => '✓',
    ];
    $icon = $icons[$calloutType] ?? $icons['info'];
@endphp

<style>
.callout {
    border-left: 3px solid;
    border-radius: 0 8px 8px 0;
    padding: 1rem 1.25rem;
    margin: 1.5rem 0;
    font-size: 14.5px;
    line-height: 1.7;
}
.callout-title {
    font-weight: 600; font-size: 13px;
    text-transform: uppercase; letter-spacing: 0.06em;
    margin-bottom: 4px;
}
.callout-info    { background: rgba(0,194,255,0.06); border-color: #00C2FF; color: #1a1a2e; }
.callout-warning { background: rgba(251,191,36,0.08); border-color: #fbbf24; color: #1a1a2e; }
.callout-danger  { background: rgba(239,68,68,0.08); border-color: #ef4444; color: #1a1a2e; }
.callout-tip     { background: rgba(134,239,172,0.08); border-color: #86efac; color: #1a1a2e; }
.callout-info .callout-title    { color: #0099cc; }
.callout-warning .callout-title { color: #d97706; }
.callout-danger .callout-title  { color: #dc2626; }
.callout-tip .callout-title     { color: #16a34a; }
</style>

<div class="callout callout-{{ $calloutType }} mb-6">
    @if($calloutTitle)
        <div class="callout-title">{{ $icon }} {{ $calloutTitle }}</div>
    @endif
    <div>{!! $content !!}</div>
</div>
