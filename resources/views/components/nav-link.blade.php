@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[rgb(var(--color-primary))] text-sm font-medium leading-5 text-foreground focus:outline-none focus:border-[rgb(var(--color-primary))] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-foreground-muted hover:text-foreground-muted hover:border-default focus:outline-none focus:text-foreground-muted focus:border-default transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
