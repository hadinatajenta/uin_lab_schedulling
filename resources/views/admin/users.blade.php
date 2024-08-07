@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class=" ">
        {{-- First section --}}
        <div class="flex flex-col md:flex-row justify-start lg:justify-between mb-4  ">
            <div>
                <h4 class="text-2xl font-bold wedustext-white">Pengguna</h4>
                <p class="text-sm font-normal text-gray-500 lg:text-sm wedustext-gray-400">
                    Menampilkan semua daftar pengguna.
                </p>
            </div>
            <div class="flex self-start p-2">
                @if (Auth::user()->jabatan !== 'Mahasiswa')
                    <button type="button" data-modal-target="add-modal" data-modal-toggle="add-modal"
                        class="text-white flex items-center btn-sm bg-[#152F8B] hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-5 py-2.5 me-2 mb-2 wedusbg-gray-800 wedushover:bg-gray-700 wedusfocus:ring-gray-700 wedusborder-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        <span class="ms-3">Tambah Pengguna</span>
                    </button>
                @endif
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-center md:items-start justify-start md:justify-between mb-4">
            {{-- Tambah pengguna --}}
            <div id="add-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <form method="POST" action="{{ route('add.users') }}" class="relative p-4 w-full max-w-2xl max-h-full">
                    @csrf
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow wedusbg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t wedusborder-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 wedustext-white">
                                Tambah pengguna
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center wedushover:bg-gray-600 wedushover:text-white"
                                data-modal-hide="add-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <div class="relative">
                                <input type="text" id="floating_outlined" name="name"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none wedustext-white wedusborder-gray-600 wedusfocus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="floating_outlined"
                                    class="absolute text-sm text-gray-500 wedustext-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white wedusbg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:wedustext-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                    Nama
                                </label>
                            </div>
                            <div class="relative">
                                <input type="email" id="floating_outlined" name="email"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none wedustext-white wedusborder-gray-600 wedusfocus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="floating_outlined"
                                    class="absolute text-sm text-gray-500 wedustext-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white wedusbg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:wedustext-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                    Email
                                </label>
                            </div>
                            <div class="relative">
                                <input type="password" id="floating_outlined" name="password"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none wedustext-white wedusborder-gray-600 wedusfocus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="floating_outlined"
                                    class="absolute text-sm text-gray-500 wedustext-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white wedusbg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:wedustext-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Password</label>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b wedusborder-gray-600">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">Tambah</button>
                            <button data-modal-hide="add-modal" type="button"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 wedusfocus:ring-gray-700 wedusbg-gray-800 wedustext-gray-400 wedusborder-gray-600 wedushover:text-white wedushover:bg-gray-700">Batalkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3  gap-4 my-4 text-gray">
            <div class="flex items-center h-24 p-4 rounded-lg bg-[#00B8FF] wedusbg-gray-800 space-x-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div class="flex flex-col text-gray">
                    <p class="text-lg font-semibold">Dosen</p>
                    <span class="text-sm">{{ $dosen->count() }}</span>
                </div>
            </div>
            <div class="flex items-center h-24 p-4 rounded-lg bg-[#65FBD2] wedusbg-gray-800 space-x-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div class="flex flex-col text-gray">
                    <p class="text-lg font-semibold">Admin Lab</p>
                    <span class="text-sm">{{ $admin->count() }}</span>
                </div>
            </div>
            <div class="flex items-center h-24 p-4 rounded-lg bg-[#00DEFB] space-x-4 col-span-2 md:col-span-1">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div class="flex flex-col text-gray">
                    <p class="text-lg font-semibold">Mahasiswa</p>
                    <span class="text-sm">{{ $mahasiswa->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Search section --}}
        <div class="flex items-center mb-4">
            <form class="w-full mx-auto">
                <label for="default-search"
                    class="mb-2 text-sm font-medium text-gray-900 sr-only wedustext-white">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 wedustext-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search" name="keyword"
                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 wedusbg-gray-700 wedusborder-gray-600 wedusplaceholder-gray-400 wedustext-white wedusfocus:ring-blue-500 wedusfocus:border-blue-500"
                        placeholder="Cari Nama, atau E-mail..." />
                    <button type="submit"
                        class="text-white absolute end-2.5 bottom-2.5 bg-[#6467CD] hover:bg-[#5257d6] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- Alert --}}
        <x-alert />

        <div class="flex justify-between items-center mb-2">
            <div>
                <h2 class="text-xl font-bold">Daftar Pengguna</h2>
                <h5 class="text-sm font-thin">Menampilkan {{ $users->count() }} Pengguna</h5>
            </div>
        </div>
        {{-- Data section --}}
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class=" text-sm w-full text-left rtl:text-right text-gray-500 wedustext-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 wedusbg-gray-700 wedustext-gray-400">
                    <tr>
                        <th scope="col" class="p-4">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jabatan
                        </th>

                        @if (Auth::user()->jabatan !== 'Mahasiswa')
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr
                            class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700 hover:bg-gray-50 wedushover:bg-gray-600">
                            <td class="w-4 p-4">
                                {{ $index + 1 }}
                            </td>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap wedustext-white">
                                {{ $user->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ Str::ucfirst($user->jabatan ?? '-') }}
                            </td>
                            <td class="flex items-center px-6 py-4">
                                @if (Auth::user()->jabatan !== 'Mahasiswa')
                                    <x-edit-modal id="{{ $user->id }}" class="me-4">
                                        <form method="POST" action="{{ route('update.users', $user->id) }}"
                                            class="relative p-4 w-full max-w-2xl max-h-full">
                                            @csrf
                                            @method('PUT')
                                            <!-- Modal content -->
                                            <div class="relative bg-white rounded-lg shadow wedusbg-gray-700">
                                                <!-- Modal header -->
                                                <div
                                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t wedusborder-gray-600">
                                                    <h3 class="text-xl font-semibold text-gray-900 wedustext-white">
                                                        Update data - {{ $user->name }}
                                                    </h3>
                                                    <button type="button"
                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center wedushover:bg-gray-600 wedushover:text-white"
                                                        data-modal-hide="edit-modal-{{ $user->id }}">
                                                        <svg class="w-3 h-3" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <!-- Modal body -->
                                                <div class="p-4 md:p-5 space-y-4">
                                                    <div class="relative">
                                                        <input type="text" id="floating_outlined" name="name"
                                                            required
                                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none wedustext-white wedusborder-gray-600 wedusfocus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                            placeholder="Nama pengguna" value="{{ $user->name }}" />
                                                        <label for="floating_outlined"
                                                            class="absolute text-sm text-gray-500 wedustext-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white wedusbg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:wedustext-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Masukkan
                                                            nama <span class="text-red-900">*</span> </label>
                                                    </div>

                                                    <div class="relative">
                                                        <label for="countries"
                                                            class="block mb-2 text-sm font-medium text-gray-900 wedustext-white">Ubah
                                                            jabatan</label>
                                                        <select id="jabatan" name="jabatan"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 wedusbg-gray-700 wedusborder-gray-600 wedusplaceholder-gray-400 wedustext-white wedusfocus:ring-blue-500 wedusfocus:border-blue-500">
                                                            <option selected disabled>Pilih jabatan</option>
                                                            <option value="dosen"
                                                                @if ($user->jabatan == 'dosen') selected @endif>Dosen
                                                            </option>
                                                            <option value="admin lab"
                                                                @if ($user->jabatan == 'admin lab') selected @endif>Admin Lab
                                                            </option>
                                                            <option value="asisten dosen"
                                                                @if ($user->jabatan == 'asisten dosen') selected @endif>Asisten
                                                                Dosen
                                                            </option>
                                                            <option value="Mahasiswa"
                                                                @if ($user->jabatan == 'Mahasiswa') selected @endif>Mahasiswa
                                                            </option>

                                                        </select>
                                                    </div>

                                                </div>
                                                <!-- Modal footer -->
                                                <div
                                                    class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b wedusborder-gray-600">
                                                    <button type="submit"
                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">
                                                        Update</button>
                                                    <button data-modal-hide="edit-modal-{{ $user->id }}"
                                                        type="button"
                                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 wedusfocus:ring-gray-700 wedusbg-gray-800 wedustext-gray-400 wedusborder-gray-600 wedushover:text-white wedushover:bg-gray-700">Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                    </x-edit-modal>
                                    <x-pop-up action="{{ route('delete.users', $user->id) }}" id="{{ $user->id }}"
                                        buttonName="Hapus">Apa kamu yakin ingin menghapus {{ $user->name ?? '- ' }} dari
                                        sistem?
                                    </x-pop-up>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mb-2">
            <div>
                <h2 class="text-xl font-bold">Peraturan Jaslab</h2>
                <h5 class="text-sm font-thin">Menampilkan Daftar Jabatan beserta warna jaslab yang harus dikenakan sesuai
                    jabatan.</h5>
            </div>
        </div>
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg mb-4">
            <table class=" text-sm w-full text-left rtl:text-right text-gray-500 wedustext-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 wedusbg-gray-700 wedustext-gray-400">
                    <tr>
                        <th scope="col" class="p-4">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jabatan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Warna
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jaslab as $index => $jl)
                        <tr
                            class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700 hover:bg-gray-50 wedushover:bg-gray-600">
                            <td class="w-4 p-4">
                                {{ $index + 1 }}
                            </td>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap wedustext-white">
                                {{ $jl->role }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $jl->warna }}
                            </td>
                            <td class="flex items-center px-6 py-4">
                                <a href="#" data-modal-target="update-jaslab-{{ $jl->id }}"
                                    data-modal-toggle="update-jaslab-{{ $jl->id }}"
                                    class="font-medium text-blue-600 wedustext-blue-500 hover:underline">Edit</a>
                                {{-- Modal --}}
                                <form action="{{ route('ubahJaslab', $jl->id) }}" id="update-jaslab-{{ $jl->id }}"
                                    method="POST" tabindex="-1" aria-hidden="true"
                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    @csrf
                                    @method('PUT')
                                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                                        <div class="relative bg-white rounded-lg shadow wedusbg-gray-700">
                                            <!-- Modal header -->
                                            <div
                                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t wedusborder-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 wedustext-white">
                                                    Ubah Warna Jaslab untuk user Role {{ $jl->role }}
                                                </h3>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center wedushover:bg-gray-600 wedushover:text-white"
                                                    data-modal-hide="update-jaslab-{{ $jl->id }}">
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-4 md:p-5 space-y-4">
                                                <div class="mb-5">
                                                    <label for="warna"
                                                        class="block mb-2 text-sm font-medium text-gray-900 wedustext-white">
                                                        warna</label>
                                                    <input type="text" id="warna" name="warna"
                                                        value="{{ $jl->warna }}"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 wedusbg-gray-700 wedusborder-gray-600 wedusplaceholder-gray-400 wedustext-white wedusfocus:ring-blue-500 wedusfocus:border-blue-500"
                                                        placeholder="Masukkan warna Jaslab..." required />
                                                </div>
                                            </div>
                                            <!-- Modal footer -->
                                            <div
                                                class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b wedusborder-gray-600">
                                                <button type="submit"
                                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">
                                                    Perbarui</button>
                                                <button data-modal-hide="update-jaslab-{{ $jl->id }}"
                                                    type="button"
                                                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 wedusfocus:ring-gray-700 wedusbg-gray-800 wedustext-gray-400 wedusborder-gray-600 wedushover:text-white wedushover:bg-gray-700">batal</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection
