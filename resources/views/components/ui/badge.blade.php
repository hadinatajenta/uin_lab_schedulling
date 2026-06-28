@props(['type' => 'neutral'])

@php
    $classes = match($type) {
        'success', 'primary' => 'ui-primary-soft ring-primary-soft',
        'warning' => 'ui-warning-soft ring-warning-soft',
        'danger' => 'ui-danger-soft ring-danger-soft',
        default => 'bg-surface-muted text-foreground-muted ring-default',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $classes }}">
    {{ $slot }}
</span>
