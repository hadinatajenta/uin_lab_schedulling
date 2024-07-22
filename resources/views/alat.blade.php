@extends('layouts.app')

@section('title', 'Alat dan bahan')

@section('content')
    <div class="flex flex-row md:flex-row items-center justify-between mb-4">
        <div class="flex-grow md:mr-4">
            <!-- Tambahkan class flex-grow dan margin kanan untuk fleksibilitas di layar kecil -->
            <h4 class="text-2xl font-bold wedustext-white">Alat & bahan</h4>
            <p class="text-sm font-normal text-gray-500 lg:text-sm wedustext-gray-400">
                Kamu bisa mengelola alat yang ada di ruangan Lab disini.
            </p>
        </div>
    </div>

    <div class="w-full overflow-x-auto mb-6">
        <div class="grid grid-cols-3 gap-4 min-w-max">
            {{-- Daftar alat --}}
            <form action="">
                <input type="hidden" name="jenis_alat" value="Alat">
                <button type="submit"
                    class="flex items-center p-6 border w-full border-gray-200 bg-white rounded shadow hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>

                    <div class="ms-4 flex flex-col">
                        <h5 class="text-xl font-bold">Daftar Alat</h5>
                        <span class="text-start">{{ $bahanPadat->count() }}</span>
                    </div>
                </button>
            </form>

            {{-- Daftar bahan --}}
            <form action="">
                <input type="hidden" name="jenis_alat" value="Bahan">
                <button type="submit"
                    class="flex items-center w-full p-6 border border-gray-200 bg-white rounded shadow hover:shadow-md hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                    </svg>

                    <div class="ms-4 flex flex-col">
                        <h5 class="text-xl font-bold">Daftar Bahan</h5>
                        <span class="text-start">{{ $bahanCair->count() }}</span>
                    </div>
                </button>
            </form>

            {{-- Total alat dan bahan --}}
            <div class="flex items-center p-6 border border-gray-200 bg-white rounded shadow">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>

                <div class="ms-4 flex flex-col">
                    <h5 class="text-xl font-bold">Total Alat & Bahan</h5>
                    <span>{{ $all }}</span>
                </div>
            </div>
        </div>
    </div>


    {{-- search --}}
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
                <input type="search" id="search" name="cari"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 wedusbg-gray-700 wedusborder-gray-600 wedusplaceholder-gray-400 wedustext-white wedusfocus:ring-blue-500 wedusfocus:border-blue-500"
                    placeholder="Cari nama alat / bahan ..." />
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
            <h2 class="text-xl font-bold">Daftar Alat & Bahan</h2>
            <h5 class="text-sm font-thin">Menampilkan {{ $alat->count() }} daftar alat & bahan</h5>
        </div>
        <a href="{{ route('add.alat') }}"
            class="text-white bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 wedusbg-blue-600 wedushover:bg-blue-700 focus:outline-none wedusfocus:ring-blue-800">
            <span class="block md:hidden">+</span>
            <span class="hidden md:block">Tambah alat</span>
        </a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @if ($alat->count() > 0)
            @foreach ($alat as $item)
                <div id="alat-table"
                    class="alat-item max-w-sm bg-white border border-gray-200 rounded-lg shadow wedusbg-gray-800 wedusborder-gray-700">
                    <a href="#" data-modal-target="item-{{ $item->id }}"
                        data-modal-toggle="item-{{ $item->id }}">
                        <img class="rounded-2xl h-36 w-full p-1" src="{{ asset('storage/' . $item->gambar) }}"
                            alt="{{ $item->nama_alat }}" />
                    </a>
                    <div class="p-5">
                        <a href="#" class="mb-0">
                            <h5 class=" text-xl font-bold tracking-tight text-gray-900 wedustext-white">
                                {{ $item->nama_alat ?? '-' }}
                            </h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 wedustext-gray-400">
                            {{ strlen($item->deskripsi) > 100 ? substr($item->deskripsi, 0, 50) . '...' : $item->deskripsi }}
                        </p>
                    </div>
                </div>

                {{-- Modal --}}
                <div id="item-{{ $item->id }}" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow wedusbg-gray-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t wedusborder-gray-600">
                                {{-- title --}}
                                <h3 class="text-xl font-semibold text-gray-900 wedustext-white">
                                    {{ $item->nama_alat ?? '' }}
                                </h3>
                                {{-- close modal --}}
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center wedushover:bg-gray-600 wedushover:text-white"
                                    data-modal-hide="item-{{ $item->id }}">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-4 md:p-5 space-y-4">
                                {{-- gambar --}}
                                <figure class="max-w-lg">
                                    <img class="h-auto max-w-full mx-auto rounded-lg"
                                        src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_alat }}">
                                    <figcaption class="mt-2 text-sm text-center text-gray-500 wedustext-gray-400">
                                        {{ $item->nama_alat }}
                                    </figcaption>
                                </figure>

                                {{-- table --}}
                                <div class="relative overflow-x-auto">
                                    <table
                                        class="w-full text-sm text-left rtl:text-right text-gray-500 wedustext-gray-400">
                                        <tbody>
                                            <tr class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium bg-gray-50 text-gray-900 whitespace-nowrap wedustext-white">
                                                    Nama alat
                                                </th>
                                                <td>:</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $item->nama_alat ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium bg-gray-50 text-gray-900 whitespace-nowrap wedustext-white">
                                                    Deksripsi alat
                                                </th>
                                                <td>
                                                    :
                                                </td>
                                                <td class="px-6 py-4">
                                                    {{ $item->deskripsi ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium bg-gray-50 text-gray-900 whitespace-nowrap wedustext-white">
                                                    @if ($item->jenis_alat == 'Alat')
                                                        <span>Jumlah alat</span>
                                                    @else
                                                        <span>Ukuran</span>
                                                    @endif
                                                </th>
                                                <td>
                                                    :
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if ($item->jenis_alat == 'Alat')
                                                        {{ $item->jumlah_satuan ?? '-' }} unit
                                                    @else
                                                        {{ $item->jumlah_ml ?? '-' }} ml
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium bg-gray-50 text-gray-900 whitespace-nowrap wedustext-white">
                                                    Kondisi alat
                                                </th>
                                                <td>
                                                    :
                                                </td>
                                                <td class="px-6 py-4 ">
                                                    <span
                                                        class="p-2 @if ($item->kondisi == 'Baru') bg-blue-500 text-white rounded-full @endif">{{ $item->kondisi ?? '' }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b wedusborder-gray-600">
                                <button onclick="window.location.href='{{ route('detailAlat', $item->id) }}'"
                                    type="button"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">
                                    Baca lengkap
                                </button>

                                <button data-modal-hide="item-{{ $item->id }}" type="button"
                                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 wedusfocus:ring-gray-700 wedusbg-gray-800 wedustext-gray-400 wedusborder-gray-600 wedushover:text-white wedushover:bg-gray-700">Tutup</button>

                                <form action="{{ route('hapus.alat', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm py-2.5 px-5 mx-2 wedusbg-gray-800 wedustext-white wedusborder-gray-600 wedushover:bg-gray-700 wedushover:border-gray-600 wedusfocus:ring-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>

                                <button data-modal-hide="item-{{ $item->id }}" type="button"
                                    onclick="window.location.href='{{ route('editAlat', $item->id) }}'"
                                    class="py-2.5 px-5  text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 wedusfocus:ring-gray-700 wedusbg-gray-800 wedustext-gray-400 wedusborder-gray-600 wedushover:text-white wedushover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-12 text-center mt-7">
                <span
                    class="py-4 px-2 col-span-6 bg-blue-100 text-blue-800 text-sm font-medium me-2 rounded wedusbg-blue-900 wedustext-blue-300">Tidak
                    ada list yang ditampilkan, silahkana tambahkan alat / bahan terlebih dahulu.</span>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var items = document.querySelectorAll('.alat-item');

            items.forEach(function(item) {
                var itemName = item.querySelector('h5').textContent.toLowerCase();
                var itemDescription = item.querySelector('p').textContent.toLowerCase();

                if (itemName.includes(searchValue) || itemDescription.includes(searchValue)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
