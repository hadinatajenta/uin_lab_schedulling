@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-2 pb-12 max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Dashboard Operasional</h1>
            <p class="text-sm font-medium text-zinc-500 mt-1">
                Ikhtisar status laboratorium hari ini, {{ Auth::user()->name ?? 'Administrator' }}
            </p>
        </div>
        <div class="flex flex-col items-start md:items-end text-zinc-500 font-medium">
            <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Tanggal</span>
            <span class="text-sm text-zinc-800 font-semibold mt-1 bg-zinc-100 px-3 py-1 rounded-lg">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    {{-- Section 1: Overview --}}
    <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider mb-3">Tinjauan Umum</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-dashboard.stat-card 
            label="Total Pengguna" 
            :value="$totalPengguna" 
            icon="users" 
            type="indigo" 
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
            type="emerald" 
        />
        <x-dashboard.stat-card 
            label="Peminjaman Aktif" 
            :value="$peminjamanAktif" 
            icon="clipboard-document-list" 
            type="rose" 
        />
    </div>

    {{-- Section 2: Operational Activities --}}
    <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider mb-3">Operasional Hari Ini</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white border border-zinc-200/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Jadwal</p>
                <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $jadwalHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                <x-atoms.icon name="calendar" class="w-5 h-5" />
            </div>
        </div>
        
        <div class="bg-white border border-zinc-200/80 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Peminjaman</p>
                <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $peminjamanHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
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

    {{-- Content Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        {{-- Left: Activity Feed --}}
        <div>
            <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider mb-4">Aktivitas Terbaru</h2>
            <x-dashboard.activity-feed :activities="$activities" />
        </div>

        {{-- Right: Upcoming Schedule --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider">Jadwal Mendatang</h2>
                <a href="{{ route('lab') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua</a>
            </div>
            
            <div class="bg-white border border-zinc-200/80 rounded-2xl shadow-sm overflow-hidden">
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
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex flex-col items-center justify-center shrink-0 border border-indigo-100/50">
                                <span class="text-[10px] font-bold text-indigo-600 leading-none mb-0.5 uppercase">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->translatedFormat('M') }}</span>
                                <span class="text-sm font-bold text-indigo-700 leading-none">{{ \Carbon\Carbon::parse($jadwal->tanggal_jadwal)->format('d') }}</span>
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

    {{-- Section 5: Quick Actions at Bottom --}}
    <div>
        <h2 class="text-[13px] font-bold text-zinc-900 uppercase tracking-wider mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('users.index') }}" class="group bg-white border border-zinc-200/80 hover:border-indigo-300 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full bg-zinc-50 group-hover:bg-indigo-50 flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="users" class="w-5 h-5 text-zinc-500 group-hover:text-indigo-600 transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1">Tambah Pengguna</h3>
                <p class="text-xs text-zinc-500">Daftarkan pengguna baru</p>
            </a>
            
            <a href="{{ route('add.alat') }}" class="group bg-white border border-zinc-200/80 hover:border-indigo-300 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full bg-zinc-50 group-hover:bg-indigo-50 flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="cube" class="w-5 h-5 text-zinc-500 group-hover:text-indigo-600 transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1">Tambah Alat</h3>
                <p class="text-xs text-zinc-500">Catat inventaris baru</p>
            </a>
            
            <a href="{{ route('add.alat') }}" class="group bg-white border border-zinc-200/80 hover:border-indigo-300 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full bg-zinc-50 group-hover:bg-indigo-50 flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="beaker" class="w-5 h-5 text-zinc-500 group-hover:text-indigo-600 transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1">Tambah Bahan</h3>
                <p class="text-xs text-zinc-500">Data bahan praktikum</p>
            </a>
            
            <a href="{{ route('addJadwalView') }}" class="group bg-white border border-zinc-200/80 hover:border-indigo-300 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow transition-all">
                <div class="w-10 h-10 rounded-full bg-zinc-50 group-hover:bg-indigo-50 flex items-center justify-center mb-3 transition-colors">
                    <x-atoms.icon name="calendar" class="w-5 h-5 text-zinc-500 group-hover:text-indigo-600 transition-colors" />
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1">Buat Jadwal</h3>
                <p class="text-xs text-zinc-500">Atur penggunaan lab</p>
            </a>
        </div>
    </div>
</div>
@endsection
