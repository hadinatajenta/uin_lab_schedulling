@props(['totalPengguna', 'alatTersedia', 'bahanTersedia'])

<div>
    <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider mb-4">Overview</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-dashboard.stat-card label="Total Pengguna" :value="format_angka($totalPengguna)" icon="users" type="primary" />
        <x-dashboard.stat-card label="Total Alat" :value="format_angka($alatTersedia)" icon="cube" type="zinc" />
        <x-dashboard.stat-card label="Total Bahan" :value="format_angka($bahanTersedia)" icon="beaker" type="zinc" />
    </div>
</div>
