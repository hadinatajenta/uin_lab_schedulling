@extends('layouts.app')

@section('title', 'Tambah alat')

@section('content')
    <div>
        <div class="flex flex-col md:flex-row items-center justify-start md:justify-between mb-4  ">
            <div>
                <h4 class="text-2xl font-bold dark:text-white">Tambah data alat / bahan</h4>
                <p class="text-sm font-normal text-gray-500 lg:text-sm dark:text-gray-400">
                    Disini kamu bisa menambahkan data alat atau bahan.
                </p>
            </div>
        </div>
        <hr>
        <x-alert />
    </div>
    <div class="mx-auto mt-4">
        <div class="grid grid-cols-12 ">
            <form action="{{ route('post.alat') }}" method="POST"
                class="col-span-12 sm:col-span-12 md:col-span-6 lg:col-span-6 lg:col-start-4" enctype="multipart/form-data">
                @csrf

                {{-- Nama alat --}}
                <div class="bg-white p-4 rounded-lg mb-4">
                    <label for="nama_alat" class="block text-sm font-medium text-gray-700">Nama Alat <span
                            class="text-red-800">*</span> </label>
                    <input type="text" id="nama_alat" name="nama_alat" placeholder="Masukkan nama alat..."
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('nama_alat') }}">
                    @error('nama_alat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 p-4 bg-white rounded-lg">
                    <label for="jenis_alat" class="block text-sm font-medium text-gray-700">Jenis Alat</label>
                    <div class="grid grid-cols-2 gap-4 mt-1">
                        <div>
                            <div class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
                                <input id="jenis_padat" type="radio" value="Benda Padat" name="jenis_alat"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    checked onclick="toggleFields()">
                                <label for="jenis_padat"
                                    class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Benda
                                    Padat</label>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
                                <input id="jenis_cair" type="radio" value="Benda Cair" name="jenis_alat"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    onclick="toggleFields()">
                                <label for="jenis_cair"
                                    class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Benda
                                    Cair</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- deskripsi --}}
                <div class="bg-white p-4 rounded-lg mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <input id="deskripsi " name="deskripsi" placeholder="Masukkan deskripsi alat..."
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('deskripsi') }}">

                    @error('deskripsi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- spesifikasi --}}
                <div class="bg-white p-4 rounded-lg mb-4">
                    <label for="spesifikasi" class="block text-sm font-medium text-gray-700">Spesifikasi</label>
                    <textarea id="spesifikasi" name="spesifikasi" placeholder="Masukkan spesifikasi dari alat / bahan"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('spesifikasi') }}</textarea>
                    @error('spesifikasi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kondisi alat --}}
                <div class="bg-white p-4 rounded-lg mb-4">
                    <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi alat / bahan</label>

                    <div class="flex items-center mb-4">
                        <input id="kondisi" type="radio" value="Baru" name="kondisi"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="kondisi" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Baru</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input checked id="kondisi-2" type="radio" value="Bekas" name="kondisi"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="kondisi-2"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Bekas</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input checked id="kondisi-3" type="radio" value="Rusak" name="kondisi"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="kondisi-3"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Rusak</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input checked id="kondisi-4" type="radio" value="Hampir habis" name="kondisi"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="kondisi-4" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Hampir
                            habis</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input checked id="kondisi-5" type="radio" value="Habis" name="kondisi"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="kondisi-5" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Habis</label>
                    </div>
                </div>

                {{-- Gambar --}}
                <div class="bg-white p-4 rounded-lg mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="gambar">Upload
                        Gambar</label>
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="gambar" name="gambar" type="file" accept="image/*" onchange="previewImage(event)">

                    @error('gambar')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-4">
                        <img id="image-preview" class="hidden w-24 h-auto rounded-lg" />
                    </div>
                </div>

                {{-- Jumlah --}}
                <div id="jumlah_satuan_field" class="bg-white p-4 rounded-lg mb-4">
                    <label for="jumlah_satuan" class="block text-sm font-medium text-gray-700">Jumlah Satuan</label>
                    <input type="number" id="jumlah_satuan" name="jumlah_satuan"
                        placeholder="Masukkan jumlah satuan dalam hitungan unit, misal = 1 unit"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('jumlah_satuan') }}">
                    @error('jumlah_satuan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="jumlah_ml_field" class="bg-white p-4 rounded-lg mb-4 hidden">
                    <label for="jumlah_ml" class="block text-sm font-medium text-gray-700">Jumlah (ml)</label>
                    <input type="number" id="jumlah_ml" name="jumlah_ml"
                        placeholder="Masukkan jumlah cairan dalam ukuran {{ 'ml / mili liter' }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('jumlah_ml') }}">
                    @error('jumlah_ml')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cara penggunaan / CKEDITOR5 --}}
                <div class="bg-white p-4 rounded-lg mb-4 h-auto">
                    <label for="cara_penggunaan" class="block mb-1 text-sm font-medium text-gray-700">Cara
                        Penggunaan</label>
                    <textarea id="editor" name="cara_penggunaan"
                        class="mt-1   block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </textarea>
                    @error('cara_penggunaan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Link youtube --}}
                <div class="bg-white p-4 rounded-lg mb-4">
                    <label for="link_youtube" class="block text-sm font-medium text-gray-700">Link YouTube</label>
                    <input type="url" id="link_youtube" name="link_youtube"
                        placeholder=" Link diawali dengan http://"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('link_youtube') }}">
                    @error('link_youtube')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 bg-white rounded-lg">
                    <div class="bg-white p-4 rounded-lg mb-4">
                        <label for="tanggal_expired" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Pembelian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input datepicker datepicker-autohide type="text" name="tanggal_pembelian"
                                datepicker-format="yyyy/mm/dd"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date">
                        </div>

                        @error('tanggal_pembelian')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="bg-white p-4 rounded-lg mb-4">
                        <label for="tanggal_expired" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Expired</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input datepicker datepicker-autohide type="text" name="tanggal_expired"
                                datepicker-format="yyyy/mm/dd"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date">
                        </div>
                        <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Jika tidak
                            memiliki tanggal exp, silahkan kosongkan.
                        </p>


                        @error('tanggal_expired')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('image-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.classList.add('hidden');
            }
        }
    </script>

    <script>
        function toggleFields() {
            const jenisPadat = document.getElementById('jenis_padat').checked;
            const jumlahSatuanField = document.getElementById('jumlah_satuan_field');
            const jumlahMlField = document.getElementById('jumlah_ml_field');

            if (jenisPadat) {
                jumlahSatuanField.classList.remove('hidden');
                jumlahMlField.classList.add('hidden');
            } else {
                jumlahSatuanField.classList.add('hidden');
                jumlahMlField.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleFields(); // Initialize the fields based on the default selected radio button
        });

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const imagePreview = document.getElementById('image-preview');
                imagePreview.src = reader.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
