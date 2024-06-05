@extends('layouts.app')

@section('title', 'Tambah Jadwal baru')

@section('content')
    @if ($errors->any())
        <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Peringatan</span>
            <div>
                <span class="font-medium">Tolong perhatikan aturan berikut:</span>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>
            </div>
        </div>
    @endif

    <section class="max-w-4xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">Jadwal Baru</h2>

        <form action="{{ route('addJadwal') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div class="">
                    <label for="mata_kuliah" class="block text-sm font-medium leading-6 text-gray-900">Mata kuliah</label>
                    <div class="mt-2">
                        <input type="text" name="mata_kuliah" id="mata_kuliah" autocomplete="family-name"
                            placeholder="Masukkan nama kelas"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="ruangan" class="block text-sm font-medium leading-6 text-gray-900">Ruangan </label>

                    <div class="grid grid-cols-2 gap-4">

                        <div class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
                            <input id="bordered-radio-1" type="radio" value="2" name="ruangan_id"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-1"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruangan
                                1</label>
                        </div>
                        <div class="flex items-center ps-4 border border-gray-200 rounded dark:border-gray-700">
                            <input checked id="bordered-radio-2" type="radio" value="2" name="ruangan_id"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-2"
                                class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ruangan
                                2</label>
                        </div>

                    </div>
                </div>

                <div>
                    <label for="tanggal_jadwal" class="block text-sm font-medium leading-6 text-gray-900">Tanggal</label>
                    <div class="relative  mt-2">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input datepicker datepicker-autohide type="text" name="tanggal_jadwal"
                            placeholder="Pilih tanggal" datepicker-format="yyyy/mm/dd"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Select date">
                    </div>
                </div>

                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dosen
                        pengampu</label>
                    <select id="countries" name="dosen"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected disabled>Pilih nama dosen</option>
                        @foreach ($user as $dosen)
                            <option value="{{ $dosen->name }}">{{ $dosen->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="waktu_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Waktu
                        Mulai:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="time" id="waktu_mulai" name="waktu_mulai"
                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            min="09:00" max="20:00" value="00:00" required />
                    </div>
                </div>

                <div>
                    <label for="waktu_selesai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Waktu
                        Selesai:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="time" id="waktu_selesai" name="waktu_selesai"
                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            min="09:00" max="23:00" value="00:00" required />
                    </div>
                </div>

                <div class="">
                    <label for="kelas" class="block text-sm font-medium leading-6 text-gray-900">Kelas</label>
                    <div class="mt-2">
                        <input type="text" name="kelas" id="kelas" autocomplete="family-name"
                            placeholder="Masukkan nama kelas"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="">
                    <label for="submateri" class="block text-sm font-medium leading-6 text-gray-900">Submateri</label>
                    <div class="mt-2">
                        <input type="text" name="submateri" id="submateri" autocomplete="family-name"
                            placeholder="Masukkan nama submateri"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="">
                    <label for="semester" class="block text-sm font-medium leading-6 text-gray-900">Semester</label>
                    <div class="mt-2">
                        <input type="text" name="semester" id="semester" autocomplete="family-name"
                            placeholder="Masukkan nama semester"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-8 py-2.5 leading-5 text-white transition-colors duration-300 transform bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Save</button>
            </div>
        </form>
    </section>

@endsection
