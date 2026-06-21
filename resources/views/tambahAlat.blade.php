@extends('layouts.app')

@section('title', 'Tambah Alat / Bahan')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-zinc-500 mb-2">
        <a href="{{ route('alat') }}" class="hover:text-indigo-600 transition-colors">Alat & Bahan</a>
        <span class="material-symbols-rounded text-[16px]">chevron_right</span>
        <span class="font-semibold text-zinc-900">Tambah Data Baru</span>
    </div>
    <h4 class="text-2xl font-bold text-zinc-900">Tambah Alat / Bahan</h4>
    <p class="text-sm font-medium text-zinc-500 mt-1">
        Masukkan informasi detail mengenai alat atau bahan baru yang masuk ke laboratorium.
    </p>
</div>

<x-alert />

<form action="{{ route('add.alat') }}" method="POST" enctype="multipart/form-data" 
      x-data="{ kategori: '{{ old('jenis_alat', 'Alat') }}' }" 
      class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    @csrf

    {{-- Left Card: Informasi Utama --}}
    <div class="lg:col-span-2 bg-white border border-zinc-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-zinc-100/80 bg-zinc-50/50">
            <h5 class="text-sm font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-500 text-[20px]">info</span>
                Informasi Utama
            </h5>
        </div>
        
        <div class="p-6 space-y-6">
            {{-- Nama Barang --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Nama Barang <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_alat" value="{{ old('nama_alat') }}" required
                    class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors"
                    placeholder="Masukkan nama alat atau bahan...">
                @error('nama_alat') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori (Segmented Control) --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Kategori Barang <span class="text-rose-500">*</span></label>
                <div class="flex p-1 bg-zinc-100/80 rounded-xl w-full">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="jenis_alat" value="Alat" x-model="kategori" class="peer sr-only">
                        <div class="w-full py-2.5 text-sm font-bold text-center text-zinc-500 rounded-lg peer-checked:bg-white peer-checked:text-blue-700 peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-zinc-200 transition-all">
                            Alat
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="jenis_alat" value="Bahan" x-model="kategori" class="peer sr-only">
                        <div class="w-full py-2.5 text-sm font-bold text-center text-zinc-500 rounded-lg peer-checked:bg-white peer-checked:text-purple-700 peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-zinc-200 transition-all">
                            Bahan
                        </div>
                    </label>
                </div>
                @error('jenis_alat') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Horizontal Row: Kondisi & Jumlah --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kondisi --}}
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Kondisi Barang</label>
                    <div class="relative">
                        <select name="kondisi" class="w-full pl-4 pr-10 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors appearance-none">
                            <option value="">Pilih Kondisi...</option>
                            <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500">
                            <span class="material-symbols-rounded text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>

                {{-- Jumlah Dinamis --}}
                <div class="relative min-h-[70px]">
                    {{-- Jumlah Alat --}}
                    <div x-show="kategori === 'Alat'" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute inset-0 w-full"
                         style="display: none;">
                        <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Jumlah Satuan (unit) <span class="text-rose-500">*</span></label>
                        <input type="number" name="jumlah_satuan" value="{{ old('jumlah_satuan') }}" min="0"
                            class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-colors"
                            placeholder="Contoh: 10">
                        @error('jumlah_satuan') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jumlah Bahan --}}
                    <div x-show="kategori === 'Bahan'" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute inset-0 w-full"
                         style="display: none;">
                        <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Jumlah Takaran (ml) <span class="text-rose-500">*</span></label>
                        <input type="number" name="jumlah_ml" value="{{ old('jumlah_ml') }}" min="0" step="0.1"
                            class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:bg-white transition-colors"
                            placeholder="Contoh: 500">
                        @error('jumlah_ml') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Spesifikasi & Deskripsi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Spesifikasi</label>
                    <textarea name="spesifikasi" rows="4"
                        class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors resize-none"
                        placeholder="Merek, tipe, dimensi, dll...">{{ old('spesifikasi') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors resize-none"
                        placeholder="Penjelasan fungsi barang...">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
            
        </div>
    </div>

    {{-- Right Card: Media & Metadata --}}
    <div class="lg:col-span-1 space-y-6">
        
        {{-- Card Media --}}
        <div class="bg-white border border-zinc-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-zinc-100/80 bg-zinc-50/50">
                <h5 class="text-sm font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-rounded text-indigo-500 text-[20px]">image</span>
                    Media Barang
                </h5>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-zinc-300 border-dashed rounded-2xl cursor-pointer bg-zinc-50 hover:bg-zinc-100 hover:border-indigo-400 transition-colors group">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-rounded text-[24px]">cloud_upload</span>
                            </div>
                            <p class="mb-1 text-sm text-zinc-600 font-semibold"><span class="text-indigo-600">Klik untuk upload</span> atau drag and drop</p>
                            <p class="text-xs text-zinc-500">SVG, PNG, JPG (MAX. 5MB)</p>
                        </div>
                        <input id="dropzone-file" type="file" name="gambar" accept="image/*" class="hidden" />
                    </label>
                </div>
                @error('gambar') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Card Metadata --}}
        <div class="bg-white border border-zinc-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-zinc-100/80 bg-zinc-50/50">
                <h5 class="text-sm font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-rounded text-indigo-500 text-[20px]">date_range</span>
                    Metadata Tambahan
                </h5>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Tanggal Pembelian</label>
                    <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian') }}"
                        class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
                </div>

                {{-- Tanggal Expired hanya relevan untuk Bahan, tapi kita sembunyikan dengan alpine jika Alat --}}
                <div x-show="kategori === 'Bahan'" x-collapse>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Tanggal Expired <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_expired" value="{{ old('tanggal_expired') }}"
                        class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:bg-white transition-colors">
                    @error('tanggal_expired') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Link Tutorial (YouTube)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-rose-500 text-[20px]">play_circle</span>
                        </div>
                        <input type="url" name="link_youtube" value="{{ old('link_youtube') }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors"
                            placeholder="https://youtube.com/...">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Cara Penggunaan</label>
                    <textarea name="cara_penggunaan" rows="2"
                        class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors resize-none"
                        placeholder="Langkah-langkah penggunaan...">{{ old('cara_penggunaan') }}</textarea>
                </div>
            </div>
            
            {{-- Submit Area --}}
            <div class="p-6 pt-0">
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 text-white font-bold rounded-xl px-4 py-3 shadow-sm shadow-indigo-600/20 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="material-symbols-rounded text-[20px]">save</span>
                    Simpan Data
                </button>
            </div>
        </div>

    </div>
</form>

@endsection