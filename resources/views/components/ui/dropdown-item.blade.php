@props(['as' => 'a', 'href' => '#', 'icon' => null, 'shortcut' => null, 'danger' => false])

@php
    $classes = 'group flex items-center justify-between px-2 py-1.5 text-[13px] font-medium rounded-lg transition-colors w-full text-left focus:outline-none ';
    $classes .= $danger ? 'text-red-600 hover:bg-red-50 focus:bg-red-50' : 'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 focus:bg-zinc-100 focus:text-zinc-900';
@endphp

@if ($as === 'button')
    <button type="{{ $attributes->get('type', 'button') }}" {{ $attributes->merge(['class' => $classes]) }}>
        <div class="flex items-center min-w-0">
            @if($icon)
                <span class="material-symbols-rounded text-[16px] mr-2 shrink-0 {{ $danger ? 'text-red-400 group-hover:text-red-500' : 'text-zinc-400 group-hover:text-zinc-600' }}">{{ $icon }}</span>
            @endif
            <span class="truncate">{{ $slot }}</span>
        </div>
        @if($shortcut)
            <span class="ml-4 shrink-0 text-[11px] font-mono tracking-wider {{ $danger ? 'text-red-300' : 'text-zinc-400' }}">{{ $shortcut }}</span>
        @endif
    </button>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <div class="flex items-center min-w-0">
            @if($icon)
                <span class="material-symbols-rounded text-[16px] mr-2 shrink-0 {{ $danger ? 'text-red-400 group-hover:text-red-500' : 'text-zinc-400 group-hover:text-zinc-600' }}">{{ $icon }}</span>
            @endif
            <span class="truncate">{{ $slot }}</span>
        </div>
        @if($shortcut)
            <span class="ml-4 shrink-0 text-[11px] font-mono tracking-wider {{ $danger ? 'text-red-300' : 'text-zinc-400' }}">{{ $shortcut }}</span>
        @endif
    </a>
@endif
