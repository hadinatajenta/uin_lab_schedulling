@props(['type' => 'neutral'])

@php
    $classes = match($type) {
        'success', 'primary' => 'ui-primary-soft ring-[rgb(var(--color-primary-soft))]',
        'warning' => 'ui-warning-soft ring-amber-200',
        'danger' => 'ui-danger-soft ring-rose-200',
        default => 'bg-zinc-50 text-zinc-700 ring-zinc-200',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $classes }}">
    {{ $slot }}
</span>
