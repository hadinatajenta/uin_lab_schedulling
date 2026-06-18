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

                <x-atoms.nav-link href="{{ route('limbah') }}" :active="request()->routeIs('limbah') || request()->routeIs('tambahLimbah')">
                    <x-atoms.icon name="wishlist" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Limbah</span>
                </x-atoms.nav-link>

                <x-atoms.nav-link href="{{ route('jaslabView') }}" :active="request()->routeIs('jaslabView')">
                    <x-atoms.icon name="settings" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Pengaturan Jaslab</span>
                </x-atoms.nav-link>

                <x-atoms.nav-link href="{{ route('tentangLab') }}" :active="request()->routeIs('tentangLab') || request()->routeIs('editInfoLab')">
                    <x-atoms.icon name="chat" class="w-[18px] h-[18px] mb-[1px]" />
                    <span>Tentang Lab</span>
                </x-atoms.nav-link>
            </ul>
        </div>
        
        <!-- Bottom Profile & Logout -->
        <div class="mt-auto pt-4 flex items-center justify-between">
            <x-molecules.user-profile-snippet :name="Auth::user()->name ?? 'User'" :profileUrl="route('dashboard')" />
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors" title="Keluar">
                    <x-atoms.icon name="logout" class="w-[18px] h-[18px]" />
                </button>
            </form>
        </div>
    </div>
</aside>
