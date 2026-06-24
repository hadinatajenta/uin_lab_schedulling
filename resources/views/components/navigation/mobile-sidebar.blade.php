@php
    $navigation = config('navigation');
@endphp

<!-- Mobile Sidebar Overlay -->
<div 
    x-show="$store.sidebar.isMobileOpen"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm z-40 lg:hidden"
    @click="$store.sidebar.isMobileOpen = false"
    style="display: none;"
    aria-hidden="true"
></div>

<!-- Mobile Sidebar Drawer -->
<aside 
    x-show="$store.sidebar.isMobileOpen"
    x-transition:enter="transition ease-in-out duration-300 transform"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    @click.outside="$store.sidebar.isMobileOpen = false"
    @keydown.escape.window="$store.sidebar.isMobileOpen = false"
    class="fixed inset-y-0 left-0 z-50 w-72 ui-surface shadow-xl lg:hidden flex flex-col"
    aria-label="Mobile Sidebar"
    style="display: none;"
>
    <!-- Header -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-zinc-100 shrink-0">
        <div class="flex items-center space-x-3 cursor-pointer">
            <img src="{{ asset('images/logo-uin.svg') }}" alt="Logo UIN" class="w-8 h-8 shrink-0 object-contain" />
            <div>
                <h2 class="text-[15px] font-bold text-zinc-900 leading-none tracking-tight">Lab UIN</h2>
                <p class="text-[11px] text-zinc-500 font-medium mt-1 uppercase tracking-wider">Management</p>
            </div>
        </div>
        <button 
            @click="$store.sidebar.isMobileOpen = false"
            class="p-2 rounded-xl text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))]"
        >
            <span class="material-symbols-rounded text-[24px]">close</span>
        </button>
    </div>

    <!-- Search -->
    <div class="p-4 shrink-0">
        <x-navigation.search-button :isMobile="true" />
    </div>

    <!-- Navigation -->
    <div class="flex-1 px-3 overflow-y-auto hide-scrollbar pb-4">
        <ul class="space-y-6">
            @foreach($navigation as $section)
                <li>
                    @if($section['section'] !== 'GENERAL')
                        <div class="px-2 mb-2 text-xs font-semibold tracking-wider text-zinc-400 uppercase flex items-center">
                            @if(isset($section['icon']))
                                <span class="material-symbols-rounded text-[16px] mr-1.5">{{ $section['icon'] }}</span>
                            @endif
                            {{ $section['section'] }}
                        </div>
                    @endif
                    <ul class="space-y-1">
                        @foreach($section['items'] as $item)
                            @if(isset($item['children']))
                                <x-navigation.nav-group :group="$item" :isMobile="true" />
                            @else
                                <x-navigation.nav-item :item="$item" :isMobile="true" />
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- User Menu -->
    <div class="mt-auto shrink-0 border-t border-[rgb(var(--color-border))] p-3 bg-[rgb(var(--color-surface-muted)_/_0.5)]">
        <x-navigation.user-menu :isMobile="true" />
    </div>
</aside>
