@props(['isMobile' => false])

<div class="relative" x-data="{ userMenuOpen: false }">
    <button 
        @click="userMenuOpen = !userMenuOpen"
        @click.outside="userMenuOpen = false"
        class="w-full flex items-center rounded-xl transition-colors hover:bg-zinc-200/50 outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
        @if(!$isMobile)
            :class="!$store.sidebar.expanded ? 'p-2 justify-center' : 'p-1.5 justify-between'"
        @else
            class="p-1.5 justify-between"
        @endif
    >
        <div class="flex items-center space-x-3 overflow-hidden">
            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 shrink-0 font-bold text-sm uppercase ring-2 ring-white">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            
            <div 
                class="flex flex-col items-start truncate"
                @if(!$isMobile)
                    x-show="$store.sidebar.expanded"
                @endif
            >
                <span class="text-sm font-semibold text-zinc-900 truncate w-full">{{ Auth::user()->name ?? 'User Name' }}</span>
                <span class="text-[11px] font-medium text-zinc-500 truncate w-full">{{ Auth::user()->role ?? 'Admin Lab' }}</span>
            </div>
        </div>

        <svg 
            @if(!$isMobile)
                x-show="$store.sidebar.expanded"
            @endif
            class="w-4 h-4 text-zinc-400 shrink-0 mr-1 transition-transform duration-200" 
            :class="{'rotate-180': userMenuOpen}"
            fill="none" viewBox="0 0 24 24" stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="userMenuOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95 transform -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 transform -translate-y-2"
        class="absolute bottom-full mb-2 bg-white border border-zinc-200 shadow-lg rounded-xl py-1 z-50 focus:outline-none"
        @if(!$isMobile)
            :class="!$store.sidebar.expanded ? 'left-full ml-3 w-48' : 'left-0 right-0 w-full'"
        @else
            class="left-0 right-0 w-full"
        @endif
        style="display: none;"
        x-cloak
    >
        <div class="px-3 py-2 border-b border-zinc-100 mb-1">
            <p class="text-sm font-medium text-zinc-900 truncate">{{ Auth::user()->name ?? 'User Name' }}</p>
            <p class="text-xs text-zinc-500 truncate">{{ Auth::user()->email ?? 'user@example.com' }}</p>
        </div>
        
        <a href="{{ url('/admin/profile-settings') }}" class="flex items-center px-3 py-1.5 text-sm font-medium text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
            <x-atoms.icon name="settings" class="w-4 h-4 mr-2.5 text-zinc-400" />
            Pengaturan Akun
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="w-full mt-1 border-t border-zinc-100 pt-1">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                <x-atoms.icon name="logout" class="w-4 h-4 mr-2.5 text-red-500" />
                Keluar
            </button>
        </form>
    </div>
</div>
