@extends('layouts.app')

@section('title', 'Penjadwalan Lab')

@section('content')
<div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6">
    <x-ui.page-header title="Penjadwalan" description="Kelola jadwal pemakaian ruangan laboratorium.">
        @if (Auth::user()->jabatan !== 'Mahasiswa')
            <a href="{{ route('addJadwalView') }}"
                class="w-full md:w-auto inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 h-11 md:h-10 text-white font-semibold text-sm md:text-xs shadow-sm shadow-indigo-600/10 hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
                Tambah Jadwal
            </a>
        @endif
    </x-ui.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-4">
            <div class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-zinc-100">
                    <h2 id="monthYear" class="text-sm font-bold text-zinc-900 tracking-tight"></h2>
                    <div class="flex items-center gap-1">
                        <button id="prev" class="w-9 h-9 rounded-xl flex items-center justify-center text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button id="next" class="w-9 h-9 rounded-xl flex items-center justify-center text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-3">
                    <table class="w-full">
                        <thead>
                            <tr>
                                @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                                    <th class="py-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wider text-center">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="calendarBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden">
                <div class="border-b border-zinc-100" x-data="{ activeTab: 'today' }">
                    <div class="flex items-center gap-1 p-1.5 overflow-x-auto whitespace-nowrap">
                        <button @click="activeTab = 'today'"
                            :class="activeTab === 'today' ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700 hover:bg-zinc-50'"
                            class="flex-1 md:flex-none px-4 py-2.5 md:py-2 text-sm md:text-xs font-semibold rounded-xl transition-all text-center">
                            Hari Ini
                        </button>
                        <button @click="activeTab = 'tomorrow'"
                            :class="activeTab === 'tomorrow' ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700 hover:bg-zinc-50'"
                            class="flex-1 md:flex-none px-4 py-2.5 md:py-2 text-sm md:text-xs font-semibold rounded-xl transition-all text-center">
                            Besok
                        </button>
                        <button @click="activeTab = 'week'"
                            :class="activeTab === 'week' ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700 hover:bg-zinc-50'"
                            class="flex-1 md:flex-none px-4 py-2.5 md:py-2 text-sm md:text-xs font-semibold rounded-xl transition-all text-center whitespace-nowrap">
                            7 Hari Kedepan
                        </button>
                    </div>

                    @php
                        $tabClasses = 'p-5 md:p-6';
                        $timelineClasses = 'relative border-s-2 border-zinc-200 ms-3';
                        $nodeClasses = 'absolute flex items-center justify-center w-7 h-7 bg-indigo-100 rounded-full -start-3.5 ring-4 ring-white';
                    @endphp

                    <div x-show="activeTab === 'today'" class="{{ $tabClasses }}">
                        @if (count($jadwal) > 0)
                            <ol class="{{ $timelineClasses }}">
                                @foreach ($jadwal as $agenda)
                                    <li class="mb-8 ms-6 last:mb-0">
                                        <span class="{{ $nodeClasses }}">
                                            <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </span>
                                        <h3 class="text-sm font-bold text-zinc-900 tracking-tight mb-1">
                                            {{ $agenda->mata_kuliah }} — Kelas {{ $agenda->kelas }} Smt {{ $agenda->semester }}
                                        </h3>
                                        <time class="block mb-2 text-xs font-semibold text-zinc-400">
                                            {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') }}
                                        </time>
                                        <p class="text-sm font-medium text-zinc-500 leading-relaxed">
                                            Submateri {{ $agenda->submateri ?? '-' }} · Dosen {{ $agenda->dosen?->name ?? 'Unknown' }}
                                        </p>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-10 h-10 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-400 mb-3 ring-1 ring-zinc-100">
                                    <x-atoms.icon name="calendar" class="w-5 h-5" />
                                </div>
                                <p class="text-sm font-semibold text-zinc-500">Tidak ada jadwal hari ini</p>
                            </div>
                        @endif
                    </div>

                    <div x-show="activeTab === 'tomorrow'" x-cloak class="{{ $tabClasses }}">
                        @if (count($jadwal_besok) > 0)
                            <ol class="{{ $timelineClasses }}">
                                @foreach ($jadwal_besok as $agenda)
                                    <li class="mb-8 ms-6 last:mb-0">
                                        <span class="{{ $nodeClasses }}">
                                            <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </span>
                                        <h3 class="text-sm font-bold text-zinc-900 tracking-tight mb-1">
                                            {{ $agenda->mata_kuliah }} — Kelas {{ $agenda->kelas }} Smt {{ $agenda->semester }}
                                        </h3>
                                        <time class="block mb-2 text-xs font-semibold text-zinc-400">
                                            {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') }}
                                        </time>
                                        <p class="text-sm font-medium text-zinc-500 leading-relaxed">
                                            Submateri {{ $agenda->submateri ?? '-' }} · Dosen {{ $agenda->dosen?->name ?? 'Unknown' }}
                                        </p>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-10 h-10 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-400 mb-3 ring-1 ring-zinc-100">
                                    <x-atoms.icon name="calendar" class="w-5 h-5" />
                                </div>
                                <p class="text-sm font-semibold text-zinc-500">Tidak ada jadwal besok</p>
                            </div>
                        @endif
                    </div>

                    <div x-show="activeTab === 'week'" x-cloak class="{{ $tabClasses }}">
                        @if (count($jadwal_minggu_ini) > 0)
                            <ol class="{{ $timelineClasses }}">
                                @foreach ($jadwal_minggu_ini as $agenda)
                                    <li class="mb-8 ms-6 last:mb-0">
                                        <span class="{{ $nodeClasses }}">
                                            <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </span>
                                        <h3 class="text-sm font-bold text-zinc-900 tracking-tight mb-1">
                                            {{ $agenda->mata_kuliah }} — Kelas {{ $agenda->kelas }} Smt {{ $agenda->semester }}
                                        </h3>
                                        <time class="block mb-2 text-xs font-semibold text-zinc-400">
                                            {{ \Carbon\Carbon::parse($agenda->tanggal_jadwal)->format('d M Y') }} • {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') }}
                                        </time>
                                        <p class="text-sm font-medium text-zinc-500 leading-relaxed">
                                            Submateri {{ $agenda->submateri ?? '-' }} · Dosen {{ $agenda->dosen?->name ?? 'Unknown' }}
                                        </p>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-10 h-10 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-400 mb-3 ring-1 ring-zinc-100">
                                    <x-atoms.icon name="calendar" class="w-5 h-5" />
                                </div>
                                <p class="text-sm font-semibold text-zinc-500">Tidak ada jadwal dalam 7 hari kedepan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="mb-4">
            <h2 class="text-lg font-bold text-zinc-900 tracking-tight">Daftar Semua Jadwal</h2>
            <p class="text-xs font-medium text-zinc-500 mt-0.5">Seluruh jadwal pemakaian laboratorium yang tercatat.</p>
        </div>

        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 mb-6">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <x-atoms.icon name="search" class="w-5 h-5 md:w-4 md:h-4 text-zinc-400" />
                </div>
                <input type="search" id="search"
                    class="block w-full h-12 md:h-10 pl-10 pr-4 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm transition-colors"
                    placeholder="Cari mata kuliah..." />
            </div>
            <div class="relative flex-1 md:max-w-xs">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <x-atoms.icon name="search" class="w-5 h-5 md:w-4 md:h-4 text-zinc-400" />
                </div>
                <input type="search" id="search-kelas"
                    class="block w-full h-12 md:h-10 pl-10 pr-4 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm transition-colors"
                    placeholder="Cari kelas..." />
            </div>
        </div>

        @if($schedule->isEmpty())
            <x-ui.empty-state title="Belum ada jadwal"
                description="Tambahkan jadwal pertama atau sesuaikan filter pencarian untuk memulai." icon="calendar">
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <x-slot name="action">
                        <a href="{{ route('addJadwalView') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors">
                            Tambah Jadwal
                        </a>
                    </x-slot>
                @endif
            </x-ui.empty-state>
        @else
            <x-ui.table class="hidden lg:block mb-6">
                <x-slot name="header">
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Mata Kuliah</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Submateri</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Dosen</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Status</th>
                    @if (Auth::user()->jabatan !== 'Mahasiswa')
                        <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider text-right">Aksi</th>
                    @endif
                </x-slot>

                @foreach ($schedule as $jadwal)
                    @php
                        $currentDateTime = \Carbon\Carbon::now();
                        $jadwalDateTime = \Carbon\Carbon::parse($jadwal->tanggal_jadwal . ' ' . $jadwal->waktu_selesai);
                        $isSelesai = $jadwalDateTime < $currentDateTime;
                    @endphp
                    <tr class="schedule-item group hover:bg-zinc-50/80 transition-all" data-matkul="{{ strtolower($jadwal->mata_kuliah ?? '') }}" data-kelas="{{ strtolower($jadwal->kelas ?? '') }}">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-zinc-900 tracking-tight">{{ Str::ucfirst($jadwal->mata_kuliah ?? '-') }}</p>
                        </td>
                        <td class="px-6 py-4"><span class="text-sm font-medium text-zinc-600">{{ $jadwal->submateri ?? '-' }}</span></td>
                        <td class="px-6 py-4"><span class="text-sm font-medium text-zinc-600">{{ $jadwal->dosen?->name ?? '-' }}</span></td>
                        <td class="px-6 py-4"><span class="text-sm font-medium text-zinc-600">{{ $jadwal->kelas ?? '-' }} / Smt {{ $jadwal->semester ?? '-' }}</span></td>
                        <td class="px-6 py-4"><span class="text-sm font-medium text-zinc-600">{{ $jadwal->tanggal_jadwal ?? '-' }}</span></td>
                        <td class="px-6 py-4"><span class="text-sm font-medium text-zinc-600">{{ $jadwal->waktu_mulai ?? '-' }} – {{ $jadwal->waktu_selesai ?? '-' }}</span></td>
                        <td class="px-6 py-4">
                            @if ($jadwal->status === 'selesai')
                                <x-ui.badge type="success">Selesai</x-ui.badge>
                            @elseif ($jadwal->status === 'berlangsung')
                                <x-ui.badge type="indigo">Berlangsung</x-ui.badge>
                            @elseif ($jadwal->status === 'dibatalkan')
                                <x-ui.badge type="danger">Dibatalkan</x-ui.badge>
                            @else
                                <x-ui.badge type="warning">Dijadwalkan</x-ui.badge>
                            @endif
                        </td>
                        @if (Auth::user()->jabatan !== 'Mahasiswa')
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end">
                                    <x-table.action-menu>
                                        <a href="{{ route('updateJadwal', $jadwal->id) }}" class="w-full text-left px-3 py-2 text-xs font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors flex items-center min-h-[44px]">
                                            <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-zinc-400" />
                                            Edit Jadwal
                                        </a>
                                        @if(in_array($jadwal->status, ['dijadwalkan', 'berlangsung']) && (Auth::id() == $jadwal->dosen_id || Auth::user()->jabatan == 'Admin Lab'))
                                            <form action="{{ route('completeEarly', $jadwal->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold text-emerald-600 hover:bg-emerald-50 transition-colors flex items-center min-h-[44px]">
                                                    <x-atoms.icon name="check" class="w-3.5 h-3.5 mr-2 text-emerald-400" />
                                                    Selesaikan
                                                </button>
                                            </form>
                                            <form action="{{ route('cancelJadwal', $jadwal->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold text-amber-600 hover:bg-amber-50 transition-colors flex items-center min-h-[44px]">
                                                    <x-atoms.icon name="x" class="w-3.5 h-3.5 mr-2 text-amber-400" />
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                        <div class="h-px bg-zinc-100 my-1"></div>
                                        <button type="button" data-modal-target="delete-modal-{{ $jadwal->id }}" data-modal-toggle="delete-modal-{{ $jadwal->id }}" class="w-full text-left px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center min-h-[44px]">
                                            <x-atoms.icon name="trash" class="w-3.5 h-3.5 mr-2 text-rose-400" />
                                            Hapus
                                        </button>
                                    </x-table.action-menu>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </x-ui.table>
            <x-ui.pagination :paginator="$schedule" label="Total Jadwal" class="mt-6" />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:hidden gap-4 flex-grow">
                @foreach ($schedule as $jadwal)
                    <div class="schedule-item bg-white border border-zinc-200/80 rounded-3xl p-5 shadow-sm hover:shadow-md transition-shadow relative" data-matkul="{{ strtolower($jadwal->mata_kuliah ?? '') }}" data-kelas="{{ strtolower($jadwal->kelas ?? '') }}">
                        @if (Auth::user()->jabatan !== 'Mahasiswa')
                            <div class="absolute top-4 right-4">
                                <x-table.action-menu>
                                    <a href="{{ route('updateJadwal', $jadwal->id) }}" class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors flex items-center min-h-[44px]">
                                        <x-atoms.icon name="settings" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-zinc-400" />
                                        Edit Jadwal
                                    </a>
                                    @if(in_array($jadwal->status, ['dijadwalkan', 'berlangsung']) && (Auth::id() == $jadwal->dosen_id || Auth::user()->jabatan == 'Admin Lab'))
                                        <form action="{{ route('completeEarly', $jadwal->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-emerald-600 hover:bg-emerald-50 transition-colors flex items-center min-h-[44px]">
                                                <x-atoms.icon name="check" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-emerald-400" />
                                                Selesaikan
                                            </button>
                                        </form>
                                        <form action="{{ route('cancelJadwal', $jadwal->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-amber-600 hover:bg-amber-50 transition-colors flex items-center min-h-[44px]">
                                                <x-atoms.icon name="x" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-amber-400" />
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                    <div class="h-px bg-zinc-100 my-1"></div>
                                    <button type="button" data-modal-target="delete-modal-{{ $jadwal->id }}" data-modal-toggle="delete-modal-{{ $jadwal->id }}" class="w-full text-left px-3 py-2 text-sm md:text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center min-h-[44px]">
                                        <x-atoms.icon name="trash" class="w-4 h-4 md:w-3.5 md:h-3.5 mr-2 text-rose-400" />
                                        Hapus
                                    </button>
                                </x-table.action-menu>
                            </div>
                        @endif

                        <div class="pr-12 mb-4">
                            <h5 class="text-[15px] font-bold text-zinc-900 tracking-tight mb-1">{{ Str::ucfirst($jadwal->mata_kuliah ?? '-') }}</h5>
                            <p class="text-[13px] font-medium text-zinc-500">Kelas {{ $jadwal->kelas ?? '-' }} · Semester {{ $jadwal->semester ?? '-' }}</p>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-zinc-100/80">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Submateri</span>
                                <span class="text-[13px] font-semibold text-zinc-700 text-right truncate">{{ $jadwal->submateri ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Dosen</span>
                                <span class="text-[13px] font-semibold text-zinc-700 text-right">{{ $jadwal->dosen?->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Tanggal</span>
                                <span class="text-[13px] font-semibold text-zinc-700 text-right">{{ $jadwal->tanggal_jadwal ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Waktu</span>
                                <span class="text-[13px] font-semibold text-zinc-700 text-right">{{ $jadwal->waktu_mulai ?? '-' }} – {{ $jadwal->waktu_selesai ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[12px] font-bold text-zinc-400 uppercase tracking-wider">Status</span>
                                @if ($jadwal->status === 'selesai')
                                    <x-ui.badge type="success">Selesai</x-ui.badge>
                                @elseif ($jadwal->status === 'berlangsung')
                                    <x-ui.badge type="indigo">Berlangsung</x-ui.badge>
                                @elseif ($jadwal->status === 'dibatalkan')
                                    <x-ui.badge type="danger">Dibatalkan</x-ui.badge>
                                @else
                                    <x-ui.badge type="warning">Dijadwalkan</x-ui.badge>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <x-ui.pagination :paginator="$schedule" label="Total Jadwal" class="lg:hidden mt-6" />

            @foreach ($schedule as $jadwal)
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <form action="{{ route('hapusJadwal', $jadwal->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div id="delete-modal-{{ $jadwal->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-3xl shadow-lg border border-zinc-200">
                                    <button type="button" class="absolute top-4 end-4 text-zinc-400 bg-transparent hover:bg-zinc-100 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors" data-modal-hide="delete-modal-{{ $jadwal->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <div class="p-6 md:p-8 text-center">
                                        <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center mx-auto mb-5 ring-1 ring-rose-100">
                                            <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-zinc-900 mb-2">Hapus Jadwal?</h3>
                                        <p class="text-sm text-zinc-500 mb-6">Jadwal <strong>{{ $jadwal->mata_kuliah }}</strong> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                                        <div class="flex flex-col-reverse sm:flex-row gap-3 justify-center">
                                            <button data-modal-hide="delete-modal-{{ $jadwal->id }}" type="button" class="px-5 py-2.5 text-sm font-semibold text-zinc-700 bg-white rounded-xl border border-zinc-200 hover:bg-zinc-50 transition-colors min-h-[44px]">
                                                Batal
                                            </button>
                                            <button data-modal-hide="delete-modal-{{ $jadwal->id }}" type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors min-h-[44px] focus:outline-none focus:ring-2 focus:ring-rose-500">
                                                Ya, Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('script')
    <script>
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        let currentDate = new Date();

        function generateCalendar(date) {
            const calendarBody = document.getElementById("calendarBody");
            calendarBody.innerHTML = "";
            const monthYear = document.getElementById("monthYear");
            const month = date.getMonth();
            const year = date.getFullYear();

            monthYear.textContent = `${monthNames[month]} ${year}`;

            const firstDayOfMonth = new Date(year, month, 1).getDay();
            const lastDateOfMonth = new Date(year, month + 1, 0).getDate();
            const lastDateOfPreviousMonth = new Date(year, month, 0).getDate();

            let dateCount = 1;
            let nextMonthDateCount = 1;

            for (let i = 0; i < 6; i++) {
                let row = document.createElement("tr");

                for (let j = 0; j < 7; j++) {
                    let cell = document.createElement("td");
                    cell.classList.add("py-2", "text-center", "text-sm", "font-medium", "cursor-default", "rounded-lg");

                    if (i === 0 && j < firstDayOfMonth) {
                        cell.textContent = lastDateOfPreviousMonth - firstDayOfMonth + j + 1;
                        cell.classList.add("text-zinc-300");
                    } else if (dateCount > lastDateOfMonth) {
                        cell.textContent = nextMonthDateCount++;
                        cell.classList.add("text-zinc-300");
                    } else {
                        cell.textContent = dateCount++;
                        cell.classList.add("text-zinc-700");
                        if (date.getFullYear() === new Date().getFullYear() && date.getMonth() === new Date().getMonth() && cell.textContent == new Date().getDate()) {
                            cell.classList.remove("text-zinc-700");
                            cell.classList.add("bg-indigo-600", "text-white", "font-bold", "rounded-lg");
                        }
                    }

                    row.appendChild(cell);
                }

                calendarBody.appendChild(row);
            }
        }

        document.getElementById("prev").addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar(currentDate);
        });

        document.getElementById("next").addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar(currentDate);
        });

        generateCalendar(currentDate);
    </script>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            filterSchedule();
        });

        document.getElementById('search-kelas').addEventListener('keyup', function() {
            filterSchedule();
        });

        function filterSchedule() {
            var search = document.getElementById('search').value.toLowerCase();
            var searchKelas = document.getElementById('search-kelas').value.toLowerCase();
            var items = document.querySelectorAll('.schedule-item');

            items.forEach(function(item) {
                var matakuliah = item.getAttribute('data-matkul') || '';
                var kelas = item.getAttribute('data-kelas') || '';
                if (matakuliah.includes(search) && kelas.includes(searchKelas)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }
    </script>
@endsection
