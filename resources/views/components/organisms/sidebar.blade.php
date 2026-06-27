<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-[260px] h-screen transition-transform -translate-x-full sm:translate-x-0 bg-[#FBFBFC] border-r border-gray-200 text-gray-800 flex flex-col" aria-label="Sidebar">
    <div class="h-full px-4 py-5 overflow-y-auto flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3 cursor-pointer hover:opacity-80">
                <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center text-white">
                    <x-atoms.icon name="grid" class="w-5 h-5 text-white" />
                </div>
                <div>
                    <div class="flex items-center space-x-1">
                        <h2 class="text-[15px] font-semibold text-gray-900 leading-tight">Lab UIN</h2>
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Sistem Manajemen</p>
                </div>
            </div>
            <button class="p-1.5 text-gray-400 hover:bg-gray-100 rounded-md transition-colors sm:hidden" data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button">
                <x-atoms.icon name="menu" class="w-5 h-5" />
            </button>
        </div>
        
        <!-- Search -->
        <div class="mb-5">
            <x-molecules.search-bar />
        </div>
        
        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto">
            <ul class="space-y-0.5">
                <x-atoms.nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    <x-atoms.icon name="users" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Data Pengguna</span>
                </x-atoms.nav-link>

                <x-atoms.nav-link href="{{ route('lab') }}" :active="request()->routeIs('lab') || request()->routeIs('addJadwalView')">
                    <x-atoms.icon name="dashboard" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Penjadwalan</span>
                </x-atoms.nav-link>

                <x-atoms.nav-link href="{{ route('laporanView') }}" :active="request()->routeIs('laporanView')">
                    <x-atoms.icon name="orders" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Laporan</span>
                </x-atoms.nav-link>

                <x-atoms.nav-link href="{{ route('alat') }}" :active="request()->routeIs('alat') || request()->routeIs('detailAlat')">
                    <x-atoms.icon name="settings" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Alat & Bahan</span>
                </x-atoms.nav-link>
                
                <x-atoms.nav-link href="{{ route('laporanPeminjaman') }}" :active="request()->routeIs('laporanPeminjaman')">
                    <x-atoms.icon name="orders" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Laporan Peminjaman</span>
                </x-atoms.nav-link>

                <x-atoms.nav-link href="{{ route('wastes.index') }}" :active="request()->routeIs('wastes.*')">
                    <x-atoms.icon name="beaker" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Limbah B3</span>
                </x-atoms.nav-link>



                <x-atoms.nav-link href="{{ route('tentangLab') }}" :active="request()->routeIs('tentangLab') || request()->routeIs('editInfoLab')">
                    <x-atoms.icon name="chat" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Tentang Lab</span>
                </x-atoms.nav-link>
            </ul>
        </div>
        
        <!-- Bottom Profile & Logout -->
        <div class="mt-auto pt-4 relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between p-2 -mx-2 hover:bg-gray-100 rounded-xl transition-colors outline-none focus-visible:ring-2 focus-visible:ring-[rgb(var(--color-primary))]">
                <x-molecules.user-profile-snippet :name="Auth::user()->name ?? 'User'" profileUrl="#" />
                <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="absolute bottom-full left-0 w-full mb-2 bg-white rounded-xl shadow-lg shadow-gray-200/50 border border-gray-200 overflow-hidden z-50 origin-bottom"
                 style="display: none;"
                 x-cloak>
                
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                    <p class="text-sm text-gray-900 font-bold truncate">{{ Auth::user()->name ?? 'User Name' }}</p>
                    <p class="text-[11px] text-gray-500 font-medium truncate">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                </div>

                <div class="py-1 border-b border-gray-100">
                    <a href="{{ route('profile.settings') }}" class="flex items-center gap-2 px-4 py-2 text-[13px] font-semibold text-gray-700 hover:bg-gray-50 hover:text-[rgb(var(--color-primary))] transition-colors">
                        <x-atoms.icon name="settings" class="w-4 h-4" />
                        Pengaturan Profil
                    </a>
                </div>
                
                <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}" class="w-full m-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-[13px] font-semibold text-rose-600 hover:bg-rose-50 transition-colors text-left">
                            <x-atoms.icon name="logout" class="w-4 h-4" />
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
