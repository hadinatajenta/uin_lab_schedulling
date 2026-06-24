@extends('layouts.app')

@section('title', 'Alat dan Bahan')

@section('content')
<div x-data="{ 
        viewMode: localStorage.getItem('alatViewMode') || 'list',
        setViewMode(mode) {
            this.viewMode = mode;
            localStorage.setItem('alatViewMode', mode);
        }
    }" class="space-y-6">
    <x-ui.page-header title="Equipments & Materials" description="Manage laboratory equipments and materials inventory in an integrated manner.">
        @if (Auth::user()->jabatan !== 'Mahasiswa')
            <a href="{{ route('add.alat') }}"
                class="w-full md:w-auto inline-flex items-center justify-center rounded-xl ui-primary hover:opacity-90 px-4 h-11 md:h-10 font-semibold text-sm md:text-xs shadow-sm shadow-[rgb(var(--color-primary))_/_0.1] transition-opacity focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2">
                <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
                Tambah Data
            </a>
        @endif
    </x-ui.page-header>

    @php
        $currentFilter = request('jenis_alat');
        $currentSearch = request('cari');
        $currentPerPage = request('per_page', 10);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('alat') }}" class="group ui-surface border border-zinc-200/80 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-zinc-300 transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-zinc-50 flex items-center justify-center text-zinc-600 group-hover:scale-105 transition-transform">
                <span class="material-symbols-rounded text-[24px]">inventory_2</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Total Keseluruhan</p>
                <h5 class="text-2xl font-extrabold text-zinc-900">{{ $all }}</h5>
            </div>
        </a>

        <a href="{{ route('alat', ['jenis_alat' => 'Alat']) }}" class="group ui-surface border border-zinc-200/80 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-[rgb(var(--color-primary-soft))] transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl ui-primary-soft flex items-center justify-center text-[rgb(var(--color-primary))] group-hover:scale-105 transition-transform">
                <span class="material-symbols-rounded text-[24px]">hardware</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Daftar Alat</p>
                <h5 class="text-2xl font-extrabold text-zinc-900">{{ $bahanPadatCount }}</h5>
            </div>
        </a>

        <a href="{{ route('alat', ['jenis_alat' => 'Bahan']) }}" class="group ui-surface border border-zinc-200/80 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-[rgb(var(--color-primary-soft))] transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl ui-primary-soft flex items-center justify-center text-[rgb(var(--color-primary))] group-hover:scale-105 transition-transform">
                <span class="material-symbols-rounded text-[24px]">science</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Daftar Bahan</p>
                <h5 class="text-2xl font-extrabold text-zinc-900">{{ $bahanCairCount }}</h5>
            </div>
        </a>
    </div>

    <div class="ui-surface border border-zinc-200/80 rounded-3xl p-4 shadow-sm">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full xl:w-auto">
                <div class="flex p-1 bg-zinc-100/80 rounded-2xl w-full md:w-auto shrink-0">
                    <a href="{{ route('alat') }}" class="{{ !$currentFilter ? 'ui-surface text-zinc-900 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700' }} flex-1 md:w-28 py-2 text-sm font-semibold text-center rounded-xl transition-all">
                        Semua
                    </a>
                    <a href="{{ route('alat', ['jenis_alat' => 'Alat']) }}" class="{{ $currentFilter == 'Alat' ? 'ui-surface text-[rgb(var(--color-primary))] shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700' }} flex-1 md:w-28 py-2 text-sm font-semibold text-center rounded-xl transition-all">
                        Alat
                    </a>
                    <a href="{{ route('alat', ['jenis_alat' => 'Bahan']) }}" class="{{ $currentFilter == 'Bahan' ? 'ui-surface text-[rgb(var(--color-primary))] shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700' }} flex-1 md:w-28 py-2 text-sm font-semibold text-center rounded-xl transition-all">
                        Bahan
                    </a>
                </div>

                <form action="{{ route('alat') }}" method="GET" class="w-full md:w-auto flex items-center shrink-0">
                    @if($currentFilter)
                        <input type="hidden" name="jenis_alat" value="{{ $currentFilter }}">
                    @endif
                    @if($currentSearch)
                        <input type="hidden" name="cari" value="{{ $currentSearch }}">
                    @endif
                    <label for="per_page" class="text-xs font-bold text-zinc-500 mr-2 uppercase tracking-wider hidden md:block">Tampilkan</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()" class="bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] py-2.5 px-3 pr-8 w-full md:w-auto">
                        <option value="10" {{ (int) $currentPerPage === 10 ? 'selected' : '' }}>10 baris</option>
                        <option value="20" {{ (int) $currentPerPage === 20 ? 'selected' : '' }}>20 baris</option>
                        <option value="50" {{ (int) $currentPerPage === 50 ? 'selected' : '' }}>50 baris</option>
                        <option value="100" {{ (int) $currentPerPage === 100 ? 'selected' : '' }}>100 baris</option>
                    </select>
                </form>
            </div>

            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full xl:w-auto">
                <form action="{{ route('alat') }}" method="GET" class="w-full md:w-80 relative shrink-0">
                    @if($currentFilter)
                        <input type="hidden" name="jenis_alat" value="{{ $currentFilter }}">
                    @endif
                    @if(request('per_page'))
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-rounded text-zinc-400 text-[20px]">search</span>
                    </div>
                    <input type="text" name="cari" value="{{ $currentSearch }}" placeholder="Cari nama alat atau bahan..."
                        class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:bg-white transition-colors">
                </form>

                <div class="hidden md:flex p-1 bg-zinc-100/80 rounded-2xl shrink-0">
                    <button type="button" @click="setViewMode('list')" :class="{ 'ui-surface text-[rgb(var(--color-primary))] shadow-sm ring-1 ring-zinc-200': viewMode === 'list', 'text-zinc-400 hover:text-zinc-600': viewMode !== 'list' }" class="p-1.5 rounded-xl transition-all flex items-center justify-center">
                        <span class="material-symbols-rounded text-[20px]">view_list</span>
                    </button>
                    <button type="button" @click="setViewMode('grid')" :class="{ 'ui-surface text-[rgb(var(--color-primary))] shadow-sm ring-1 ring-zinc-200': viewMode === 'grid', 'text-zinc-400 hover:text-zinc-600': viewMode !== 'grid' }" class="p-1.5 rounded-xl transition-all flex items-center justify-center">
                        <span class="material-symbols-rounded text-[20px]">grid_view</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-alert />

    <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <x-ui.table class="mb-6">
            <x-slot name="header">
                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider w-10 text-center">No.</th>
                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Barang</th>
                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Kondisi</th>
                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider text-right">Jumlah</th>
                <th class="px-6 py-4 text-[10px] font-bold text-zinc-500 uppercase tracking-wider text-center">Aksi</th>
            </x-slot>

            @forelse($alat as $item)
                <tr class="group hover:bg-zinc-50/80 transition-colors">
                    <td class="px-6 py-4 text-center text-[13px] font-semibold text-zinc-500">
                        {{ $alat->firstItem() + $loop->index }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_alat }}" class="w-12 h-12 rounded-2xl object-cover ring-1 ring-zinc-200 shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-zinc-100 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-rounded text-zinc-400">image</span>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-zinc-900">{{ $item->nama_alat }}</p>
                                <p class="text-xs text-zinc-500 mt-0.5 truncate max-w-xs">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->jenis_alat == 'Alat')
                            <x-ui.badge type="primary">Alat</x-ui.badge>
                        @else
                            <x-ui.badge type="primary">Bahan</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-zinc-600">{{ $item->kondisi ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-sm font-bold text-zinc-900">
                            {{ $item->jenis_alat == 'Alat' ? ($item->jumlah_satuan ?? 0) . ' unit' : ($item->jumlah_ml ?? 0) . ' ml' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center">
                            <x-table.action-menu>
                                <a href="{{ route('detailAlat', $item->id) }}" class="w-full text-left px-3 py-2 text-xs font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors flex items-center min-h-[44px]">
                                    <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-zinc-400" />
                                    Lihat Detail
                                </a>
                                <a href="{{ route('editAlat', $item->id) }}" class="w-full text-left px-3 py-2 text-xs font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors flex items-center min-h-[44px]">
                                    <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-zinc-400" />
                                    Edit Alat
                                </a>
                                <div class="h-px bg-zinc-100 my-1"></div>
                                <form action="{{ route('hapus.alat', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center min-h-[44px]">
                                        <x-atoms.icon name="trash" class="w-3.5 h-3.5 mr-2 text-rose-400" />
                                        Hapus
                                    </button>
                                </form>
                            </x-table.action-menu>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-zinc-50 mb-4">
                            <span class="material-symbols-rounded text-[32px] text-zinc-400">inventory_2</span>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900">Data kosong</h3>
                        <p class="text-sm text-zinc-500 mt-1">Tidak ada alat atau bahan yang ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </x-ui.table>

        <x-ui.pagination :paginator="$alat" label="Total Alat & Bahan" />
    </div>

    <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
            @forelse($alat as $item)
                <div class="group ui-surface border border-zinc-200/80 hover:border-[rgb(var(--color-primary-soft))] rounded-3xl shadow-sm hover:shadow transition-all flex flex-col overflow-hidden">
                    <div class="relative h-48 bg-zinc-100 overflow-hidden">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_alat }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-zinc-400 bg-zinc-50">
                                <span class="material-symbols-rounded text-[48px] mb-2">image</span>
                                <span class="text-xs font-medium">Tanpa Gambar</span>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            @if($item->jenis_alat == 'Alat')
                                <x-ui.badge type="primary">Alat</x-ui.badge>
                            @else
                                <x-ui.badge type="primary">Bahan</x-ui.badge>
                            @endif
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h5 class="text-base font-bold text-zinc-900 leading-tight">{{ $item->nama_alat }}</h5>
                            <span class="text-xs font-bold px-2 py-1 bg-zinc-100 text-zinc-600 rounded-lg shrink-0">
                                {{ $item->jenis_alat == 'Alat' ? ($item->jumlah_satuan ?? 0) . ' unit' : ($item->jumlah_ml ?? 0) . ' ml' }}
                            </span>
                        </div>

                        <p class="text-xs text-zinc-500 line-clamp-2 mb-4 flex-1">
                            {{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>

                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-zinc-100">
                            <a href="{{ route('detailAlat', $item->id) }}" class="text-xs font-bold text-[rgb(var(--color-primary))] hover:text-[rgb(var(--color-primary-soft))] flex items-center gap-1">
                                Lihat Detail <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                            </a>

                            <div class="flex items-center">
                                <x-table.action-menu>
                                    <a href="{{ route('editAlat', $item->id) }}" class="w-full text-left px-3 py-2 text-xs font-semibold text-zinc-700 hover:bg-zinc-50 transition-colors flex items-center min-h-[44px]">
                                        <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-zinc-400" />
                                        Edit Alat
                                    </a>
                                    <div class="h-px bg-zinc-100 my-1"></div>
                                    <form action="{{ route('hapus.alat', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center min-h-[44px]">
                                            <x-atoms.icon name="trash" class="w-3.5 h-3.5 mr-2 text-rose-400" />
                                            Hapus
                                        </button>
                                    </form>
                                </x-table.action-menu>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center ui-surface border border-zinc-200/80 rounded-3xl">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-zinc-50 mb-4">
                        <span class="material-symbols-rounded text-[32px] text-zinc-400">inventory_2</span>
                    </div>
                    <h3 class="text-sm font-bold text-zinc-900">Data kosong</h3>
                    <p class="text-sm text-zinc-500 mt-1">Tidak ada alat atau bahan yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <x-ui.pagination :paginator="$alat" label="Total Alat & Bahan" class="mt-6" />
    </div>
</div>
@endsection
