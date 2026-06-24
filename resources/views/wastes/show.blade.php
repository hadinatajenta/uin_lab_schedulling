@extends('layouts.app')

@section('title', 'Detail limbah')

@section('content')
    @php
        $createdAt = \Carbon\Carbon::parse($findLimbah->created_at)->format('d F Y');
    @endphp

    <div class="px-2 pb-12 max-w-7xl mx-auto">
        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[rgb(var(--color-primary))]">Detail Limbah</p>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-zinc-900">{{ $findLimbah->nama_limbah }}</h1>
            <p class="text-sm text-zinc-500 mt-1">Published on {{ $createdAt }}</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <aside class="xl:col-span-3 xl:sticky xl:top-6 self-start">
                <div class="ui-surface border border-zinc-200/80 rounded-3xl shadow-sm p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Daftar isi</p>
                    <nav class="mt-4 space-y-1">
                        <a href="#cara-penggunaan" class="flex items-center justify-between rounded-2xl px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-50 hover:text-[rgb(var(--color-primary))] transition-colors">
                            <span>Cara penggunaan</span><span class="text-zinc-300">›</span>
                        </a>
                        <a href="#materi" class="flex items-center justify-between rounded-2xl px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-50 hover:text-[rgb(var(--color-primary))] transition-colors">
                            <span>Materi</span><span class="text-zinc-300">›</span>
                        </a>
                        <a href="#cara-pengolahan" class="flex items-center justify-between rounded-2xl px-3 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-50 hover:text-[rgb(var(--color-primary))] transition-colors">
                            <span>Cara pengolahan</span><span class="text-zinc-300">›</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <main class="xl:col-span-9">
                <article class="ui-surface border border-zinc-200/80 rounded-3xl shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8 space-y-8">
                        <section id="cara-penggunaan">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <h2 class="text-xl font-bold text-zinc-900">Cara penggunaan</h2>
                                <x-ui.badge type="primary">Panduan</x-ui.badge>
                            </div>
                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                <p class="text-sm leading-7 text-zinc-700">{{ $findLimbah->cara_penggunaan }}</p>
                            </div>
                        </section>

                        <section id="materi">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <h2 class="text-xl font-bold text-zinc-900">Materi</h2>
                                <x-ui.badge type="primary">Konten</x-ui.badge>
                            </div>
                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 prose prose-sm max-w-none prose-zinc">
                                {!! $findLimbah->materi !!}
                            </div>
                        </section>

                        <section id="cara-pengolahan">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <h2 class="text-xl font-bold text-zinc-900">Cara pengolahan</h2>
                                <x-ui.badge type="warning">Penting</x-ui.badge>
                            </div>
                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                <p class="text-sm leading-7 text-zinc-700">{{ $findLimbah->cara_pengolahan }}</p>
                            </div>
                        </section>
                    </div>
                </article>
            </main>
        </div>
    </div>
@endsection
