@props(['as' => 'a', 'href' => '#', 'icon' => null, 'shortcut' => null, 'danger' => false])

@php
    $classes = 'group flex items-center justify-between px-2 py-1.5 text-[13px] font-medium rounded-lg transition-colors w-full text-left focus:outline-none ';
    $classes .= $danger ? 'text-danger hover:bg-danger-soft focus:bg-danger-soft' : 'text-foreground-muted hover:bg-surface-muted hover:text-foreground focus:bg-surface-muted focus:text-foreground';
@endphp

@if ($as === 'button')
    <button type="{{ $attributes->get('type', 'button') }}" {{ $attributes->merge(['class' => $classes]) }}>
        <div class="flex items-center min-w-0">
            @if($icon)
                <span class="material-symbols-rounded text-[16px] mr-2 shrink-0 {{ $danger ? 'text-danger/80 group-hover:text-danger' : 'text-foreground-muted/60 group-hover:text-foreground-muted' }}">{{ $icon }}</span>
            @endif
            <span class="truncate">{{ $slot }}</span>
        </div>
        @if($shortcut)
            <span class="ml-4 shrink-0 text-[11px] font-mono tracking-wider {{ $danger ? 'text-danger/60' : 'text-foreground-muted/60' }}">{{ $shortcut }}</span>
        @endif
    </button>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <div class="flex items-center min-w-0">
            @if($icon)
                <span class="material-symbols-rounded text-[16px] mr-2 shrink-0 {{ $danger ? 'text-danger/80 group-hover:text-danger' : 'text-foreground-muted/60 group-hover:text-foreground-muted' }}">{{ $icon }}</span>
            @endif
            <span class="truncate">{{ $slot }}</span>
        </div>
        @if($shortcut)
            <span class="ml-4 shrink-0 text-[11px] font-mono tracking-wider {{ $danger ? 'text-danger/60' : 'text-foreground-muted/60' }}">{{ $shortcut }}</span>
        @endif
    </a>
@endif
