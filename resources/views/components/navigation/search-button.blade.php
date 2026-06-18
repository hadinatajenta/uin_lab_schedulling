@props(['isMobile' => false])

<button 
    class="w-full flex items-center rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-500 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
    @if(!$isMobile)
        :class="!$store.sidebar.expanded ? 'h-11 justify-center' : 'h-9 px-2.5 justify-between'"
    @else
        class="h-9 px-2.5 justify-between"
    @endif
    <!-- Quick search trigger will be implemented later -->
>
    <div class="flex items-center">
        <x-atoms.icon name="search" class="w-4 h-4" />
        <span 
            class="ml-2.5 text-[13.5px] font-medium"
            @if(!$isMobile)
                x-show="$store.sidebar.expanded"
            @endif
        >Quick Search...</span>
    </div>
    <div 
        class="hidden sm:flex items-center space-x-0.5 text-[10px] font-semibold tracking-wider text-zinc-400"
        @if(!$isMobile)
            x-show="$store.sidebar.expanded"
        @endif
    >
        <span class="px-1.5 py-0.5 rounded-md border border-zinc-200 bg-white">⌘</span>
        <span class="px-1.5 py-0.5 rounded-md border border-zinc-200 bg-white">K</span>
    </div>
</button>
