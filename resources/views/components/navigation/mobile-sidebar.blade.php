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
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-xl lg:hidden flex flex-col"
    aria-label="Mobile Sidebar"
    style="display: none;"
>
    <!-- Header -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-zinc-100 shrink-0">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white shrink-0 shadow-sm">
                <x-atoms.icon name="grid" class="w-5 h-5" />
            </div>
            <div>
                <h2 class="text-[15px] font-bold text-zinc-900 leading-none tracking-tight">Lab UIN</h2>
                <p class="text-[11px] text-zinc-500 font-medium mt-1 uppercase tracking-wider">Management</p>
            </div>
        </div>
        <button 
            @click="$store.sidebar.isMobileOpen = false"
            class="p-2 text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
            <x-atoms.icon name="x-mark" class="w-6 h-6" />
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
                    <div class="px-2 mb-2 text-xs font-semibold tracking-wider text-zinc-400 uppercase">
                        {{ $section['section'] }}
                    </div>
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
    <div class="mt-auto shrink-0 border-t border-zinc-100 p-3 bg-zinc-50/50">
        <x-navigation.user-menu :isMobile="true" />
    </div>
</aside>
