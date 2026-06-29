@extends('layouts.app')

@section('title', 'Detail alat')

@section('content')
    @php
        $jenisLabel = $alat->jenis_alat ?? '-';
        $kondisiLabel = $alat->kondisi ?? '-';

        $jumlahLabel = '-';
        if (!is_null($alat->jumlah_ml)) {
            $jumlahLabel = format_angka($alat->jumlah_ml) . ' ml';
        } elseif (!is_null($alat->jumlah_satuan)) {
            $jumlahLabel = format_angka($alat->jumlah_satuan) . ' unit';
        }

        $tanggalPembelian = $alat->tanggal_pembelian ? \Carbon\Carbon::parse($alat->tanggal_pembelian)->translatedFormat('d F Y') : '-';
        $tanggalExpired = $alat->tanggal_expired ? \Carbon\Carbon::parse($alat->tanggal_expired)->translatedFormat('d F Y') : '-';
        $hasImage = !empty($alat->gambar) && (is_array($alat->gambar) ? count($alat->gambar) > 0 : is_string($alat->gambar));
        $images = is_array($alat->gambar) ? $alat->gambar : (is_string($alat->gambar) ? [$alat->gambar] : []);
        $imageUrl = count($images) > 0 ? asset('storage/' . $images[0]) : null;

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
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[rgb(var(--color-primary))]">Detail Alat & Bahan</p>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-foreground">{{ $alat->nama_alat }}</h1>
            <p class="text-sm text-foreground-muted mt-1">
                Tampilan dibuat seperti halaman artikel agar konten panjang lebih mudah dipindai.
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <aside class="xl:col-span-3 xl:sticky xl:top-6 self-start space-y-4">
                <div class="ui-surface border border-default/80 rounded-3xl shadow-sm p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Daftar isi</p>
                    <nav class="mt-4 space-y-1">
                        @foreach ($tocItems as $item)
                            <a
                                href="#{{ $item['id'] }}"
                                class="flex items-center justify-between rounded-2xl px-3 py-2 text-sm font-medium text-foreground-muted hover:bg-surface-muted hover:text-[rgb(var(--color-primary))] transition-colors"
                            >
                                <span>{{ $item['label'] }}</span>
                                <span class="text-default">›</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="ui-surface border border-default/80 rounded-3xl shadow-sm p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Quick facts</p>
                    <div class="mt-4 space-y-3">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-foreground-muted/60">Kategori</p>
                            <p class="text-sm font-semibold text-foreground">{{ $jenisLabel }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-foreground-muted/60">Kondisi</p>
                            <p class="text-sm font-semibold text-foreground">{{ $kondisiLabel }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-foreground-muted/60">Jumlah</p>
                            <p class="text-sm font-semibold text-foreground">{{ $jumlahLabel }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-foreground-muted/60">Pembelian</p>
                            <p class="text-sm font-semibold text-foreground">{{ $tanggalPembelian }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-foreground-muted/60">Expired</p>
                            <p class="text-sm font-semibold text-foreground">{{ $tanggalExpired }}</p>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="xl:col-span-9 space-y-6">
                <article id="ringkasan" class="ui-surface border border-default/80 rounded-3xl shadow-sm overflow-hidden">
                    <div class="relative bg-surface-muted">
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
                                        <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-surface border border-default text-foreground-muted/60">
                                            <x-atoms.icon name="cube" class="w-8 h-8" />
                                        </div>
                                        <p class="text-sm font-semibold text-foreground">Tidak ada gambar</p>
                                        <p class="text-xs text-foreground-muted mt-1">Belum ada gambar yang diunggah untuk data ini.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="absolute left-4 top-4 flex gap-2">
                            <span class="rounded-full bg-zinc-900/80 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                                {{ $jenisLabel }}
                            </span>
                            <span class="rounded-full bg-surface/90 px-3 py-1 text-xs font-semibold text-foreground backdrop-blur">
                                {{ $kondisiLabel }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Ringkasan</p>
                        <h2 class="mt-2 text-2xl font-bold text-foreground">{{ $alat->nama_alat }}</h2>
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-foreground-muted">
                            {{ $alat->deskripsi ?? 'Data ini belum memiliki deskripsi.' }}
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <div class="rounded-2xl border border-default bg-surface-muted px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-foreground-muted">Jumlah</p>
                                <p class="mt-1 text-sm font-semibold text-foreground">{{ $jumlahLabel }}</p>
                            </div>
                            <div class="rounded-2xl border border-default bg-surface-muted px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-foreground-muted">Tanggal pembelian</p>
                                <p class="mt-1 text-sm font-semibold text-foreground">{{ $tanggalPembelian }}</p>
                            </div>
                            <div class="rounded-2xl border border-default bg-surface-muted px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-foreground-muted">Tanggal expired</p>
                                <p class="mt-1 text-sm font-semibold text-foreground">{{ $tanggalExpired }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            @if ($alat->link_youtube)
                                <a
                                    href="{{ $alat->link_youtube }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center rounded-xl ui-primary hover:opacity-90 px-4 py-2 text-sm font-semibold shadow-sm transition-opacity"
                                >
                                    Buka YouTube
                                </a>
                            @endif
                            <a
                                href="{{ route('alat') }}"
                                class="inline-flex items-center rounded-xl border border-default ui-surface px-4 py-2 text-sm font-semibold text-foreground-muted hover:border-default hover:bg-surface-muted transition-colors"
                            >
                                Kembali
                            </a>
                        </div>
                    </div>
                </article>

                <section id="informasi-inti" class="ui-surface border border-default/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Informasi inti</p>
                            <h2 class="mt-1 text-xl font-bold text-foreground">Detail singkat</h2>
                        </div>
                        <span class="rounded-full ui-primary-soft px-3 py-1 text-xs font-semibold text-[rgb(var(--color-primary))]">
                            Sekilas
                        </span>
                    </div>

                    <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-default bg-surface-muted p-4">
                            <dt class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Nama</dt>
                            <dd class="mt-1 text-sm font-semibold text-foreground">{{ $alat->nama_alat ?? '-' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-default bg-surface-muted p-4">
                            <dt class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Kategori</dt>
                            <dd class="mt-1 text-sm font-semibold text-foreground">{{ $jenisLabel }}</dd>
                        </div>
                        <div class="rounded-2xl border border-default bg-surface-muted p-4">
                            <dt class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Kondisi</dt>
                            <dd class="mt-1 text-sm font-semibold text-foreground">{{ $kondisiLabel }}</dd>
                        </div>
                        <div class="rounded-2xl border border-default bg-surface-muted p-4">
                            <dt class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Jumlah</dt>
                            <dd class="mt-1 text-sm font-semibold text-foreground">{{ $jumlahLabel }}</dd>
                        </div>
                    </dl>
                </section>

                <section id="deskripsi" class="ui-surface border border-default/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Deskripsi</p>
                            <h2 class="mt-1 text-xl font-bold text-foreground">Ringkasan barang</h2>
                        </div>
                        <span class="rounded-full ui-primary-soft px-3 py-1 text-xs font-semibold text-[rgb(var(--color-primary))]">
                            Singkat
                        </span>
                    </div>

                    <div class="mt-5 rounded-2xl border border-default bg-surface-muted p-5">
                        <p class="whitespace-pre-line text-sm leading-7 text-foreground-muted">
                            {{ $alat->deskripsi ?? 'Tidak ada deskripsi yang diisi.' }}
                        </p>
                    </div>
                </section>

                <section id="spesifikasi" class="ui-surface border border-default/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Spesifikasi</p>
                            <h2 class="mt-1 text-xl font-bold text-foreground">Rincian teknis</h2>
                        </div>
                        <span class="rounded-full bg-surface-muted px-3 py-1 text-xs font-semibold text-foreground-muted">
                            Detail
                        </span>
                    </div>

                    <div class="mt-5 rounded-2xl border border-default bg-surface-muted p-5">
                        <p class="whitespace-pre-line text-sm leading-7 text-foreground-muted">
                            {{ $alat->spesifikasi ?? 'Tidak ada spesifikasi yang diisi.' }}
                        </p>
                    </div>
                </section>

                <section id="panduan" class="ui-surface border border-default/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Panduan</p>
                            <h2 class="mt-1 text-xl font-bold text-foreground">
                                {{ $alat->jenis_alat == 'Bahan' ? 'Informasi penggunaan' : 'Cara penggunaan' }}
                            </h2>
                        </div>
                        <span class="rounded-full ui-primary-soft px-3 py-1 text-xs font-semibold text-[rgb(var(--color-primary))]">
                            Panjang
                        </span>
                    </div>

                    <div class="mt-5 rounded-2xl border border-default bg-surface-muted p-5">
                        @if ($alat->cara_penggunaan)
                            <div class="prose prose-sm max-w-none prose-zinc prose-p:leading-7 prose-li:leading-7 prose-headings:scroll-mt-24">
                                {!! $alat->cara_penggunaan !!}
                            </div>
                        @else
                            <p class="text-sm leading-7 text-foreground-muted">
                                Belum ada panduan penggunaan yang ditambahkan.
                            </p>
                        @endif
                    </div>

                    @if ($alat->link_youtube)
                        <div class="mt-5 rounded-2xl border border-default ui-surface p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Link YouTube</p>
                            <a
                                href="{{ $alat->link_youtube }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-2 block break-all text-sm font-semibold text-[rgb(var(--color-primary))] hover:text-[rgb(var(--color-primary-soft))]"
                            >
                                {{ $alat->link_youtube }}
                            </a>
                        </div>
                    @endif
                </section>

                <section id="media" class="ui-surface border border-default/80 rounded-3xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-foreground-muted">Media</p>
                            <h2 class="mt-1 text-xl font-bold text-foreground">Galeri gambar</h2>
                        </div>
                        <span class="rounded-full bg-surface-muted px-3 py-1 text-xs font-semibold text-foreground-muted">
                            {{ count($images) }} gambar
                        </span>
                    </div>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse ($images as $img)
                            <div class="overflow-hidden rounded-2xl border border-default bg-surface-muted aspect-square group">
                                <a href="{{ asset('storage/' . $img) }}" target="_blank" class="block w-full h-full">
                                    <img
                                        src="{{ asset('storage/' . $img) }}"
                                        alt="{{ $alat->nama_alat }}"
                                        class="h-full w-full object-cover object-center group-hover:scale-110 transition-transform duration-500"
                                    >
                                </a>
                            </div>
                        @empty
                            <div class="sm:col-span-2 md:col-span-3 flex h-40 items-center justify-center rounded-2xl border border-dashed border-default bg-surface-muted">
                                <p class="text-sm text-foreground-muted">Belum ada gambar yang tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection
