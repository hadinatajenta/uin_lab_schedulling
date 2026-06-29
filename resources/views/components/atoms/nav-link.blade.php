@props(['href', 'active' => false])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-2.5 py-1.5 space-x-2.5 rounded-md bg-surface-muted text-foreground transition-colors font-medium text-[13px]'
            : 'flex items-center px-2.5 py-1.5 space-x-2.5 rounded-md text-foreground-muted hover:bg-surface-muted hover:text-foreground transition-colors font-medium text-[13px]';
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>
