@extends('layouts.app')

@section('title', 'Katalog Limbah B3')

@section('content')
<div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6">
    <!-- Header -->
    <x-ui.page-header title="Katalog Limbah B3" description="Sistem informasi pengelolaan dan penanganan limbah laboratorium.">
        @if (Auth::user()->jabatan !== 'Mahasiswa')
        <a href="{{ route('wastes.create') }}">
            <button type="button" class="w-full md:w-auto inline-flex items-center justify-center rounded-xl ui-primary px-4 h-11 md:h-10 font-semibold text-sm md:text-xs shadow-sm hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2">
                <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
                Tambah Limbah Baru
            </button>
        </a>
        @endif
    </x-ui.page-header>

    <!-- Filters & Table Container -->
    <div class="ui-surface border border-zinc-200/80 rounded-3xl p-4 shadow-sm flex-grow flex flex-col">
        <!-- Search & Filter Bar -->
        <form method="GET" action="{{ route('wastes.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3 mb-4">
            {{-- Search Input --}}
            <div class="relative w-full md:w-1/3 shrink-0">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <span class="material-symbols-rounded text-zinc-400 text-[20px] md:text-[18px]">search</span>
                </div>
                <input type="search" name="keyword" value="{{ request('keyword') }}"
                    class="block w-full h-11 md:h-10 pl-10 pr-4 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] shadow-sm transition-colors"
                    placeholder="Cari kode atau nama limbah..." />
            </div>

            {{-- Kategori Dropdown --}}
            <div class="relative w-full md:w-48 shrink-0">
                <select name="kategori" onchange="this.form.submit()"
                    class="w-full h-11 md:h-10 text-sm md:text-xs font-medium border border-zinc-200 rounded-xl pl-4 pr-8 bg-white focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-zinc-700 appearance-none shadow-sm">
                    <option value="">Semua Kategori</option>
                    @foreach(['Padat', 'Cair', 'Gas', 'Infeksius'] as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <span class="material-symbols-rounded text-zinc-400 text-[20px]">expand_more</span>
                </div>
            </div>

            {{-- Sifat Bahaya Dropdown --}}
            <div class="relative w-full md:w-48 shrink-0">
                <select name="bahaya" onchange="this.form.submit()"
                    class="w-full h-11 md:h-10 text-sm md:text-xs font-medium border border-zinc-200 rounded-xl pl-4 pr-8 bg-white focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-zinc-700 appearance-none shadow-sm">
                    <option value="">Semua Sifat Bahaya</option>
                    @foreach(['Beracun', 'Mudah Terbakar', 'Korosif', 'Infeksius', 'Reaktif', 'Iritan'] as $bhy)
                        <option value="{{ $bhy }}" {{ request('bahaya') === $bhy ? 'selected' : '' }}>{{ $bhy }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <span class="material-symbols-rounded text-zinc-400 text-[20px]">expand_more</span>
                </div>
            </div>

            <div class="flex-grow flex justify-end">
                @if(request('keyword') || request('kategori') || request('bahaya'))
                    <a href="{{ route('wastes.index') }}" class="inline-flex items-center justify-center px-4 h-11 md:h-10 text-sm md:text-xs font-semibold text-zinc-600 bg-zinc-100 rounded-xl hover:bg-zinc-200 transition-colors">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>

        <!-- Data Area -->
        @if($wastes->isEmpty())
            <x-ui.empty-state title="Belum ada data limbah" description="Katalog limbah B3 masih kosong atau tidak ada hasil pencarian." icon="beaker">
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <x-slot name="action">
                        <a href="{{ route('wastes.create') }}">
                            <x-primary-button type="button">
                                Tambah Data
                            </x-primary-button>
                        </a>
                    </x-slot>
                @endif
            </x-ui.empty-state>
        @else
            <!-- Desktop Table -->
            <x-ui.table class="hidden lg:block flex-grow border border-zinc-200/80 rounded-xl mt-2">
                <x-slot name="header">
                    <th class="px-5 py-3 text-[10px] font-bold text-zinc-500 uppercase tracking-wider w-10 text-center">No.</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Limbah</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-zinc-500 uppercase tracking-wider">Sifat Bahaya</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-zinc-500 uppercase tracking-wider text-right">Aksi</th>
                </x-slot>

                @foreach($wastes as $waste)
                <tr class="group hover:bg-zinc-50/80 transition-all">
                    <td class="px-5 py-3 text-center text-[13px] font-semibold text-zinc-500">
                        {{ $wastes->firstItem() + $loop->index }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-4 min-w-0">
                            @if($waste->gambar_panduan)
                                <img src="{{ asset('storage/' . $waste->gambar_panduan) }}" alt="{{ $waste->nama_limbah }}" class="w-10 h-10 rounded-lg object-cover ring-4 ring-white shadow-sm shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-zinc-100 flex items-center justify-center ring-4 ring-white shadow-sm shrink-0">
                                    <span class="material-symbols-rounded text-[20px] text-zinc-400">science</span>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-[14px] font-bold text-zinc-900 truncate tracking-tight">{{ $waste->nama_limbah }}</p>
                                <p class="text-[11px] font-medium text-zinc-500 font-mono tracking-wider uppercase mt-0.5">{{ $waste->kode_limbah }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 bg-blue-50 text-blue-700 text-[11px] font-bold rounded-lg uppercase tracking-wider border border-blue-200">
                            {{ $waste->kategori }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex flex-wrap gap-1">
                            @if($waste->sifat_bahaya && is_array($waste->sifat_bahaya) && count($waste->sifat_bahaya) > 0)
                                @foreach(array_slice($waste->sifat_bahaya, 0, 2) as $bahaya)
                                    @php
                                        $color = match($bahaya) {
                                            'Beracun' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'Mudah Terbakar' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'Korosif' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Infeksius' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            default => 'bg-zinc-50 text-zinc-700 border-zinc-200'
                                        };
                                    @endphp
                                    <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded-md border {{ $color }} uppercase">
                                        {{ $bahaya }}
                                    </span>
                                @endforeach
                                @if(count($waste->sifat_bahaya) > 2)
                                    <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded-md border bg-zinc-100 text-zinc-600 border-zinc-200">
                                        +{{ count($waste->sifat_bahaya) - 2 }}
                                    </span>
                                @endif
                            @else
                                <span class="text-xs text-zinc-400 italic">Tidak ada</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('wastes.show', $waste->id) }}" class="inline-flex justify-center items-center p-2 rounded-xl text-zinc-600 hover:bg-zinc-100 transition-colors" title="Detail">
                                <span class="material-symbols-rounded text-[18px]">visibility</span>
                            </a>
                            @if (Auth::user()->jabatan !== 'Mahasiswa')
                                <a href="{{ route('wastes.edit', $waste->id) }}" class="inline-flex justify-center items-center p-2 rounded-xl text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                    <span class="material-symbols-rounded text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('wastes.destroy', $waste->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus katalog limbah ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex justify-center items-center p-2 rounded-xl text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                        <span class="material-symbols-rounded text-[18px]">delete</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </x-ui.table>

            <!-- Mobile Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:hidden gap-4 flex-grow mt-2">
                @foreach ($wastes as $waste)
                    <div class="ui-surface border border-zinc-200/80 rounded-2xl p-4 shadow-sm relative">
                        @if (Auth::user()->jabatan !== 'Mahasiswa')
                            <div class="absolute top-4 right-4">
                                <x-ui.dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button type="button" class="inline-flex justify-center items-center p-1.5 rounded-lg text-zinc-500 hover:bg-zinc-100 transition-colors">
                                            <span class="material-symbols-rounded text-[20px]">more_vert</span>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-ui.dropdown-item href="{{ route('wastes.edit', $waste->id) }}">
                                            <span class="material-symbols-rounded text-[18px] mr-2 text-zinc-400">edit</span>
                                            Edit Master
                                        </x-ui.dropdown-item>
                                        <form action="{{ route('wastes.destroy', $waste->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus katalog limbah ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center min-h-[44px]">
                                                <span class="material-symbols-rounded text-[18px] mr-2 text-rose-400">delete</span>
                                                Hapus
                                            </button>
                                        </form>
                                    </x-slot>
                                </x-ui.dropdown>
                            </div>
                        @endif

                        <div class="flex items-start gap-4 mb-4 pr-8">
                            @if($waste->gambar_panduan)
                                <img src="{{ asset('storage/' . $waste->gambar_panduan) }}" alt="{{ $waste->nama_limbah }}" class="w-12 h-12 rounded-lg object-cover ring-4 ring-white shadow-sm shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-zinc-100 flex items-center justify-center ring-4 ring-white shadow-sm shrink-0">
                                    <span class="material-symbols-rounded text-[24px] text-zinc-400">science</span>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-[14px] font-bold text-zinc-900 tracking-tight leading-tight mb-1">{{ $waste->nama_limbah }}</p>
                                <span class="inline-block px-2 py-0.5 bg-zinc-100 text-zinc-600 text-[10px] font-bold rounded md font-mono uppercase border border-zinc-200 tracking-wider">
                                    {{ $waste->kode_limbah }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-zinc-100/80">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Kategori</span>
                                <span class="inline-flex px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-lg uppercase tracking-wider border border-blue-200">
                                    {{ $waste->kategori }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Sifat Bahaya</span>
                                <div class="text-right text-xs font-semibold text-zinc-700">
                                    @if($waste->sifat_bahaya && count($waste->sifat_bahaya) > 0)
                                        {{ implode(', ', $waste->sifat_bahaya) }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="pt-1">
                                <a href="{{ route('wastes.show', $waste->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 text-xs font-semibold text-zinc-700 bg-zinc-50 border border-zinc-200 rounded-xl hover:bg-zinc-100 transition-colors">
                                    Lihat Detail & Log
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Container -->
            <x-ui.pagination :paginator="$wastes" label="Total Limbah" class="mt-4 border-t border-zinc-100 pt-4" />
        @endif
    </div>
</div>
@endsection
