@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-2 pb-12 max-w-7xl mx-auto space-y-6">
    <x-ui.page-header title="Operational Dashboard" description="Overview of laboratory status today, {{ Auth::user()->name ?? 'Administrator' }}">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-full border border-default bg-surface px-3 py-1 text-xs font-semibold text-foreground-muted shadow-sm">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </x-ui.page-header>

    <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider">Tinjauan Umum</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-dashboard.stat-card 
            label="Total Pengguna" 
            :value="format_angka($totalPengguna)" 
            icon="users" 
            type="primary" 
        />
        <x-dashboard.stat-card 
            label="Alat Tersedia" 
            :value="format_angka($alatTersedia)" 
            icon="cube" 
            type="zinc" 
        />
        <x-dashboard.stat-card 
            label="Bahan Tersedia" 
            :value="format_angka($bahanTersedia)" 
            icon="beaker" 
            type="primary" 
        />
        <x-dashboard.stat-card 
            label="Peminjaman Aktif" 
            :value="format_angka($peminjamanAktif)" 
            icon="clipboard-document-list" 
            type="rose" 
        />
    </div>

    <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider">Operasional Hari Ini</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="ui-surface border border-default/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-foreground-muted uppercase tracking-wide">Jadwal</p>
                <p class="text-2xl font-bold text-foreground mt-1">{{ $jadwalHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-full ui-primary-soft flex items-center justify-center text-primary">
                <x-atoms.icon name="calendar" class="w-5 h-5" />
            </div>
        </div>
        
        <div class="ui-surface border border-default/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-foreground-muted uppercase tracking-wide">Peminjaman</p>
                <p class="text-2xl font-bold text-foreground mt-1">{{ $peminjamanHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-full ui-primary-soft flex items-center justify-center text-primary">
                <x-atoms.icon name="cube" class="w-5 h-5" />
            </div>
        </div>
        
        <div class="ui-surface border border-default/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-foreground-muted uppercase tracking-wide">Pengembalian</p>
                <p class="text-2xl font-bold text-foreground mt-1">{{ $pengembalianHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-full ui-surface-muted flex items-center justify-center text-foreground-muted">
                <x-atoms.icon name="clipboard-document-list" class="w-5 h-5" />
            </div>
        </div>
        
        <div class="ui-surface border border-default/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-foreground-muted uppercase tracking-wide">Menunggu</p>
                <p class="text-2xl font-bold text-foreground mt-1">{{ $menungguPersetujuan }}</p>
            </div>
            <div class="w-10 h-10 rounded-full ui-danger-soft flex items-center justify-center text-danger">
                <x-atoms.icon name="orders" class="w-5 h-5" />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div>
            <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider mb-4">Aktivitas Terbaru</h2>
            <x-dashboard.activity-feed :activities="$activities" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider">Jadwal Mendatang</h2>
                <a href="{{ route('lab') }}" class="text-xs font-semibold text-primary hover:opacity-90">Lihat Semua</a>
            </div>
            
            <div class="ui-surface border border-default/80 rounded-3xl shadow-sm overflow-hidden">
                @if($upcomingSchedules->isEmpty())
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 rounded-full ui-surface-muted flex items-center justify-center mx-auto mb-3">
                            <x-atoms.icon name="calendar" class="w-6 h-6 text-foreground-muted/60" />
                        </div>
                        <h3 class="text-sm font-semibold text-foreground">Tidak ada jadwal</h3>
                        <p class="text-xs text-foreground-muted mt-1">Belum ada jadwal penggunaan lab mendatang.</p>
                    </div>
                @else
                    <ul class="divide-y divide-default/50">
                        @foreach($upcomingSchedules as $jadwal)
                        <li class="p-4 hover:bg-surface-muted transition-colors flex items-start space-x-4">
                            <div class="w-10 h-10 rounded-xl ui-primary-soft flex flex-col items-center justify-center shrink-0 border border-primary/20">
                                <span class="text-[10px] font-bold text-primary leading-none mb-0.5 uppercase">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->translatedFormat('M') }}</span>
                                <span class="text-sm font-bold text-primary leading-none">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-foreground truncate">{{ $jadwal->mata_kuliah }}</p>
                                <div class="flex items-center text-xs text-foreground-muted mt-1 space-x-3">
                                    <span class="flex items-center">
                                        <x-atoms.icon name="calendar" class="w-3.5 h-3.5 mr-1 text-foreground-muted/60" />
                                        {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                    </span>
                                    <span class="flex items-center truncate">
                                        <x-atoms.icon name="home" class="w-3.5 h-3.5 mr-1 text-foreground-muted/60" />
                                        {{ $jadwal->nama_ruangan ?? 'Ruang Lab' }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-[13px] font-bold text-foreground uppercase tracking-wider mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('users.index') }}" class="group ui-surface border border-default/80 hover:border-primary-soft rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full ui-surface-muted group-hover:ui-primary-soft flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="users" class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-foreground mb-1">Tambah Pengguna</h3>
                <p class="text-xs text-foreground-muted">Daftarkan pengguna baru</p>
            </a>
            
            <a href="{{ route('add.alat') }}" class="group ui-surface border border-default/80 hover:border-primary-soft rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full ui-surface-muted group-hover:ui-primary-soft flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="cube" class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-foreground mb-1">Tambah Alat</h3>
                <p class="text-xs text-foreground-muted">Catat inventaris baru</p>
            </a>
            
            <a href="{{ route('add.alat') }}" class="group ui-surface border border-default/80 hover:border-primary-soft rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full ui-surface-muted group-hover:ui-primary-soft flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="beaker" class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-foreground mb-1">Tambah Bahan</h3>
                <p class="text-xs text-foreground-muted">Data bahan praktikum</p>
            </a>
            
            <a href="{{ route('addJadwalView') }}" class="group ui-surface border border-default/80 hover:border-primary-soft rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full ui-surface-muted group-hover:ui-primary-soft flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="calendar" class="w-5 h-5 text-foreground-muted group-hover:text-primary transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-foreground mb-1">Buat Jadwal</h3>
                <p class="text-xs text-foreground-muted">Atur penggunaan lab</p>
            </a>
        </div>
    </div>
</div>
@endsection
