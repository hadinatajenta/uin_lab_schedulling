@props([
    'disabled' => false,
    'icon' => null,
    'type' => 'text',
    'alpineLoading' => null,
    'size' => 'md'
])

@php
    $baseClasses = 'block w-full text-zinc-800 border-zinc-200 bg-white focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] shadow-sm transition-colors disabled:opacity-50 disabled:bg-zinc-50';
    
    $sizeClasses = match($size) {
        'sm' => 'py-1.5 text-sm rounded-md',
        default => 'h-11 md:h-10 text-sm md:text-xs font-medium rounded-xl',
    };
    
    $paddingClasses = ($icon || $alpineLoading) ? 'pl-10 pr-4' : 'px-4';
    
    $mergedClasses = "$baseClasses $sizeClasses $paddingClasses border"; 
@endphp

@if($icon || $alpineLoading)
    <div class="relative w-full">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
            @if($icon)
                @if($alpineLoading)
                    <x-atoms.icon :name="$icon" class="w-5 h-5 md:w-4 md:h-4 text-zinc-400" x-show="!{{ $alpineLoading }}" />
                @else
                    <x-atoms.icon :name="$icon" class="w-5 h-5 md:w-4 md:h-4 text-zinc-400" />
                @endif
            @endif
            
            @if($alpineLoading)
                <div class="w-4 h-4 rounded-full border-2 border-zinc-200 border-t-[rgb(var(--color-primary))] animate-spin"
                     x-show="{{ $alpineLoading }}" style="display: none;"></div>
            @endif
        </div>
        <input type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $mergedClasses]) !!}>
    </div>
@else
    <input type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $mergedClasses]) !!}>
@endif
