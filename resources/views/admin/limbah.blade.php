@extends('layouts.app')

@section('title', 'Limbah')

@section('content')
    <div class="px-2 pb-8 space-y-6">
        <x-ui.page-header title="Limbah" description="Informasi limbah dan cara pengelolaan pasca pakai.">
            @if (Auth::user()->jabatan !== 'Mahasiswa')
                <a href="{{ route('tambahLimbah') }}"
                    class="w-full md:w-auto inline-flex items-center justify-center rounded-xl ui-primary px-4 h-11 md:h-10 font-semibold text-sm md:text-xs shadow-sm shadow-[rgb(var(--color-primary))_/_0.1] hover:opacity-90 transition-colors focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2">
                    <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
                    Tambah Limbah
                </a>
            @endif
        </x-ui.page-header>

        <div class="ui-surface border border-zinc-200/80 rounded-3xl p-4 shadow-sm">
            <form class="w-full">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <x-atoms.icon name="search" class="w-4 h-4 text-zinc-400" />
                    </div>
                    <input type="search" id="search" name="cari"
                        class="block w-full h-12 pl-10 pr-4 text-sm text-zinc-800 border border-zinc-200 rounded-xl bg-zinc-50 focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))]"
                        placeholder="Cari limbah..." />
                    <button type="submit"
                        class="absolute end-2.5 bottom-2.5 ui-primary hover:opacity-90 font-semibold rounded-lg text-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))]">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <x-alert />

        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-zinc-900">Daftar Limbah</h2>
                <p class="text-sm text-zinc-500">Menampilkan {{ $limbah->count() }} data limbah.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            @foreach ($limbah as $lbh)
                <article class="ui-surface border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <h3 class="text-lg font-bold text-zinc-900 leading-tight">{{ $lbh->nama_limbah }}</h3>
                            <x-ui.badge type="primary">Limbah</x-ui.badge>
                        </div>
                        <p class="text-sm text-zinc-600 leading-6">
                            {{ strlen($lbh->cara_pengolahan) > 120 ? substr($lbh->cara_pengolahan, 0, 120) . '...' : $lbh->cara_pengolahan }}
                        </p>
                    </div>

                    <div class="p-5 pt-0 flex items-center gap-3">
                        <a href="{{ route('detailLimbah', $lbh->id) }}"
                            class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-800 transition-colors">
                            Lihat Detail
                        </a>
                        @if (Auth::user()->jabatan !== 'Mahasiswa')
                            <form action="{{ route('hapusLimbah', $lbh->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endsection
