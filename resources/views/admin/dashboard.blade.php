@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-2 pb-12 max-w-7xl mx-auto space-y-6">
    <x-ui.page-header title="Dashboard Operasional" description="Ikhtisar status laboratorium hari ini, {{ Auth::user()->name ?? 'Administrator' }}">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-semibold text-zinc-700 shadow-sm">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </x-ui.page-header>

    <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider">Tinjauan Umum</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-dashboard.stat-card 
            label="Total Pengguna" 
            :value="$totalPengguna" 
            icon="users" 
            type="primary" 
        />
        <x-dashboard.stat-card 
            label="Alat Tersedia" 
            :value="$alatTersedia" 
            icon="cube" 
            type="zinc" 
        />
        <x-dashboard.stat-card 
            label="Bahan Tersedia" 
            :value="$bahanTersedia" 
            icon="beaker" 
            type="primary" 
        />
        <x-dashboard.stat-card 
            label="Peminjaman Aktif" 
            :value="$peminjamanAktif" 
            icon="clipboard-document-list" 
            type="rose" 
        />
    </div>

    <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider">Operasional Hari Ini</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white border border-zinc-200/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Jadwal</p>
                <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $jadwalHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-full ui-primary-soft flex items-center justify-center text-[rgb(var(--color-primary))]">
                <x-atoms.icon name="calendar" class="w-5 h-5" />
            </div>
        </div>
        
        <div class="bg-white border border-zinc-200/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Peminjaman</p>
                <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $peminjamanHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-full ui-primary-soft flex items-center justify-center text-[rgb(var(--color-primary))]">
                <x-atoms.icon name="cube" class="w-5 h-5" />
            </div>
        </div>
        
        <div class="bg-white border border-zinc-200/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Pengembalian</p>
                <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $pengembalianHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-600">
                <x-atoms.icon name="clipboard-document-list" class="w-5 h-5" />
            </div>
        </div>
        
        <div class="bg-white border border-zinc-200/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Menunggu</p>
                <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $menungguPersetujuan }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                <x-atoms.icon name="orders" class="w-5 h-5" />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div>
            <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider mb-4">Aktivitas Terbaru</h2>
            <x-dashboard.activity-feed :activities="$activities" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider">Jadwal Mendatang</h2>
                <a href="{{ route('lab') }}" class="text-xs font-semibold text-[rgb(var(--color-primary))] hover:opacity-90">Lihat Semua</a>
            </div>
            
            <div class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden">
                @if($upcomingSchedules->isEmpty())
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 rounded-full bg-zinc-50 flex items-center justify-center mx-auto mb-3">
                            <x-atoms.icon name="calendar" class="w-6 h-6 text-zinc-400" />
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900">Tidak ada jadwal</h3>
                        <p class="text-xs text-zinc-500 mt-1">Belum ada jadwal penggunaan lab mendatang.</p>
                    </div>
                @else
                    <ul class="divide-y divide-zinc-100">
                        @foreach($upcomingSchedules as $jadwal)
                        <li class="p-4 hover:bg-zinc-50 transition-colors flex items-start space-x-4">
                            <div class="w-10 h-10 rounded-xl ui-primary-soft flex flex-col items-center justify-center shrink-0 border border-[rgb(var(--color-primary)_/_0.2)]">
                                <span class="text-[10px] font-bold text-[rgb(var(--color-primary))] leading-none mb-0.5 uppercase">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->translatedFormat('M') }}</span>
                                <span class="text-sm font-bold text-[rgb(var(--color-primary))] leading-none">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-zinc-900 truncate">{{ $jadwal->mata_kuliah }}</p>
                                <div class="flex items-center text-xs text-zinc-500 mt-1 space-x-3">
                                    <span class="flex items-center">
                                        <x-atoms.icon name="calendar" class="w-3.5 h-3.5 mr-1 text-zinc-400" />
                                        {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                    </span>
                                    <span class="flex items-center truncate">
                                        <x-atoms.icon name="home" class="w-3.5 h-3.5 mr-1 text-zinc-400" />
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
        <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('users.index') }}" class="group bg-white border border-zinc-200/80 hover:border-[rgb(var(--color-primary-soft))] rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full bg-zinc-50 group-hover:ui-primary-soft flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="users" class="w-5 h-5 text-zinc-500 group-hover:text-[rgb(var(--color-primary))] transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1">Tambah Pengguna</h3>
                <p class="text-xs text-zinc-500">Daftarkan pengguna baru</p>
            </a>
            
            <a href="{{ route('add.alat') }}" class="group bg-white border border-zinc-200/80 hover:border-[rgb(var(--color-primary-soft))] rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full bg-zinc-50 group-hover:ui-primary-soft flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="cube" class="w-5 h-5 text-zinc-500 group-hover:text-[rgb(var(--color-primary))] transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1">Tambah Alat</h3>
                <p class="text-xs text-zinc-500">Catat inventaris baru</p>
            </a>
            
            <a href="{{ route('add.alat') }}" class="group bg-white border border-zinc-200/80 hover:border-[rgb(var(--color-primary-soft))] rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full bg-zinc-50 group-hover:ui-primary-soft flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="beaker" class="w-5 h-5 text-zinc-500 group-hover:text-[rgb(var(--color-primary))] transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1">Tambah Bahan</h3>
                <p class="text-xs text-zinc-500">Data bahan praktikum</p>
            </a>
            
            <a href="{{ route('addJadwalView') }}" class="group bg-white border border-zinc-200/80 hover:border-[rgb(var(--color-primary-soft))] rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full bg-zinc-50 group-hover:ui-primary-soft flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="calendar" class="w-5 h-5 text-zinc-500 group-hover:text-[rgb(var(--color-primary))] transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1">Buat Jadwal</h3>
                <p class="text-xs text-zinc-500">Atur penggunaan lab</p>
            </a>
        </div>
    </div>
</div>
@endsection
