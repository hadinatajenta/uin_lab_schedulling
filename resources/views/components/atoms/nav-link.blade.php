@props(['href', 'active' => false])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-2.5 py-1.5 space-x-2.5 rounded-md bg-gray-100 text-gray-900 transition-colors font-medium text-[13px]'
            : 'flex items-center px-2.5 py-1.5 space-x-2.5 rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors font-medium text-[13px]';
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>
