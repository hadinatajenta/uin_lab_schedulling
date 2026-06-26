@extends('layouts.app')

@section('title', $mode === 'create' ? 'Tambah Master Limbah' : 'Edit Master Limbah')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">{{ $mode === 'create' ? 'Tambah Limbah Baru' : 'Edit Data Limbah' }}</h1>
            <p class="text-sm text-zinc-500 mt-1">Masukkan informasi detail terkait spesifikasi dan penanganan limbah B3.</p>
        </div>
        <a href="{{ route('wastes.index') }}">
            <x-secondary-button type="button">
                <span class="material-symbols-rounded text-[20px] mr-2">arrow_back</span>
                Kembali
            </x-secondary-button>
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-rose-800 rounded-lg bg-rose-50 border border-rose-200" role="alert">
            <span class="font-bold">Error!</span> Terdapat kesalahan pada input Anda. Silakan periksa kembali form di bawah.
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
        <form action="{{ $mode === 'create' ? route('wastes.store') : route('wastes.update', $waste->id ?? '') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8">
            @csrf
            @if($mode === 'edit')
                @method('PUT')
            @endif

            <!-- Section 1: Informasi Dasar -->
            <div>
                <h3 class="text-lg font-bold text-zinc-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-rounded text-blue-500">info</span>
                    Informasi Dasar
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-input-label for="kode_limbah" value="Kode Limbah *" />
                        <x-text-input id="kode_limbah" name="kode_limbah" type="text" class="w-full font-mono uppercase" :value="old('kode_limbah', $waste->kode_limbah ?? '')" required placeholder="Contoh: L-B3-001" />
                        <x-input-error :messages="$errors->get('kode_limbah')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="nama_limbah" value="Nama Limbah *" />
                        <x-text-input id="nama_limbah" name="nama_limbah" type="text" class="w-full" :value="old('nama_limbah', $waste->nama_limbah ?? '')" required placeholder="Contoh: Sisa Pelarut Asam" />
                        <x-input-error :messages="$errors->get('nama_limbah')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="kategori" value="Kategori *" />
                        <select id="kategori" name="kategori" required class="w-full border-gray-300 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary))] rounded-md shadow-sm">
                            <option value="">Pilih Kategori...</option>
                            @foreach(['Padat', 'Cair', 'Gas', 'Infeksius'] as $kat)
                                <option value="{{ $kat }}" {{ old('kategori', $waste->kategori ?? '') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                    </div>
                </div>
            </div>

            <hr class="border-zinc-100">

            <!-- Section 2: GHS & Visual -->
            <div>
                <h3 class="text-lg font-bold text-zinc-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-rounded text-amber-500">warning</span>
                    Spesifikasi Bahaya (GHS) & Visual
                </h3>
                
                <div class="space-y-6">
                    <div class="space-y-3">
                        <x-input-label value="Sifat Bahaya (Bisa pilih lebih dari satu)" />
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $sifat_bahaya = old('sifat_bahaya', $waste->sifat_bahaya ?? []);
                            @endphp
                            @foreach(['Beracun', 'Mudah Terbakar', 'Korosif', 'Infeksius', 'Reaktif', 'Iritan'] as $bahaya)
                                <label class="flex items-center gap-3 p-3 border border-zinc-200 rounded-xl cursor-pointer hover:bg-zinc-50 transition-colors {{ in_array($bahaya, $sifat_bahaya) ? 'ring-2 ring-blue-500 border-blue-500 bg-blue-50/30' : '' }}">
                                    <input type="checkbox" name="sifat_bahaya[]" value="{{ $bahaya }}" {{ in_array($bahaya, $sifat_bahaya) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-zinc-700">{{ $bahaya }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="gambar_panduan" value="Gambar Panduan / Label Jerigen" />
                        @if(isset($waste) && $waste->gambar_panduan)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $waste->gambar_panduan) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-xl border border-zinc-200 shadow-sm">
                            </div>
                        @endif
                        <input id="gambar_panduan" type="file" name="gambar_panduan" accept="image/*" class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-zinc-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                        <x-input-error :messages="$errors->get('gambar_panduan')" class="mt-2" />
                    </div>
                </div>
            </div>

            <hr class="border-zinc-100">

            <!-- Section 3: Penanganan -->
            <div>
                <h3 class="text-lg font-bold text-zinc-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-rounded text-emerald-500">health_and_safety</span>
                    SOP & Penanganan Darurat
                </h3>
                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <x-input-label for="cara_penanganan" value="Cara Penanganan Awal" />
                        <textarea id="cara_penanganan" name="cara_penanganan" rows="3" placeholder="SOP saat mengumpulkan dan menampung limbah ini..." class="w-full border-gray-300 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary))] rounded-md shadow-sm">{{ old('cara_penanganan', $waste->cara_penanganan ?? '') }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="prosedur_darurat" value="Prosedur Darurat (Jika Tumpah / Terkena Kulit)" />
                        <textarea id="prosedur_darurat" name="prosedur_darurat" rows="3" placeholder="Langkah-langkah pertolongan pertama..." class="w-full border-gray-300 focus:border-[rgb(var(--color-primary))] focus:ring-[rgb(var(--color-primary))] rounded-md shadow-sm">{{ old('prosedur_darurat', $waste->prosedur_darurat ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex justify-end">
                <x-primary-button>
                    <span class="material-symbols-rounded text-[20px] mr-2">save</span>
                    {{ $mode === 'create' ? 'Simpan Katalog Limbah' : 'Simpan Perubahan' }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
