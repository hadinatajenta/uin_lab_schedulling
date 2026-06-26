@extends('layouts.app')

@section('title', 'Detail: ' . $waste->nama_limbah)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class="inline-block px-3 py-1 bg-zinc-100 text-zinc-600 text-[11px] font-bold rounded-lg font-mono tabular-nums uppercase tracking-wider border border-zinc-200">
                    {{ $waste->kode_limbah }}
                </span>
                <span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-[11px] font-bold rounded-lg uppercase tracking-wider border border-blue-200">
                    Kategori: {{ $waste->kategori }}
                </span>
            </div>
            <h1 class="text-3xl font-bold text-zinc-900 tracking-tight">{{ $waste->nama_limbah }}</h1>
            <p class="text-sm text-zinc-500 mt-2">Material Safety Data Sheet (MSDS) & Log Timbulan Limbah</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('wastes.index') }}">
                <x-secondary-button>
                    <span class="material-symbols-rounded text-[20px] mr-2">arrow_back</span>
                    Kembali
                </x-secondary-button>
            </a>
            @if (Auth::user()->jabatan !== 'Mahasiswa')
            <a href="{{ route('wastes.edit', $waste->id) }}">
                <x-primary-button>
                    <span class="material-symbols-rounded text-[20px] mr-2">edit</span>
                    Edit Master
                </x-primary-button>
            </a>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Spesifikasi Bahaya & Gambar -->
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
                <div class="p-4 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                        <span class="material-symbols-rounded text-amber-500 text-[20px]">warning</span>
                        Sifat Bahaya (GHS)
                    </h3>
                </div>
                <div class="p-5">
                    @if($waste->gambar_panduan)
                        <img src="{{ asset('storage/' . $waste->gambar_panduan) }}" alt="Label {{ $waste->nama_limbah }}" class="w-full object-cover rounded-xl border border-zinc-200 mb-5 shadow-sm">
                    @endif
                    
                    <div class="flex flex-col gap-3">
                        @if($waste->sifat_bahaya && is_array($waste->sifat_bahaya) && count($waste->sifat_bahaya) > 0)
                            @foreach($waste->sifat_bahaya as $bahaya)
                                @php
                                    $color = match($bahaya) {
                                        'Beracun' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'Mudah Terbakar' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'Korosif' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Infeksius' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        default => 'bg-zinc-50 text-zinc-700 border-zinc-200'
                                    };
                                    $icon = match($bahaya) {
                                        'Beracun' => 'skull',
                                        'Mudah Terbakar' => 'local_fire_department',
                                        'Korosif' => 'science',
                                        'Infeksius' => 'coronavirus',
                                        default => 'warning'
                                    };
                                @endphp
                                <div class="flex items-center gap-3 p-3 rounded-xl border {{ $color }}">
                                    <div class="w-10 h-10 rounded-lg bg-white/50 flex items-center justify-center border {{ str_replace('bg-', 'border-', $color) }}">
                                        <span class="material-symbols-rounded text-[24px]">{{ $icon }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold uppercase tracking-wide">{{ $bahaya }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <x-ui.empty-state title="Tidak ada spesifikasi bahaya" description="" icon="info" />
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: SOP dan Log -->
        <div class="space-y-6 lg:col-span-2">
            <!-- SOP Penanganan -->
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
                <div class="p-4 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                        <span class="material-symbols-rounded text-emerald-500 text-[20px]">health_and_safety</span>
                        SOP & Penanganan Darurat
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-2">Cara Penanganan Awal</h4>
                        <div class="p-4 bg-emerald-50/50 border border-emerald-100 rounded-xl">
                            <p class="text-sm text-emerald-900 leading-relaxed">{{ $waste->cara_penanganan ?: 'Belum ada SOP penanganan.' }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-rose-500 mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-[16px]">emergency</span>
                            Prosedur Darurat
                        </h4>
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl">
                            <p class="text-sm text-rose-900 leading-relaxed font-medium">{{ $waste->prosedur_darurat ?: 'Tidak ada data prosedur darurat.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Timbulan Limbah -->
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
                <div class="p-4 border-b border-zinc-100 bg-zinc-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                        <span class="material-symbols-rounded text-blue-500 text-[20px]">history</span>
                        Log Timbulan Limbah
                    </h3>
                    <div class="text-xs font-bold text-zinc-500 bg-zinc-100 px-2 py-1 rounded-md">Total Volume: <span class="text-zinc-900">{{ $waste->logs->sum('jumlah_volume') }}</span></div>
                </div>
                
                @if($waste->logs->isEmpty())
                    <x-ui.empty-state title="Belum ada pencatatan" description="Limbah ini belum pernah dihasilkan dari praktikum manapun." icon="inventory_2" />
                @else
                    <x-ui.table class="border-t-0 rounded-none shadow-none">
                        <x-slot name="header">
                            <th class="px-5 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-5 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Sumber Praktikum</th>
                            <th class="px-5 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Volume</th>
                            <th class="px-5 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider text-right">Status</th>
                        </x-slot>
                        
                        @foreach($waste->logs as $log)
                        <tr class="hover:bg-zinc-50/50 transition-colors">
                            <td class="px-5 py-3">
                                <p class="text-[13px] font-medium text-zinc-900 tabular-nums">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</p>
                                <p class="text-[11px] font-medium text-zinc-500 font-mono tabular-nums">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</p>
                            </td>
                            <td class="px-5 py-3">
                                @if($log->schedule)
                                    <p class="text-[13px] font-semibold text-zinc-900">{{ $log->schedule->mata_kuliah }}</p>
                                    <p class="text-xs text-zinc-500">Kls {{ $log->schedule->kelas }} / Smt {{ $log->schedule->semester }}</p>
                                @else
                                    <span class="text-xs text-zinc-400 italic">Jadwal dihapus</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-block px-2 py-1 bg-zinc-100 text-zinc-700 text-xs font-bold rounded border border-zinc-200 tabular-nums">
                                    {{ $log->jumlah_volume }} {{ $log->satuan }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                @php
                                    $statusColor = match($log->status) {
                                        'Ditampung' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'Diolah' => 'bg-blue-50 text-blue-600 border-blue-200',
                                        'Diserahkan' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        default => 'bg-zinc-50 text-zinc-600 border-zinc-200'
                                    };
                                @endphp
                                <span class="inline-block px-2 py-1 text-[11px] font-bold uppercase tracking-wider rounded-lg border {{ $statusColor }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </x-ui.table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
