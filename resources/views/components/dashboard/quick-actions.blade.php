<div class="mb-8">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('users.index') }}" class="group ui-quick-action border rounded-xl p-4 flex flex-col sm:flex-row items-center text-center sm:text-left gap-4 shadow-sm hover:shadow transition-all">
            <div class="w-10 h-10 rounded-full ui-surface-muted group-hover:ui-primary-soft flex items-center justify-center transition-colors shrink-0">
                <x-atoms.icon name="users" class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" />
            </div>
            <div>
                <h3 class="text-sm font-semibold mb-0.5">Tambah Pengguna</h3>
                <p class="hidden sm:block text-[10px] text-foreground-muted">Daftarkan user baru</p>
            </div>
        </a>
        <a href="{{ route('add.alat') }}" class="group ui-quick-action border rounded-xl p-4 flex flex-col sm:flex-row items-center text-center sm:text-left gap-4 shadow-sm hover:shadow transition-all">
            <div class="w-10 h-10 rounded-full ui-surface-muted group-hover:ui-primary-soft flex items-center justify-center transition-colors shrink-0">
                <x-atoms.icon name="cube" class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" />
            </div>
            <div>
                <h3 class="text-sm font-semibold mb-0.5">Tambah Alat</h3>
                <p class="hidden sm:block text-[10px] text-foreground-muted">Catat inventaris baru</p>
            </div>
        </a>
        <a href="{{ route('add.alat') }}" class="group ui-quick-action border rounded-xl p-4 flex flex-col sm:flex-row items-center text-center sm:text-left gap-4 shadow-sm hover:shadow transition-all">
            <div class="w-10 h-10 rounded-full ui-surface-muted group-hover:ui-primary-soft flex items-center justify-center transition-colors shrink-0">
                <x-atoms.icon name="beaker" class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" />
            </div>
            <div>
                <h3 class="text-sm font-semibold mb-0.5">Tambah Bahan</h3>
                <p class="hidden sm:block text-[10px] text-foreground-muted">Data bahan praktikum</p>
            </div>
        </a>
        <a href="{{ route('addJadwalView') }}" class="group ui-quick-action border rounded-xl p-4 flex flex-col sm:flex-row items-center text-center sm:text-left gap-4 shadow-sm hover:shadow transition-all">
            <div class="w-10 h-10 rounded-full ui-surface-muted group-hover:ui-primary-soft flex items-center justify-center transition-colors shrink-0">
                <x-atoms.icon name="calendar" class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" />
            </div>
            <div>
                <h3 class="text-sm font-semibold mb-0.5">Buat Jadwal</h3>
                <p class="hidden sm:block text-[10px] text-foreground-muted">Atur penggunaan lab</p>
            </div>
        </a>
    </div>
</div>
