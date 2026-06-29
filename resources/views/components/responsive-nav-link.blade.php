@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[rgb(var(--color-primary))] text-start text-base font-medium text-[rgb(var(--color-primary))] ui-primary-soft focus:outline-none focus:text-[rgb(var(--color-primary)_/_0.8)] focus:bg-[rgb(var(--color-primary)_/_0.15)] focus:border-[rgb(var(--color-primary))] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-foreground-muted hover:text-foreground hover:bg-surface-muted hover:border-default focus:outline-none focus:text-foreground focus:bg-surface-muted focus:border-default transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
