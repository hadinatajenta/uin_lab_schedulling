@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="flex flex-col md:flex-row items-center justify-start lg:justify-between mb-4  ">
        <div>
            <h4 class="text-2xl font-bold wedustext-white">Laporan Semua Alat</h4>
            <p class="text-sm font-normal text-gray-500 lg:text-sm wedustext-gray-400">
                Lihat semua laporan mengenai alat/bahan , pengguna website, dan jadwal pada halaman ini.
            </p>
        </div>
    </div>

    <div class="overflow-x-auto w-full">
        <div class="grid grid-cols-4 gap-4 mb-4 text-white min-w-max">
            <div class="flex items-center h-24 p-4 rounded bg-gray-800 wedusbg-gray-800 space-x-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>

                </div>
                <div class="flex flex-col text-white">
                    <p class="text-lg font-semibold">Jumlah pengguna</p>
                    <span class="text-sm">{{ $user->count() }}</span>
                </div>
            </div>

            <div class="flex items-center h-24 p-4 rounded bg-gray-800 wedusbg-gray-800 space-x-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>

                </div>
                <div class="flex flex-col text-white">
                    <p class="text-lg font-semibold">Alat/bahan</p>
                    <span class="text-sm">{{ $alat->count() }}</span>
                </div>
            </div>

            <div class="flex items-center h-24 p-4 rounded bg-gray-800 wedusbg-gray-800 space-x-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>

                </div>
                <div class="flex flex-col text-white">
                    <p class="text-lg font-semibold">Jumlah jadwal</p>
                    <span class="text-sm">{{ $jadwals->count() }}</span>
                </div>
            </div>

            <div class="flex items-center h-24 p-4 rounded bg-gray-800 wedusbg-gray-800 space-x-4">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="flex flex-col text-white">
                    <p class="text-lg font-semibold">Barang rusak / habis </p>
                    <span class="text-sm">{{ $alat->count() }}</span>
                </div>
            </div>
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
                        Gambar
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama alat
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Kondisi
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Tanggal Pembelian
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tanggal exp
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alat as $index => $item)
                    <tr
                        class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700 hover:bg-gray-50 wedushover:bg-gray-600">
                        <td class="w-4 p-4">
                            {{ $index + 1 }}
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap wedustext-white">
                            <img class="w-12" src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_alat }}">
                        </th>
                        <td class="px-6 py-4">
                            {{ $item->nama_alat }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($item->kondisi == 'Baru')
                                <div class="p-2 text-center  text-sm text-blue-800 rounded-lg bg-blue-50 wedusbg-gray-800 wedustext-blue-400"
                                    role="alert">
                                    <span>{{ $item->kondisi }}</span>
                                </div>
                            @elseif ($item->kondisi == 'Rusak' || $item->kondisi == 'Habis')
                                <div class="p-2 text-center text-sm text-red-800 rounded-lg bg-red-50 wedusbg-gray-800 wedustext-red-400"
                                    role="alert">
                                    <span class="font-medium">{{ $item->kondisi }}</span>
                                </div>
                            @else
                                tidak ada
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->tanggal_pembelian ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->tanggal_expired ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <h4 class="text-2xl font-bold wedustext-white">Laporan Barang Rusak / Habis</h4>
        <p class="text-sm font-normal text-gray-500 lg:text-sm wedustext-gray-400">
            Lihat semua laporan mengenai alat/bahan , pengguna website, dan jadwal pada halaman ini.
        </p>
    </div>

    <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <table class=" text-sm w-full text-left rtl:text-right text-gray-500 wedustext-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 wedusbg-gray-700 wedustext-gray-400">
                <tr>
                    <th scope="col" class="p-4">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Gambar
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama alat
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Kondisi
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Tanggal Pembelian
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tanggal exp
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alatRusakHabis as $index => $item)
                    <tr
                        class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700 hover:bg-gray-50 wedushover:bg-gray-600">
                        <td class="w-4 p-4">
                            {{ $index + 1 }}
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap wedustext-white">
                            <img class="w-12" src="{{ asset('storage/' . $item->gambar) }}"
                                alt="{{ $item->nama_alat }}">
                        </th>
                        <td class="px-6 py-4">
                            {{ $item->nama_alat }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="p-2 text-center text-sm text-red-800 rounded-lg bg-red-50 wedusbg-gray-800 wedustext-red-400"
                                role="alert">
                                <span class="font-medium">{{ $item->kondisi }} </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->tanggal_pembelian ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->tanggal_expired ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
