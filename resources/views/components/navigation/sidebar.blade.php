@php
    $navigation = config('navigation');
@endphp

<aside 
    class="hidden md:flex fixed top-0 left-0 z-40 h-screen transition-all duration-300 bg-white border-r border-zinc-200/80 shadow-sm flex-col" 
    :class="$store.sidebar.expanded ? 'w-64' : 'w-20'"
    aria-label="Sidebar"
>
    <div class="h-full flex flex-col hide-scrollbar relative">
        
        <!-- Header -->
        <div class="h-16 flex items-center shrink-0 border-b border-zinc-100" :class="$store.sidebar.expanded ? 'px-4 justify-between' : 'px-0 justify-center'">
            <div class="flex items-center space-x-3 cursor-pointer">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white shrink-0 shadow-sm">
                    <x-atoms.icon name="grid" class="w-5 h-5" />
                </div>
                <div x-show="$store.sidebar.expanded" x-transition.opacity.duration.200ms>
                    <h2 class="text-[15px] font-bold text-zinc-900 leading-none tracking-tight">Lab UIN</h2>
                    <p class="text-[11px] text-zinc-500 font-medium mt-1 uppercase tracking-wider">Management</p>
                </div>
            </div>
            
            <button 
                @click="$store.sidebar.toggleExpanded()"
                class="p-1.5 text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 rounded-lg transition-colors outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 hidden lg:block" 
                title="Toggle Sidebar"
                x-show="$store.sidebar.expanded"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
            </button>
        </div>
        
        <!-- Search -->
        <div class="px-3 py-4 shrink-0">
            <x-navigation.search-button :isMobile="false" />
        </div>
        
        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto hide-scrollbar px-3 pb-4 pt-2">
            <ul class="space-y-2">
                @foreach($navigation as $index => $section)
                    @php
                        // Check if any child route is active to keep it open by default
                        $hasActiveChild = false;
                        foreach($section['items'] as $item) {
                            if (isset($item['active_matches'])) {
                                foreach ($item['active_matches'] as $match) {
                                    if (request()->routeIs($match)) {
                                        $hasActiveChild = true;
                                        break 2;
                                    }
                                }
                            } else if (isset($item['route'])) {
                                try {
                                    if (request()->routeIs($item['route'])) {
                                        $hasActiveChild = true;
                                        break;
                                    }
                                } catch (\Exception $e) {}
                            }
                        }
                        
                        // Dashboard section shouldn't be collapsible
                        $isDashboard = $section['section'] === 'GENERAL';
                    @endphp

                    @if($isDashboard)
                        <li>
                            <ul class="space-y-1">
                                @foreach($section['items'] as $item)
                                    @if(isset($item['children']))
                                        <x-navigation.nav-group :group="$item" :isMobile="false" />
                                    @else
                                        <x-navigation.nav-item :item="$item" :isMobile="false" />
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li x-data="{ open: {{ $hasActiveChild ? 'true' : 'false' }} }" class="pt-1">
                            <!-- Parent Toggle -->
                            <button 
                                @click="open = !open"
                                x-show="$store.sidebar.expanded"
                                class="w-full flex items-center justify-between px-3 py-2 text-[11px] font-bold tracking-wider text-zinc-500 uppercase hover:text-zinc-900 transition-colors rounded-lg hover:bg-zinc-50 outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 group"
                                style="display: none;"
                                x-transition.opacity
                            >
                                <span class="group-hover:translate-x-0.5 transition-transform">{{ $section['section'] }}</span>
                                <svg 
                                    class="w-3.5 h-3.5 text-zinc-400 transition-transform duration-200" 
                                    :class="open ? 'rotate-180 text-zinc-600' : ''" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Divider when collapsed -->
                            <div x-show="!$store.sidebar.expanded" class="px-2 py-2 flex justify-center" style="display: none;">
                                <div class="w-5 h-px bg-zinc-200"></div>
                            </div>

                            <!-- Children -->
                            <ul 
                                class="space-y-1 mt-1 relative" 
                                x-show="!$store.sidebar.expanded || open"
                                :class="$store.sidebar.expanded ? 'ml-2 pl-2 border-l border-zinc-100' : ''"
                                style="display: none;"
                            >
                                @foreach($section['items'] as $item)
                                    @if(isset($item['children']))
                                        <x-navigation.nav-group :group="$item" :isMobile="false" />
                                    @else
                                        <x-navigation.nav-item :item="$item" :isMobile="false" />
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        
        <!-- User Menu -->
        <div class="mt-auto shrink-0 border-t border-zinc-100 p-3 bg-zinc-50/50">
            <x-navigation.user-menu :isMobile="false" />
        </div>
    </div>
</aside>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
