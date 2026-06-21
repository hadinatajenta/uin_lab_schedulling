@extends('layouts.app')

@section('title', 'Detail alat')

@section('content')
    @php
        $jenisLabel = $alat->jenis_alat ?? '-';
        $kondisiLabel = $alat->kondisi ?? '-';

        $jumlahLabel = '-';
        if (!is_null($alat->jumlah_ml)) {
            $jumlahLabel = $alat->jumlah_ml . ' ml';
        } elseif (!is_null($alat->jumlah_satuan)) {
            $jumlahLabel = $alat->jumlah_satuan . ' unit';
        }

        $tanggalPembelian = $alat->tanggal_pembelian ? \Carbon\Carbon::parse($alat->tanggal_pembelian)->translatedFormat('d F Y') : '-';
        $tanggalExpired = $alat->tanggal_expired ? \Carbon\Carbon::parse($alat->tanggal_expired)->translatedFormat('d F Y') : '-';
        $hasImage = !empty($alat->gambar);
        $imageUrl = $hasImage ? asset('storage/' . $alat->gambar) : null;

        $tocItems = [
            ['id' => 'ringkasan', 'label' => 'Ringkasan'],
            ['id' => 'informasi-inti', 'label' => 'Informasi inti'],
            ['id' => 'deskripsi', 'label' => 'Deskripsi'],
            ['id' => 'spesifikasi', 'label' => 'Spesifikasi'],
            ['id' => 'panduan', 'label' => 'Panduan'],
            ['id' => 'media', 'label' => 'Media'],
        ];
    @endphp

    <div class="px-2 pb-12 max-w-7xl mx-auto">
        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">Detail Alat & Bahan</p>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-zinc-900">{{ $alat->nama_alat }}</h1>
            <p class="text-sm text-zinc-500 mt-1">
                Tampilan dibuat seperti halaman artikel agar konten panjang lebih mudah dipindai.
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <aside class="xl:col-span-3 xl:sticky xl:top-6 self-start space-y-4">
                <div class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Daftar isi</p>
                    <nav class="mt-4 space-y-1">
                        @foreach ($tocItems as $item)
                            <a
                                href="#{{ $item['id'] }}"
                                class="flex items-center justify-between rounded-2xl px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-50 hover:text-indigo-700 transition-colors"
                            >
                                <span>{{ $item['label'] }}</span>
                                <span class="text-zinc-300">›</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Quick facts</p>
                    <div class="mt-4 space-y-3">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Kategori</p>
                            <p class="text-sm font-semibold text-zinc-900">{{ $jenisLabel }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Kondisi</p>
                            <p class="text-sm font-semibold text-zinc-900">{{ $kondisiLabel }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Jumlah</p>
                            <p class="text-sm font-semibold text-zinc-900">{{ $jumlahLabel }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Pembelian</p>
                            <p class="text-sm font-semibold text-zinc-900">{{ $tanggalPembelian }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Expired</p>
                            <p class="text-sm font-semibold text-zinc-900">{{ $tanggalExpired }}</p>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="xl:col-span-9 space-y-6">
                <article id="ringkasan" class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden">
                    <div class="relative bg-zinc-50">
                        <div class="aspect-[16/8] w-full">
                            @if ($hasImage)
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $alat->nama_alat }}"
                                    class="h-full w-full object-cover object-center"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <div class="text-center px-6">
                                        <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-white border border-zinc-200 text-zinc-400">
                                            <x-atoms.icon name="cube" class="w-8 h-8" />
                                        </div>
                                        <p class="text-sm font-semibold text-zinc-900">Tidak ada gambar</p>
                                        <p class="text-xs text-zinc-500 mt-1">Siap dikembangkan ke galeri multi-gambar.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="absolute left-4 top-4 flex gap-2">
                            <span class="rounded-full bg-zinc-900/80 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                                {{ $jenisLabel }}
                            </span>
                            <span class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-zinc-800 backdrop-blur">
                                {{ $kondisiLabel }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Ringkasan</p>
                        <h2 class="mt-2 text-2xl font-bold text-zinc-900">{{ $alat->nama_alat }}</h2>
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-zinc-600">
                            {{ $alat->deskripsi ?? 'Data ini belum memiliki deskripsi.' }}
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Jumlah</p>
                                <p class="mt-1 text-sm font-semibold text-zinc-900">{{ $jumlahLabel }}</p>
                            </div>
                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Tanggal pembelian</p>
                                <p class="mt-1 text-sm font-semibold text-zinc-900">{{ $tanggalPembelian }}</p>
                            </div>
                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Tanggal expired</p>
                                <p class="mt-1 text-sm font-semibold text-zinc-900">{{ $tanggalExpired }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            @if ($alat->link_youtube)
                                <a
                                    href="{{ $alat->link_youtube }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors"
                                >
                                    Buka YouTube
                                </a>
                            @endif
                            <a
                                href="{{ route('alat') }}"
                                class="inline-flex items-center rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:border-zinc-300 hover:bg-zinc-50 transition-colors"
                            >
                                Kembali
                            </a>
                        </div>
                    </div>
                </article>

                <section id="informasi-inti" class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Informasi inti</p>
                            <h2 class="mt-1 text-xl font-bold text-zinc-900">Detail singkat</h2>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                            Sekilas
                        </span>
                    </div>

                    <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4">
                            <dt class="text-xs font-bold uppercase tracking-wider text-zinc-500">Nama</dt>
                            <dd class="mt-1 text-sm font-semibold text-zinc-900">{{ $alat->nama_alat ?? '-' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4">
                            <dt class="text-xs font-bold uppercase tracking-wider text-zinc-500">Kategori</dt>
                            <dd class="mt-1 text-sm font-semibold text-zinc-900">{{ $jenisLabel }}</dd>
                        </div>
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4">
                            <dt class="text-xs font-bold uppercase tracking-wider text-zinc-500">Kondisi</dt>
                            <dd class="mt-1 text-sm font-semibold text-zinc-900">{{ $kondisiLabel }}</dd>
                        </div>
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4">
                            <dt class="text-xs font-bold uppercase tracking-wider text-zinc-500">Jumlah</dt>
                            <dd class="mt-1 text-sm font-semibold text-zinc-900">{{ $jumlahLabel }}</dd>
                        </div>
                    </dl>
                </section>

                <section id="deskripsi" class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Deskripsi</p>
                            <h2 class="mt-1 text-xl font-bold text-zinc-900">Ringkasan barang</h2>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Singkat
                        </span>
                    </div>

                    <div class="mt-5 rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                        <p class="whitespace-pre-line text-sm leading-7 text-zinc-700">
                            {{ $alat->deskripsi ?? 'Tidak ada deskripsi yang diisi.' }}
                        </p>
                    </div>
                </section>

                <section id="spesifikasi" class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Spesifikasi</p>
                            <h2 class="mt-1 text-xl font-bold text-zinc-900">Rincian teknis</h2>
                        </div>
                        <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                            Detail
                        </span>
                    </div>

                    <div class="mt-5 rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                        <p class="whitespace-pre-line text-sm leading-7 text-zinc-700">
                            {{ $alat->spesifikasi ?? 'Tidak ada spesifikasi yang diisi.' }}
                        </p>
                    </div>
                </section>

                <section id="panduan" class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Panduan</p>
                            <h2 class="mt-1 text-xl font-bold text-zinc-900">
                                {{ $alat->jenis_alat == 'Bahan' ? 'Informasi penggunaan' : 'Cara penggunaan' }}
                            </h2>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                            Panjang
                        </span>
                    </div>

                    <div class="mt-5 rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                        @if ($alat->cara_penggunaan)
                            <div class="prose prose-sm max-w-none prose-zinc prose-p:leading-7 prose-li:leading-7 prose-headings:scroll-mt-24">
                                {!! $alat->cara_penggunaan !!}
                            </div>
                        @else
                            <p class="text-sm leading-7 text-zinc-600">
                                Belum ada panduan penggunaan yang ditambahkan.
                            </p>
                        @endif
                    </div>

                    @if ($alat->link_youtube)
                        <div class="mt-5 rounded-2xl border border-zinc-200 bg-white p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Link YouTube</p>
                            <a
                                href="{{ $alat->link_youtube }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-2 block break-all text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                            >
                                {{ $alat->link_youtube }}
                            </a>
                        </div>
                    @endif
                </section>

                <section id="media" class="bg-white border border-zinc-200/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Media</p>
                            <h2 class="mt-1 text-xl font-bold text-zinc-900">Gambar alat</h2>
                        </div>
                        <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                            1 slot
                        </span>
                    </div>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if ($hasImage)
                            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50">
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $alat->nama_alat }}"
                                    class="h-64 w-full object-cover object-center"
                                >
                            </div>
                        @else
                            <div class="flex h-64 items-center justify-center rounded-2xl border border-dashed border-zinc-200 bg-zinc-50">
                                <p class="text-sm text-zinc-500">Belum ada gambar yang tersedia.</p>
                            </div>
                        @endif

                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                            <p class="text-sm font-semibold text-zinc-900">Catatan pengembangan</p>
                            <p class="mt-2 text-sm leading-7 text-zinc-600">
                                Struktur ini sudah disiapkan agar nanti bisa ditambah galeri multi-gambar tanpa mengubah layout utama.
                            </p>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection
