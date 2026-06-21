@extends('layouts.app')

@section('title', 'Alat dan Bahan')

@section('content')
<div x-data="{ viewMode: 'list' }">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h4 class="text-2xl font-bold text-zinc-900">Alat & Bahan</h4>
            <p class="text-sm font-medium text-zinc-500 mt-1">
                Kelola inventaris alat dan bahan di laboratorium secara terpadu.
            </p>
        </div>
        <a href="{{ route('add.alat') }}"
            class="w-full md:w-auto inline-flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm md:text-xs px-4 h-11 md:h-10 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
            Tambah Data
        </a>
    </div>

    {{-- Top Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('alat') }}" class="group bg-white border border-zinc-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-zinc-300 transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-600 group-hover:scale-110 transition-transform">
                <span class="material-symbols-rounded text-[24px]">inventory_2</span>
            </div>
            <div>
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-0.5">Total Keseluruhan</p>
                <h5 class="text-2xl font-extrabold text-zinc-900">{{ $all }}</h5>
            </div>
        </a>

        <a href="{{ route('alat', ['jenis_alat' => 'Alat']) }}" class="group bg-white border border-zinc-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-blue-200 transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                <span class="material-symbols-rounded text-[24px]">hardware</span>
            </div>
            <div>
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-0.5">Daftar Alat</p>
                <h5 class="text-2xl font-extrabold text-zinc-900">{{ $bahanPadatCount }}</h5>
            </div>
        </a>

        <a href="{{ route('alat', ['jenis_alat' => 'Bahan']) }}" class="group bg-white border border-zinc-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-purple-200 transition-all flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                <span class="material-symbols-rounded text-[24px]">science</span>
            </div>
            <div>
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-0.5">Daftar Bahan</p>
                <h5 class="text-2xl font-extrabold text-zinc-900">{{ $bahanCairCount }}</h5>
            </div>
        </a>
    </div>

    {{-- Filter, Search & View Controls --}}
    <div class="bg-white border border-zinc-200/80 rounded-2xl p-4 shadow-sm mb-6 flex flex-col xl:flex-row gap-4 justify-between items-center">
        
        <div class="flex flex-col md:flex-row items-center gap-4 w-full xl:w-auto">
            {{-- Segmented Control Filter Kategori --}}
            @php $currentFilter = request('jenis_alat'); @endphp
            <div class="flex p-1 bg-zinc-100/80 rounded-xl w-full md:w-auto shrink-0">
                <a href="{{ route('alat') }}" class="{{ !$currentFilter ? 'bg-white text-zinc-900 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700' }} flex-1 md:w-28 py-2 text-sm font-semibold text-center rounded-lg transition-all">Semua</a>
                <a href="{{ route('alat', ['jenis_alat' => 'Alat']) }}" class="{{ $currentFilter == 'Alat' ? 'bg-white text-blue-700 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700' }} flex-1 md:w-28 py-2 text-sm font-semibold text-center rounded-lg transition-all">Alat</a>
                <a href="{{ route('alat', ['jenis_alat' => 'Bahan']) }}" class="{{ $currentFilter == 'Bahan' ? 'bg-white text-purple-700 shadow-sm ring-1 ring-zinc-200' : 'text-zinc-500 hover:text-zinc-700' }} flex-1 md:w-28 py-2 text-sm font-semibold text-center rounded-lg transition-all">Bahan</a>
            </div>

            {{-- Filter Per Page --}}
            <form action="{{ route('alat') }}" method="GET" class="w-full md:w-auto flex items-center shrink-0">
                @if($currentFilter) <input type="hidden" name="jenis_alat" value="{{ $currentFilter }}"> @endif
                @if(request('cari')) <input type="hidden" name="cari" value="{{ request('cari') }}"> @endif
                <label for="per_page" class="text-xs font-bold text-zinc-500 mr-2 uppercase tracking-wider hidden md:block">Tampilkan</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()" class="bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 pr-8 w-full md:w-auto">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 baris</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris</option>
                </select>
            </form>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-4 w-full xl:w-auto">
            {{-- Search Bar --}}
            <form action="{{ route('alat') }}" method="GET" class="w-full md:w-80 relative shrink-0">
                @if($currentFilter) <input type="hidden" name="jenis_alat" value="{{ $currentFilter }}"> @endif
                @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-rounded text-zinc-400 text-[20px]">search</span>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama alat atau bahan..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
            </form>

            {{-- View Mode Toggle --}}
            <div class="hidden md:flex p-1 bg-zinc-100/80 rounded-xl shrink-0">
                <button @click="viewMode = 'list'" :class="{ 'bg-white text-indigo-600 shadow-sm ring-1 ring-zinc-200': viewMode === 'list', 'text-zinc-400 hover:text-zinc-600': viewMode !== 'list' }" class="p-1.5 rounded-lg transition-all flex items-center justify-center">
                    <span class="material-symbols-rounded text-[20px]">view_list</span>
                </button>
                <button @click="viewMode = 'grid'" :class="{ 'bg-white text-indigo-600 shadow-sm ring-1 ring-zinc-200': viewMode === 'grid', 'text-zinc-400 hover:text-zinc-600': viewMode !== 'grid' }" class="p-1.5 rounded-lg transition-all flex items-center justify-center">
                    <span class="material-symbols-rounded text-[20px]">grid_view</span>
                </button>
            </div>
        </div>
    </div>

    <x-alert />

    {{-- LIST VIEW --}}
    <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <x-ui.table class="mb-6">
            <x-slot name="header">
                <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider w-10 text-center">No.</th>
                <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider">Barang</th>
                <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider">Kondisi</th>
                <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider text-right">Jumlah</th>
                <th class="px-6 py-4 text-[10px] font-bold text-indigo-900 uppercase tracking-wider text-center">Aksi</th>
            </x-slot>
                        @forelse($alat as $item)
                            <tr class="group hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4 text-center text-[13px] font-semibold text-zinc-500">
                                    {{ $alat->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_alat }}" class="w-12 h-12 rounded-lg object-cover ring-1 ring-zinc-200 shrink-0">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-zinc-100 flex items-center justify-center shrink-0">
                                                <span class="material-symbols-rounded text-zinc-400">image</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-bold text-zinc-900">{{ $item->nama_alat }}</p>
                                            <p class="text-xs text-zinc-500 mt-0.5 truncate max-w-xs">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->jenis_alat == 'Alat')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10">Alat</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-700/10">Bahan</span>
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
                                    <div class="flex items-center justify-center gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('detailAlat', $item->id) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Lihat Detail">
                                            <span class="material-symbols-rounded text-[20px]">visibility</span>
                                        </a>
                                        <a href="{{ route('editAlat', $item->id) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                            <span class="material-symbols-rounded text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('hapus.alat', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                                <span class="material-symbols-rounded text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-zinc-50 mb-4">
                                        <span class="material-symbols-rounded text-[32px] text-zinc-400">inventory_2</span>
                                    </div>
                                    <h3 class="text-sm font-bold text-zinc-900">Data Kosong</h3>
                                    <p class="text-sm text-zinc-500 mt-1">Tidak ada alat atau bahan yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
        </x-ui.table>
            
            {{-- List Pagination --}}
            <x-ui.pagination :paginator="$alat" label="Total Alat & Bahan" />
    </div>

    {{-- GRID VIEW --}}
    <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
            @forelse($alat as $item)
                <div class="group bg-white border border-zinc-200/80 hover:border-indigo-300 rounded-2xl shadow-sm hover:shadow transition-all flex flex-col overflow-hidden">
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
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50/90 backdrop-blur-sm text-blue-700 ring-1 ring-inset ring-blue-700/20 shadow-sm">Alat</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-50/90 backdrop-blur-sm text-purple-700 ring-1 ring-inset ring-purple-700/20 shadow-sm">Bahan</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h5 class="text-base font-bold text-zinc-900 leading-tight">{{ $item->nama_alat }}</h5>
                            <span class="text-xs font-bold px-2 py-1 bg-zinc-100 text-zinc-600 rounded-md shrink-0">
                                {{ $item->jenis_alat == 'Alat' ? ($item->jumlah_satuan ?? 0) . ' unit' : ($item->jumlah_ml ?? 0) . ' ml' }}
                            </span>
                        </div>
                        
                        <p class="text-xs text-zinc-500 line-clamp-2 mb-4 flex-1">
                            {{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-zinc-100">
                            <a href="{{ route('detailAlat', $item->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                                Lihat Detail <span class="material-symbols-rounded text-[16px]">arrow_forward</span>
                            </a>
                            
                            <div class="flex items-center gap-1">
                                <a href="{{ route('editAlat', $item->id) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                    <span class="material-symbols-rounded text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('hapus.alat', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                        <span class="material-symbols-rounded text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white border border-zinc-200/80 rounded-2xl">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-zinc-50 mb-4">
                        <span class="material-symbols-rounded text-[32px] text-zinc-400">inventory_2</span>
                    </div>
                    <h3 class="text-sm font-bold text-zinc-900">Data Kosong</h3>
                    <p class="text-sm text-zinc-500 mt-1">Tidak ada alat atau bahan yang ditemukan.</p>
                </div>
            @endforelse
        </div>
        
        {{-- Grid Pagination --}}
        <x-ui.pagination :paginator="$alat" label="Total Alat & Bahan" class="mt-6" />
    </div>
</div>

@push('scripts')
<script>
    // Ensure viewMode persists based on user preference if needed in the future
    // For now, Alpine handles the toggle smoothly on client-side
</script>
@endpush
@endsection