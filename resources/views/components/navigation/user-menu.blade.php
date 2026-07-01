@props(['isMobile' => false])

{{-- Bottom Profile section untuk sidebar navigasi (navigation/sidebar.blade.php) --}}
{{-- Menggunakan upward-menu component dan logout-confirm-modal --}}

<div class="relative" x-data="{ open: false }" @click.outside="open = false">

    {{-- Upward Dropdown Panel --}}
    <x-ui.upward-menu>

        {{-- User Info Header --}}
        <div class="px-4 py-3.5 border-b border-default bg-surface-muted/40">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-white text-sm shrink-0 shadow-sm"
                     style="background: linear-gradient(135deg, rgb(var(--color-primary)), #60a5fa)">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-[13px] font-bold text-foreground truncate leading-tight">{{ Auth::user()->name ?? 'User Name' }}</p>
                    <p class="text-[11px] text-foreground-muted truncate mt-0.5 leading-none">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                </div>
            </div>
        </div>

        {{-- Menu Items --}}
        <div class="p-1.5 space-y-0.5">
            <a href="{{ url('/admin/profile-settings') }}"
               class="flex items-center gap-2.5 px-3 py-2 text-[13px] font-medium text-foreground-muted hover:bg-surface-muted hover:text-foreground rounded-xl transition-colors">
                <span class="material-symbols-rounded text-[17px]">manage_accounts</span>
                Pengaturan Profil
            </a>
            <a href="{{ url('/admin/profile-settings') }}"
               class="flex items-center gap-2.5 px-3 py-2 text-[13px] font-medium text-foreground-muted hover:bg-surface-muted hover:text-foreground rounded-xl transition-colors">
                <span class="material-symbols-rounded text-[17px]">settings</span>
                Pengaturan
            </a>
        </div>

        {{-- Divider + Logout --}}
        <div class="p-1.5 border-t border-default">
            <button
                type="button"
                @click="open = false; $dispatch('open-logout-modal')"
                class="w-full flex items-center gap-2.5 px-3 py-2 text-[13px] font-medium text-danger hover:bg-danger-soft rounded-xl transition-colors">
                <span class="material-symbols-rounded text-[17px]">logout</span>
                Keluar dari Sistem
            </button>
        </div>
    </x-ui.upward-menu>

    {{-- Seamless Interactive Row Button --}}
    <button
        @click="open = !open"
        class="w-full flex items-center p-2 transition-colors duration-200 rounded-xl outline-none focus-visible:ring-2 focus-visible:ring-primary/30 group border-0"
        @if(!$isMobile)
            :class="{
                'bg-surface-muted': open,
                'bg-transparent hover:bg-surface-muted/50': !open,
                'justify-center': !$store.sidebar.expanded,
                'gap-3': $store.sidebar.expanded
            }"
        @else
            :class="open ? 'bg-surface-muted gap-3' : 'bg-transparent hover:bg-surface-muted/50 gap-3'"
        @endif
    >
        {{-- Avatar (Round & Flat) --}}
        <div class="relative shrink-0">
            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-white text-sm"
                 style="background-color: rgb(var(--color-primary));">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-success rounded-full border-[2px] border-surface"></span>
        </div>

        {{-- Name & Role --}}
        <div
            class="flex-1 text-left overflow-hidden relative z-10"
            @if(!$isMobile) x-show="$store.sidebar.expanded" @endif
        >
            <p class="text-[13px] font-semibold text-foreground truncate leading-tight">{{ Auth::user()->name ?? 'User Name' }}</p>
            <p class="text-[11px] text-foreground-muted font-medium truncate mt-0.5 leading-tight">{{ Auth::user()->role ?? 'Admin Lab' }}</p>
        </div>

        {{-- Chevron --}}
        <div
            class="shrink-0 transition-colors"
            :class="open ? 'text-foreground' : 'text-foreground-muted/50 group-hover:text-foreground-muted'"
            @if(!$isMobile) x-show="$store.sidebar.expanded" @endif
        >
            <span class="material-symbols-rounded text-[20px] transition-transform duration-200"
                  :class="open ? 'rotate-180' : ''">
                expand_more
            </span>
        </div>
    </button>
</div>

{{-- Logout Confirmation Modal (fixed/full-screen) --}}
<x-ui.logout-confirm-modal />
