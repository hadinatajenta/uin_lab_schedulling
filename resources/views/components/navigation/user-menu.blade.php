@props(['isMobile' => false])

<div class="relative">
    <x-ui.dropdown align="top-right" width="auto">
        <x-slot name="trigger">
            <button 
                class="w-full flex items-center rounded-xl transition-colors hover:bg-surface-muted outline-none focus-visible:ring-2 focus-visible:ring-primary"
                @if(!$isMobile)
                    :class="!$store.sidebar.expanded ? 'p-2 justify-center' : 'p-1.5 justify-between'"
                @else
                    class="p-1.5 justify-between"
                @endif
            >
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-primary-soft flex items-center justify-center text-primary shrink-0 font-bold text-sm uppercase ring-2 ring-surface">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    
                    <div 
                        class="flex flex-col items-start truncate"
                        @if(!$isMobile)
                            x-show="$store.sidebar.expanded"
                        @endif
                    >
                        <span class="text-sm font-semibold text-foreground truncate w-full">{{ Auth::user()->name ?? 'User Name' }}</span>
                        <span class="text-[11px] font-medium text-foreground-muted truncate w-full">{{ Auth::user()->role ?? 'Admin Lab' }}</span>
                    </div>
                </div>

                <svg 
                    @if(!$isMobile)
                        x-show="$store.sidebar.expanded"
                    @endif
                    class="w-4 h-4 text-foreground-muted shrink-0 mr-1 transition-transform duration-200" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </x-slot>

        <x-slot name="content">
            <x-ui.dropdown-header title="My Account">
                <p class="text-[13px] font-medium text-foreground truncate">{{ Auth::user()->name ?? 'User Name' }}</p>
                <p class="text-[11px] text-foreground-muted truncate">{{ Auth::user()->email ?? 'user@example.com' }}</p>
            </x-ui.dropdown-header>

            <x-ui.dropdown-item href="{{ url('/admin/profile-settings') }}" shortcut="⇧ ⌘ P">
                Profile
            </x-ui.dropdown-item>
            
            <x-ui.dropdown-item href="{{ url('/admin/profile-settings') }}" shortcut="⌘ S">
                Settings
            </x-ui.dropdown-item>

            <x-ui.dropdown-divider />

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <x-ui.dropdown-item as="button" type="submit" shortcut="⇧ ⌘ Q" danger="true">
                    Logout
                </x-ui.dropdown-item>
            </form>
        </x-slot>
    </x-ui.dropdown>
</div>
