@extends('layouts.app')

@section('title', 'Limbah')
@section('content')
    <div class="flex flex-row md:flex-row items-center justify-between mb-4">
        <div class="flex-grow md:mr-4">
            <h4 class="text-2xl font-bold wedustext-white">Limbah</h4>
            <p class="text-sm font-normal text-gray-500 lg:text-sm wedustext-gray-400">
                Disini kamu bisa melihat semua informasi mengenai Limbah beserta cara pengelolaan pasca pakai.
            </p>
        </div>
    </div>

    {{-- Search func --}}
    <div class="flex items-center mb-4">
        <form class="w-full mx-auto">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only wedustext-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 wedustext-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="search" name="cari"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 wedusbg-gray-700 wedusborder-gray-600 wedusplaceholder-gray-400 wedustext-white wedusfocus:ring-blue-500 wedusfocus:border-blue-500"
                    placeholder="Cari Limbah ..." />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <x-alert />

    <div class="flex justify-between items-center mb-2">
        <div>
            <h2 class="text-xl font-bold">Daftar Limbah</h2>
            <h5 class="text-sm font-thin">Menampilkan {{ $limbah->count() }} daftar alat & bahan</h5>
        </div>
        <a href="{{ route('tambahLimbah') }}"
            class="text-white bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 wedusbg-blue-600 wedushover:bg-blue-700 focus:outline-none wedusfocus:ring-blue-800">
            <span class="block md:hidden">+</span>
            <span class="hidden md:block">Tambah Limbah</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        @foreach ($limbah as $lbh)
            <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow wedusbg-gray-800 wedusborder-gray-700">

                <div class="p-5">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 wedustext-white">
                            {{ $lbh->nama_limbah }}</h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 wedustext-gray-400">
                        {{ strlen($lbh->cara_pengolahan) > 100 ? substr($lbh->cara_pengolahan, 0, 50) . '...' : $lbh->cara_pengolahan }}
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ route('detailLimbah', $lbh->id) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-gray-800 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-800 wedusbg-gray-800 wedushover:bg-gray-800 wedusfocus:ring-gray-800">
                            Read more

                        </a>

                        <form action="{{ route('hapusLimbah', $lbh->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 wedusbg-red-600 wedushover:bg-red-700 focus:outline-none wedusfocus:ring-red-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>

                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endsection
