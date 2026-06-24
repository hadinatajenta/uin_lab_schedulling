{{-- Left Card: Informasi Utama --}}
<div class="lg:col-span-2 ui-surface border border-zinc-200/80 rounded-2xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-zinc-100/80 bg-zinc-50/50">
        <h5 class="text-sm font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-2">
            <span class="material-symbols-rounded text-[rgb(var(--color-primary))] text-[20px]">info</span>
            Informasi Utama
        </h5>
    </div>
    
    <div class="p-6 space-y-6">
        {{-- Nama Barang --}}
        <div>
            <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Nama Barang <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_alat" value="{{ old('nama_alat', $alat->nama_alat ?? '') }}" required
                class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors"
                placeholder="Masukkan nama alat atau bahan...">
            @error('nama_alat') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Kategori (Segmented Control) --}}
        <div>
            <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Kategori Barang <span class="text-rose-500">*</span></label>
            <div class="flex p-1 bg-zinc-100/80 rounded-xl w-full">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="jenis_alat" value="Alat" x-model="kategori" class="peer sr-only">
                    <div class="w-full py-2.5 text-sm font-bold text-center text-zinc-500 rounded-lg peer-checked:ui-surface peer-checked:text-[rgb(var(--color-primary))] peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-zinc-200 transition-all">
                        Alat
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="jenis_alat" value="Bahan" x-model="kategori" class="peer sr-only">
                    <div class="w-full py-2.5 text-sm font-bold text-center text-zinc-500 rounded-lg peer-checked:ui-surface peer-checked:text-[rgb(var(--color-primary))] peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-zinc-200 transition-all">
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
                    <select name="kondisi" class="w-full pl-4 pr-10 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors appearance-none">
                        <option value="">Pilih Kondisi...</option>
                        <option value="Baru" {{ old('kondisi', $alat->kondisi ?? '') == 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Bekas" {{ old('kondisi', $alat->kondisi ?? '') == 'Bekas' ? 'selected' : '' }}>Bekas</option>
                        <option value="Baik" {{ old('kondisi', $alat->kondisi ?? '') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi', $alat->kondisi ?? '') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi', $alat->kondisi ?? '') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="Rusak" {{ old('kondisi', $alat->kondisi ?? '') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="Hampir habis" {{ old('kondisi', $alat->kondisi ?? '') == 'Hampir habis' ? 'selected' : '' }}>Hampir habis</option>
                        <option value="Habis" {{ old('kondisi', $alat->kondisi ?? '') == 'Habis' ? 'selected' : '' }}>Habis</option>
                        <option value="Layak" {{ old('kondisi', $alat->kondisi ?? '') == 'Layak' ? 'selected' : '' }}>Layak</option>
                        <option value="Tidak Layak" {{ old('kondisi', $alat->kondisi ?? '') == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
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
                    <input type="number" name="jumlah_satuan" value="{{ old('jumlah_satuan', $alat->jumlah_satuan ?? '') }}" min="0"
                        class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors"
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
                    <input type="number" name="jumlah_ml" value="{{ old('jumlah_ml', $alat->jumlah_ml ?? '') }}" min="0" step="0.1"
                        class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors"
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
                    class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors resize-none"
                    placeholder="Merek, tipe, dimensi, dll...">{{ old('spesifikasi', $alat->spesifikasi ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors resize-none"
                    placeholder="Penjelasan fungsi barang...">{{ old('deskripsi', $alat->deskripsi ?? '') }}</textarea>
            </div>
        </div>
        
    </div>
</div>

{{-- Right Card: Media & Metadata --}}
<div class="lg:col-span-1 space-y-6">
    
    {{-- Card Media --}}
    <div class="ui-surface border border-zinc-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-zinc-100/80 bg-zinc-50/50">
            <h5 class="text-sm font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-[rgb(var(--color-primary))] text-[20px]">image</span>
                Media Barang
            </h5>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-center w-full relative">
                {{-- Preview Image --}}
                <div class="absolute inset-0 w-full h-full p-2 pointer-events-none" style="display: {{ !empty($alat->gambar) ? 'block' : 'none' }}" id="image-preview-container">
                    <img id="image-preview" src="{{ !empty($alat->gambar) ? asset('storage/'.$alat->gambar) : '' }}" class="w-full h-full object-contain rounded-xl bg-zinc-50" />
                </div>
                
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-zinc-300 border-dashed rounded-2xl cursor-pointer bg-zinc-50/50 hover:bg-zinc-100 hover:border-[rgb(var(--color-primary))] transition-colors group">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6 bg-white/60 backdrop-blur-sm rounded-2xl px-4 text-center">
                        <div class="w-12 h-12 rounded-full ui-primary-soft text-[rgb(var(--color-primary))] flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-[24px]">cloud_upload</span>
                        </div>
                        <p class="mb-1 text-sm text-zinc-600 font-semibold"><span class="text-[rgb(var(--color-primary))]">Klik untuk upload</span> atau drag and drop</p>
                        <p class="text-xs text-zinc-500">SVG, PNG, JPG (MAX. 5MB)</p>
                    </div>
                    <input id="dropzone-file" type="file" name="gambar" accept="image/*" class="hidden" onchange="previewImage(event)" />
                </label>
            </div>
            @error('gambar') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Card Metadata --}}
    <div class="ui-surface border border-zinc-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-zinc-100/80 bg-zinc-50/50">
            <h5 class="text-sm font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-rounded text-[rgb(var(--color-primary))] text-[20px]">date_range</span>
                Metadata Tambahan
            </h5>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', $alat->tanggal_pembelian ?? '') }}"
                    class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors">
            </div>

            {{-- Tanggal Expired hanya relevan untuk Bahan, tapi kita sembunyikan dengan alpine jika Alat --}}
            <div x-show="kategori === 'Bahan'" x-collapse>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Tanggal Expired <span class="text-rose-500">*</span></label>
                <input type="date" name="tanggal_expired" value="{{ old('tanggal_expired', $alat->tanggal_expired ?? '') }}"
                    class="w-full px-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors">
                @error('tanggal_expired') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Link Tutorial (YouTube)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-rounded text-rose-500 text-[20px]">play_circle</span>
                    </div>
                    <input type="url" name="link_youtube" value="{{ old('link_youtube', $alat->link_youtube ?? '') }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors"
                        placeholder="https://youtube.com/...">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Cara Penggunaan</label>
                <textarea name="cara_penggunaan" rows="2"
                    class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 text-zinc-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:border-[rgb(var(--color-primary))] focus:ui-surface transition-colors resize-none"
                    placeholder="Langkah-langkah penggunaan...">{{ old('cara_penggunaan', $alat->cara_penggunaan ?? '') }}</textarea>
            </div>
        </div>
        
        {{-- Submit Area --}}
        <div class="p-6 pt-0">
            <button type="submit" class="w-full flex items-center justify-center gap-2 ui-primary hover:opacity-90 font-bold rounded-xl px-4 py-3 shadow-sm shadow-[rgb(var(--color-primary))_/_0.2] hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-offset-2 focus:ring-[rgb(var(--color-primary))]">
                <span class="material-symbols-rounded text-[20px]">{{ $mode === 'create' ? 'add_circle' : 'save' }}</span>
                {{ $mode === 'create' ? 'Simpan Data Baru' : 'Perbarui Data' }}
            </button>
        </div>
    </div>

</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview-container').style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('image-preview').src = '';
            document.getElementById('image-preview-container').style.display = 'none';
        }
    }
</script>
