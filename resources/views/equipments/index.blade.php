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
        <x-ui.page-header title="Equipments & Materials"
            description="Manage laboratory equipments and materials inventory in an integrated manner.">
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
            <a href="{{ route('alat') }}"
                class="group ui-surface border border-default/80 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-default transition-all flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-surface-muted flex items-center justify-center text-foreground-muted group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-[24px]">inventory_2</span>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-foreground-muted uppercase tracking-wider">Total Keseluruhan</p>
                    <h5 class="text-2xl font-extrabold text-foreground">{{ $all }}</h5>
                </div>
            </a>

            <a href="{{ route('alat', ['jenis_alat' => 'Alat']) }}"
                class="group ui-surface border border-default/80 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-[rgb(var(--color-primary-soft))] transition-all flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl ui-primary-soft flex items-center justify-center text-[rgb(var(--color-primary))] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-[24px]">hardware</span>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-foreground-muted uppercase tracking-wider">Daftar Alat</p>
                    <h5 class="text-2xl font-extrabold text-foreground">{{ $bahanPadatCount }}</h5>
                </div>
            </a>

            <a href="{{ route('alat', ['jenis_alat' => 'Bahan']) }}"
                class="group ui-surface border border-default/80 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-[rgb(var(--color-primary-soft))] transition-all flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl ui-primary-soft flex items-center justify-center text-[rgb(var(--color-primary))] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-[24px]">science</span>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-foreground-muted uppercase tracking-wider">Daftar Bahan</p>
                    <h5 class="text-2xl font-extrabold text-foreground">{{ $bahanCairCount }}</h5>
                </div>
            </a>
        </div>

        <div class="ui-surface border border-default/80 rounded-3xl p-4 shadow-sm" x-data="{ 
            advancedOpen: {{ request('jenis_alat') || request('per_page') && request('per_page') != 10 ? 'true' : 'false' }},
            keyword: '{{ request('cari', '') }}',
            selectedJenis: '{{ request('jenis_alat', '') }}',
            perPage: '{{ request('per_page', '10') }}',
            isLoading: false,
            submitForm() {
                this.isLoading = true;
                this.$refs.filterForm.submit();
            },
            removeFilter(name) {
                let input = this.$refs.filterForm.elements[name];
                if(input) {
                    input.value = '';
                    if(name === 'cari') this.keyword = '';
                    if(name === 'jenis_alat') this.selectedJenis = '';
                    if(name === 'per_page') this.perPage = '10';
                    this.submitForm();
                }
            }
        }">

            <form x-ref="filterForm" method="GET" action="{{ route('alat') }}" class="flex flex-col">
                {{-- Primary Bar --}}
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                    {{-- Search Input --}}
                    <div class="relative flex-grow">
                        <x-ui.input type="search" name="cari" x-model="keyword" x-on:keydown.enter.prevent="submitForm"
                            icon="search" alpineLoading="isLoading"
                            placeholder="Cari nama alat atau bahan..." />
                    </div>

                    {{-- View Mode Toggles (Grid/List) - Exclusive to this page --}}
                    <div class="hidden md:flex p-1 bg-surface-muted/80 rounded-2xl shrink-0">
                        <button type="button" @click="setViewMode('list')"
                            :class="{ 'ui-surface text-[rgb(var(--color-primary))] shadow-sm ring-1 ring-default': viewMode === 'list', 'text-foreground-muted/60 hover:text-foreground-muted': viewMode !== 'list' }"
                            class="p-1.5 rounded-xl transition-all flex items-center justify-center">
                            <span class="material-symbols-rounded text-[20px]">view_list</span>
                        </button>
                        <button type="button" @click="setViewMode('grid')"
                            :class="{ 'ui-surface text-[rgb(var(--color-primary))] shadow-sm ring-1 ring-default': viewMode === 'grid', 'text-foreground-muted/60 hover:text-foreground-muted': viewMode !== 'grid' }"
                            class="p-1.5 rounded-xl transition-all flex items-center justify-center">
                            <span class="material-symbols-rounded text-[20px]">grid_view</span>
                        </button>
                    </div>

                    {{-- Toggle Advanced --}}
                    <button type="button" @click="advancedOpen = !advancedOpen"
                        class="w-full md:w-auto h-11 md:h-10 px-4 inline-flex items-center justify-center text-sm md:text-xs font-semibold text-foreground-muted bg-surface border border-default rounded-xl hover:bg-surface-muted transition-colors shadow-sm shrink-0 focus:outline-none">
                        <span class="material-symbols-rounded text-[18px] mr-1.5" :class="advancedOpen ? 'text-[rgb(var(--color-primary))]' : ''">tune</span>
                        Filter Lanjutan
                        <span class="material-symbols-rounded text-[18px] ml-1 transition-transform" :class="advancedOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    
                    {{-- Search Button --}}
                    <button type="button" @click="submitForm" class="hidden md:flex h-11 md:h-10 px-6 ui-primary hover:opacity-90 font-bold text-white text-sm md:text-xs rounded-xl shadow-sm transition-colors focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.5)] justify-center items-center">
                        Cari
                    </button>
                </div>

                {{-- Advanced Filters Panel --}}
                <div x-show="advancedOpen" x-collapse x-cloak>
                    <div class="pt-4 mt-4 border-t border-default/50 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <div>
                            <label class="block text-[11px] font-bold text-foreground-muted mb-1.5 ml-1 uppercase tracking-wider">Kategori (Alat/Bahan)</label>
                            <div class="relative">
                                <select name="jenis_alat" x-model="selectedJenis" @change="submitForm"
                                    class="w-full text-sm h-10 border border-default rounded-xl pl-3 pr-8 bg-surface focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-foreground-muted appearance-none">
                                    <option value="">Semua Kategori</option>
                                    <option value="Alat">Alat Saja</option>
                                    <option value="Bahan">Bahan Saja</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                                    <span class="material-symbols-rounded text-foreground-muted/60 text-[20px]">expand_more</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-foreground-muted mb-1.5 ml-1 uppercase tracking-wider">Tampilkan per halaman</label>
                            <div class="relative">
                                <select name="per_page" x-model="perPage" @change="submitForm"
                                    class="w-full text-sm h-10 border border-default rounded-xl pl-3 pr-8 bg-surface focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-foreground-muted appearance-none">
                                    <option value="10">10 baris</option>
                                    <option value="20">20 baris</option>
                                    <option value="50">50 baris</option>
                                    <option value="100">100 baris</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                                    <span class="material-symbols-rounded text-foreground-muted/60 text-[20px]">expand_more</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Active Filter Indicators (Chips) --}}
                @php
                    $activeFilters = collect([
                        'cari' => request('cari') ? 'Pencarian: ' . request('cari') : null,
                        'jenis_alat' => request('jenis_alat') ? 'Kategori: ' . request('jenis_alat') : null,
                        'per_page' => request('per_page') && request('per_page') != 10 ? 'Per Halaman: ' . request('per_page') : null,
                    ])->filter();
                @endphp

                @if($activeFilters->count() > 0)
                    <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-default/50">
                        <span class="text-[11px] font-bold text-foreground-muted/60 mr-1 uppercase tracking-wider">Filter Aktif:</span>
                        @foreach($activeFilters as $key => $label)
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-[rgb(var(--color-primary)_/_0.1)] text-[rgb(var(--color-primary))] border border-[rgb(var(--color-primary)_/_0.2)]">
                                {{ $label }}
                                <button type="button" @click="removeFilter('{{ $key }}')"
                                    class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-[rgb(var(--color-primary)_/_0.2)] transition-colors focus:outline-none">
                                    <span class="material-symbols-rounded text-[14px]">close</span>
                                </button>
                            </span>
                        @endforeach

                        <a href="{{ route('alat') }}"
                            class="inline-flex items-center px-2 py-1 text-[11px] font-bold text-foreground-muted/60 hover:text-foreground-muted hover:bg-surface-muted rounded-lg transition-colors ml-1 uppercase tracking-wider">
                            Reset Semua
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <x-alert />

        <template x-if="viewMode === 'list'">
            <div>
            <x-ui.table class="mb-6">
                <x-slot name="header">
                    <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider w-10 text-center">No.
                    </th>
                    <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider">Kondisi</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider text-right">Jumlah
                    </th>
                    <th class="px-6 py-4 text-[10px] font-bold text-foreground-muted uppercase tracking-wider text-center">Aksi</th>
                </x-slot>

                @forelse($alat as $item)
                    <tr class="group hover:bg-surface-muted/80 transition-colors">
                        <td class="px-6 py-4 text-center text-[13px] font-semibold text-foreground-muted">
                            {{ $alat->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if(!empty($item->gambar) && is_array($item->gambar) && count($item->gambar) > 0)
                                    <img src="{{ asset('storage/' . $item->gambar[0]) }}" alt="{{ $item->nama_alat }}"
                                        class="w-12 h-12 rounded-2xl object-cover ring-1 ring-default shrink-0" loading="lazy">
                                @elseif(!empty($item->gambar) && is_string($item->gambar))
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_alat }}"
                                        class="w-12 h-12 rounded-2xl object-cover ring-1 ring-default shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-2xl bg-surface-muted flex items-center justify-center shrink-0">
                                        <span class="material-symbols-rounded text-foreground-muted/60">image</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-foreground">{{ $item->nama_alat }}</p>
                                    <p class="text-xs text-foreground-muted mt-0.5 truncate max-w-xs">
                                        {{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</p>
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
                            <span class="text-sm font-medium text-foreground-muted">{{ $item->kondisi ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-bold text-foreground">
                                {{ $item->jenis_alat == 'Alat' ? format_angka($item->jumlah_satuan ?? 0) . ' unit' : format_angka($item->jumlah_ml ?? 0) . ' ml' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center">
                                <x-table.action-menu>
                                    <a href="{{ route('detailAlat', $item->id) }}"
                                        class="w-full text-left px-3 py-2 text-xs font-semibold text-foreground-muted hover:bg-surface-muted transition-colors flex items-center min-h-[44px]">
                                        <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-foreground-muted/60" />
                                        Lihat Detail
                                    </a>
                                    <a href="{{ route('editAlat', $item->id) }}"
                                        class="w-full text-left px-3 py-2 text-xs font-semibold text-foreground-muted hover:bg-surface-muted transition-colors flex items-center min-h-[44px]">
                                        <x-atoms.icon name="settings" class="w-3.5 h-3.5 mr-2 text-foreground-muted/60" />
                                        Edit Alat
                                    </a>
                                    <div class="h-px bg-surface-muted my-1"></div>
                                    <form action="{{ route('hapus.alat', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full text-left px-3 py-2 text-xs font-semibold text-danger hover:ui-danger-soft transition-colors flex items-center min-h-[44px]">
                                            <x-atoms.icon name="trash" class="w-3.5 h-3.5 mr-2 text-danger/80" />
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
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-muted mb-4">
                                <span class="material-symbols-rounded text-[32px] text-foreground-muted/60">inventory_2</span>
                            </div>
                            <h3 class="text-sm font-bold text-foreground">Data kosong</h3>
                            <p class="text-sm text-foreground-muted mt-1">Tidak ada alat atau bahan yang ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </x-ui.table>

            <x-ui.pagination :paginator="$alat" label="Total Alat & Bahan" />
            </div>
        </template>

        <template x-if="viewMode === 'grid'">
            <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                @forelse($alat as $item)
                    <div
                        class="group ui-surface border border-default/80 hover:border-[rgb(var(--color-primary-soft))] rounded-3xl shadow-sm hover:shadow transition-all flex flex-col overflow-hidden">
                        <div class="relative h-48 bg-surface-muted overflow-hidden">
                            @if(!empty($item->gambar) && is_array($item->gambar) && count($item->gambar) > 0)
                                <img src="{{ asset('storage/' . $item->gambar[0]) }}" alt="{{ $item->nama_alat }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            @elseif(!empty($item->gambar) && is_string($item->gambar))
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_alat }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-foreground-muted/60 bg-surface-muted">
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
                                <h5 class="text-base font-bold text-foreground leading-tight">{{ $item->nama_alat }}</h5>
                                <span class="text-xs font-bold px-2 py-1 bg-surface-muted text-foreground-muted rounded-lg shrink-0">
                                    {{ $item->jenis_alat == 'Alat' ? format_angka($item->jumlah_satuan ?? 0) . ' unit' : format_angka($item->jumlah_ml ?? 0) . ' ml' }}
                                </span>
                            </div>

                            <p class="text-xs text-foreground-muted line-clamp-2 mb-4 flex-1">
                                {{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-default/50">
                                <a href="{{ route('detailAlat', $item->id) }}"
                                    class="text-xs font-bold text-[rgb(var(--color-primary))] hover:text-[rgb(var(--color-primary-soft))] flex items-center gap-1">
                                    Lihat Detail <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                                </a>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('editAlat', $item->id) }}"
                                        class="p-2 text-foreground-muted/60 hover:text-foreground-muted hover:bg-surface-muted rounded-xl transition-colors" title="Edit Alat">
                                        <x-atoms.icon name="edit" class="w-4 h-4" />
                                    </a>
                                    <form action="{{ route('hapus.alat', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data ini?');" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-danger/80 hover:text-danger hover:ui-danger-soft rounded-xl transition-colors" title="Hapus Data">
                                            <x-atoms.icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center ui-surface border border-default/80 rounded-3xl">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-muted mb-4">
                            <span class="material-symbols-rounded text-[32px] text-foreground-muted/60">inventory_2</span>
                        </div>
                        <h3 class="text-sm font-bold text-foreground">Data kosong</h3>
                        <p class="text-sm text-foreground-muted mt-1">Tidak ada alat atau bahan yang ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <x-ui.pagination :paginator="$alat" label="Total Alat & Bahan" class="mt-6" />
            </div>
        </template>
    </div>
@endsection