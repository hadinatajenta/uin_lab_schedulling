@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-default focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary))] rounded-md shadow-sm']) !!}>
