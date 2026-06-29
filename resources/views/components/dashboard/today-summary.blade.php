@props(['jadwalHariIni', 'peminjamanHariIni', 'pengembalianHariIni'])

<div>
    <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider mb-4">Today's Operation</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-dashboard.stat-card label="Jadwal Hari Ini" :value="format_angka($jadwalHariIni)" icon="calendar" type="zinc" />
        <x-dashboard.stat-card label="Peminjaman" :value="format_angka($peminjamanHariIni)" icon="clipboard-document-list" type="primary" />
        <x-dashboard.stat-card label="Pengembalian" :value="format_angka($pengembalianHariIni)" icon="arrow-path" type="zinc" />
    </div>
</div>
