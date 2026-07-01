@props(['group', 'isMobile' => false])

@php
    $id = Str::slug($group['title']);
    $hasActiveChild = false;
    
    if (isset($group['children'])) {
        foreach ($group['children'] as $child) {
            if (isset($child['active_matches'])) {
                foreach ($child['active_matches'] as $match) {
                    if (request()->routeIs($match)) {
                        $hasActiveChild = true;
                        break 2;
                    }
                }
            } else if (isset($child['route'])) {
                try {
                    if (request()->routeIs($child['route'])) {
                        $hasActiveChild = true;
                        break;
                    }
                } catch (\Exception $e) {}
            }
        }
    }

    $isGroupActive = $hasActiveChild;
    
    $buttonClasses = 'w-full group flex items-center justify-between rounded-xl outline-none focus-visible:ring-2 focus-visible:ring-primary';
    $spacingClasses = $isMobile ? 'px-3 h-11' : '';

    if ($isGroupActive) {
        $stateClasses = 'text-primary font-semibold';
        $iconClasses = 'text-primary';
    } else {
        $stateClasses = 'text-nav hover:bg-nav-hover hover:text-foreground font-medium';
        $iconClasses = 'text-foreground-muted group-hover:text-nav';
    }
@endphp

<li class="relative" x-init="if ({{ $isGroupActive ? 'true' : 'false' }}) { $store.sidebar.setOpen('{{ $id }}', true) }">
    <button 
        @click="if(!$store.sidebar.expanded && {{ $isMobile ? 'false' : 'true' }}) { $store.sidebar.toggleExpanded(); $store.sidebar.setOpen('{{ $id }}', true); } else { $store.sidebar.toggle('{{ $id }}') }"
        class="{{ $buttonClasses }} {{ $spacingClasses }} {{ $stateClasses }}"
        aria-haspopup="true"
        :aria-expanded="$store.sidebar.isOpen('{{ $id }}')"
        @if(!$isMobile)
            :class="!$store.sidebar.expanded ? 'justify-center p-2.5 mx-auto' : 'px-3 h-11'"
            x-data="{ tooltipVisible: false }"
            @mouseenter="tooltipVisible = true"
            @mouseleave="tooltipVisible = false"
        @endif
    >
        <div class="flex items-center">
            @if(isset($group['icon']))
                <span 
                    class="material-symbols-rounded shrink-0 {{ $iconClasses }} {{ $isMobile ? 'text-[20px] mr-3' : '' }}" 
                    @if(!$isMobile)
                        x-bind:class="!$store.sidebar.expanded ? 'text-[24px]' : 'text-[20px] mr-3'"
                    @endif
                >{{ $group['icon'] }}</span>
            @endif
            
            <span 
                class="truncate text-[14px]"
                @if(!$isMobile)
                    x-show="$store.sidebar.expanded"
                    x-show="$store.sidebar.expanded"
                @endif
            >
                {{ $group['title'] }}
            </span>
        </div>

        <svg 
            @if(!$isMobile)
                x-show="$store.sidebar.expanded"
            @endif
            class="w-4 h-4 {{ $iconClasses }}"
            :class="{'rotate-180': $store.sidebar.isOpen('{{ $id }}')}" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>

        @if(!$isMobile)
            <!-- Tooltip -->
            <div 
                x-show="tooltipVisible && !$store.sidebar.expanded"
                class="absolute left-full ml-3 px-2.5 py-1.5 bg-tooltip text-tooltip text-xs font-medium rounded-md whitespace-nowrap shadow-md z-50 pointer-events-none"
                style="display: none;"
                x-cloak
            >
                {{ $group['title'] }}
                <div class="absolute w-2 h-2 bg-tooltip rotate-45 -left-1 top-1/2 -translate-y-1/2"></div>
            </div>
        @endif
    </button>

    <!-- Children Menu -->
    <ul 
        x-show="$store.sidebar.isOpen('{{ $id }}') && ($store.sidebar.expanded || {{ $isMobile ? 'true' : 'false' }})"
        class="mt-1 space-y-0.5 relative overflow-hidden"
        style="display: none;"
    >
        @foreach($group['children'] as $child)
            <x-navigation.nav-item :item="$child" :isMobile="$isMobile" :nested="true" />
        @endforeach
    </ul>
</li>
