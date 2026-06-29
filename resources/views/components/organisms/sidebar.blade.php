<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-[260px] h-screen transition-transform -translate-x-full sm:translate-x-0 bg-[#FBFBFC] border-r border-default text-foreground flex flex-col" aria-label="Sidebar">
    <div class="h-full px-4 py-5 overflow-y-auto flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3 cursor-pointer hover:opacity-80">
                <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center text-white">
                    <x-atoms.icon name="grid" class="w-5 h-5 text-white" />
                </div>
                <div>
                    <div class="flex items-center space-x-1">
                        <h2 class="text-[15px] font-semibold text-foreground leading-tight">Lab UIN</h2>
                        <svg class="w-3.5 h-3.5 text-foreground-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <p class="text-xs text-foreground-muted font-medium mt-0.5">Sistem Manajemen</p>
                </div>
            </div>
            <button class="p-1.5 text-foreground-muted/60 hover:bg-surface-muted rounded-md transition-colors sm:hidden" data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button">
                <x-atoms.icon name="menu" class="w-5 h-5" />
            </button>
        </div>
        
        <!-- Search -->
        <div class="mb-5">
            <x-molecules.search-bar />
        </div>
        
        <!-- Navigation -->
        @php
            $navItems = [
                ['route' => 'dashboard', 'icon' => 'users', 'label' => 'Data Pengguna', 'active' => request()->routeIs('dashboard')],
                ['route' => 'lab', 'icon' => 'dashboard', 'label' => 'Penjadwalan', 'active' => request()->routeIs('lab') || request()->routeIs('addJadwalView')],
                ['route' => 'laporanView', 'icon' => 'orders', 'label' => 'Laporan', 'active' => request()->routeIs('laporanView')],
                ['route' => 'alat', 'icon' => 'settings', 'label' => 'Alat & Bahan', 'active' => request()->routeIs('alat') || request()->routeIs('detailAlat')],
                ['route' => 'laporanPeminjaman', 'icon' => 'orders', 'label' => 'Laporan Peminjaman', 'active' => request()->routeIs('laporanPeminjaman')],
                ['route' => 'wastes.index', 'icon' => 'beaker', 'label' => 'Limbah B3', 'active' => request()->routeIs('wastes.*')],
                ['route' => 'tentangLab', 'icon' => 'chat', 'label' => 'Tentang Lab', 'active' => request()->routeIs('tentangLab') || request()->routeIs('editInfoLab')],
            ];
        @endphp
        
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <ul class="space-y-0.5">
                @foreach($navItems as $item)
                    <x-atoms.nav-link href="{{ route($item['route']) }}" :active="$item['active']">
                        <x-atoms.icon :name="$item['icon']" class="w-[18px] h-[18px] mb-[1px]" />
                        <span>{{ $item['label'] }}</span>
                    </x-atoms.nav-link>
                @endforeach
            </ul>
        </div>
        
        <!-- Bottom Profile — Premium Redesign -->
        <div class="mt-auto pt-3 relative" x-data="{ open: false }" @click.outside="open = false">

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
                    <a href="{{ route('profile.settings') }}"
                       class="flex items-center gap-2.5 px-3 py-2 text-[13px] font-medium text-foreground-muted hover:bg-surface-muted hover:text-foreground rounded-xl transition-colors">
                        <span class="material-symbols-rounded text-[17px]">manage_accounts</span>
                        Pengaturan Profil
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

            {{-- Premium Convex Trigger Button --}}
            <button
                @click="open = !open"
                class="relative w-full flex items-center gap-3 p-2 rounded-[18px] border transition-all duration-200 outline-none focus-visible:ring-2 focus-visible:ring-primary/30 group"
                :class="open
                    ? 'border-primary/20 bg-primary/5 shadow-inner'
                    : 'border-default/80 bg-gradient-to-b from-surface to-surface-muted/50 hover:to-surface-muted hover:shadow-[0_2px_8px_rgba(0,0,0,0.04)]'"
            >
                {{-- Inner top highlight for convex (3D button) effect --}}
                <div class="absolute inset-0 rounded-[18px] pointer-events-none border-t border-white/60 opacity-50"></div>

                {{-- Avatar --}}
                <div class="relative shrink-0">
                    <div class="w-10 h-10 rounded-[14px] flex items-center justify-center font-bold text-white text-sm shadow-sm border border-white/20"
                         style="background: linear-gradient(135deg, rgb(var(--color-primary)), #3b82f6)">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-success rounded-full border-2 border-surface shadow-sm"></span>
                </div>

                {{-- Name & Role --}}
                <div class="flex-1 text-left overflow-hidden relative z-10 pl-0.5">
                    <p class="text-[13px] font-bold text-foreground truncate leading-tight">
                        {{ Auth::user()->name ?? 'User Name' }}
                    </p>
                    <p class="text-[11px] text-foreground-muted font-medium truncate mt-0.5 leading-tight">
                        {{ Auth::user()->role ?? 'Admin Lab' }}
                    </p>
                </div>

                {{-- Chevron --}}
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mr-1 transition-colors group-hover:bg-foreground/5" :class="open ? 'bg-primary/10 text-primary' : 'text-foreground-muted/60'">
                    <span class="material-symbols-rounded text-[18px] transition-transform duration-200"
                          :class="open ? 'rotate-180' : ''">
                        expand_more
                    </span>
                </div>
            </button>
        </div>
    </div>

    {{-- Logout Confirmation Modal (full-screen, fixed) --}}
    <x-ui.logout-confirm-modal />

</aside>
