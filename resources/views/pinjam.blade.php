@extends('layouts.app')

@section('title', 'Pinjam alat')

@section('content')
    <div class="grid grid-cols-12 gap-3 text-gray-900">
        <form class="col-span-8 bg-white w-full rounded-lg p-6 mx-auto"
            action="{{ route('ajukanPeminjaman', ['id' => $pinjam->id]) }}" method="POST">
            @csrf
            <div class="relative z-0 w-full mb-5 group text-gray-800">
                <input type="nama_alat" name="floating_nama_alat" id="floating_nama_alat" value="{{ $pinjam->nama_alat }}"
                    disabled
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none  wedusborder-gray-600 wedusfocus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " required />
                <label for="floating_nama_alat"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 wedustext-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:wedustext-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    Nama Alat</label>
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 w-full mb-5 group">
                    <input type="number" name="jumlah_dipinjam" id="floating_first_name"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none  wedusborder-gray-600 wedusfocus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required />
                    <label for="floating_first_name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 wedustext-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:wedustext-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Jumlah
                        yang dipinjam / dipakai</label>
                    <input type="hidden" name="alat_id" value="{{ $pinjam->id }}">

                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 wedustext-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input datepicker id="default-datepicker" type="text" name="tanggal_peminjaman"
                            datepicker-format="yyyy-mm-dd"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 wedusbg-gray-700 wedusborder-gray-600 wedusplaceholder-gray-400 wedustext-white wedusfocus:ring-blue-500 wedusfocus:border-blue-500"
                            placeholder="Tanggal">
                    </div>
                </div>
            </div>

            <button type="submit"
                class="text-white mb-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">Submit</button>
            <x-alert />
        </form>

        <div class="col-span-4 flex w-full items-center justify-center bg-white rounded-lg">
            <img src="{{ asset('storage/' . $pinjam->gambar ?? '') }}" alt="">
        </div>
    </div>
@endsection
