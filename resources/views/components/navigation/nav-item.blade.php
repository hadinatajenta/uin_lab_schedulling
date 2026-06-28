@props(['item', 'isMobile' => false, 'nested' => false])

@php
    $isActive = false;
    if (isset($item['active_matches'])) {
        foreach ($item['active_matches'] as $match) {
            if (request()->routeIs($match)) {
                $isActive = true;
                break;
            }
        }
    } else if (isset($item['route'])) {
        try {
            $isActive = request()->routeIs($item['route']);
        } catch (\Exception $e) {
        }
    }

    $routeUrl = '#';
    if (isset($item['route'])) {
        try {
            $routeUrl = route($item['route']);
        } catch (\Exception $e) {
        }
    }

    // Base classes
    $baseClasses = 'group flex items-center transition-all duration-200 outline-none focus-visible:ring-2 focus-visible:ring-primary relative';

    // Sizing and rounding
    $sizingClasses = $nested ? 'h-9 rounded-lg' : 'h-11 rounded-xl';

    // Spacing (static for nested/mobile)
    $spacingClasses = '';
    if ($isMobile || $nested) {
        $spacingClasses = $nested ? 'px-3 pl-11 mx-2' : 'px-3';
    }

    // State classes
    if ($isActive) {
        $stateClasses = $nested
            ? 'bg-primary-soft text-foreground font-medium shadow-sm ring-1 ring-ring/5'
            : 'bg-primary-soft text-foreground font-medium shadow-sm ring-1 ring-ring/5';
        $iconClasses = 'text-primary';
    } else {
        $stateClasses = 'text-nav hover:bg-nav-hover hover:text-foreground font-medium';
        $iconClasses = 'text-foreground-muted group-hover:text-nav';
    }
@endphp

<li class="relative">
    @if($isActive && !$nested)
        <!-- Accent Bar for Parent -->
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-primary rounded-r-full z-10 pointer-events-none"
            @if(!$isMobile) x-show="$store.sidebar.expanded" @endif></div>
    @endif

    <a href="{{ $routeUrl }}" class="{{ $baseClasses }} {{ $sizingClasses }} {{ $spacingClasses }} {{ $stateClasses }}"
        {{ $isActive ? 'aria-current="page"' : '' }} @if(!$isMobile && !$nested)
            :class="!$store.sidebar.expanded ? 'justify-center w-11 mx-auto' : 'px-3'" x-data="{ tooltipVisible: false }"
        @mouseenter="tooltipVisible = true" @mouseleave="tooltipVisible = false" @endif>
        @if(isset($item['icon']) && !$nested)
            <span
                class="material-symbols-rounded shrink-0 transition-all duration-200 {{ $iconClasses }} {{ $isMobile ? 'text-[20px] mr-3' : '' }}"
                @if(!$isMobile) x-bind:class="!$store.sidebar.expanded ? 'text-[24px]' : 'text-[20px] mr-3'"
                @endif>{{ $item['icon'] }}</span>
        @endif

        @if(!$nested && !isset($item['icon']))
            <div class="w-5 h-5 mr-3" @if(!$isMobile) x-show="$store.sidebar.expanded" @endif></div>
        @endif

        <span class="truncate {{ $nested ? 'text-[13.5px]' : 'text-[14px]' }}" @if(!$isMobile && !$nested)
        x-show="$store.sidebar.expanded" x-transition.opacity @endif>
            {{ $item['title'] }}
        </span>

        @if(!$isMobile && !$nested)
            <!-- Tooltip -->
            <div x-show="tooltipVisible && !$store.sidebar.expanded" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-2"
                class="absolute left-full ml-3 px-2.5 py-1.5 bg-tooltip text-tooltip text-xs font-medium rounded-md whitespace-nowrap shadow-md z-50 pointer-events-none"
                style="display: none;" x-cloak>
                {{ $item['title'] }}
                <div class="absolute w-2 h-2 bg-tooltip rotate-45 -left-1 top-1/2 -translate-y-1/2"></div>
            </div>
        @endif
    </a>
</li>