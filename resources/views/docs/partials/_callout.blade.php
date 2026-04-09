@php
    $type = $type ?? 'info';
    $title = $title ?? null;
    $content = $content ?? '';

    $styles = [
        'info'    => ['border' => '#3b82f6', 'bg' => '#eff6ff', 'title_color' => '#1d4ed8', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'warning' => ['border' => '#f59e0b', 'bg' => '#fffbeb', 'title_color' => '#b45309', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z'],
        'danger'  => ['border' => '#ef4444', 'bg' => '#fef2f2', 'title_color' => '#b91c1c', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'tip'     => ['border' => '#22c55e', 'bg' => '#f0fdf4', 'title_color' => '#15803d', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
    ];
    $s = $styles[$type] ?? $styles['info'];
@endphp

<div class="rounded-lg p-4 mb-6" style="border-left: 4px solid {{ $s['border'] }}; background-color: {{ $s['bg'] }};">
    <div class="flex gap-3">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: {{ $s['border'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
        </svg>
        <div>
            @if($title)
                <p class="font-semibold text-sm mb-1" style="color: {{ $s['title_color'] }};">{{ $title }}</p>
            @endif
            <div class="text-sm" style="color: #374151; line-height: 1.6;">{!! $content !!}</div>
        </div>
    </div>
</div>
