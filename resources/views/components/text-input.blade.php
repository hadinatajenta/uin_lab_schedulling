@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary))] rounded-md shadow-sm']) !!}>
