@extends('layouts.app')

@section('title', 'Pengaturan Jaslab')

@section('content')
<div class="px-2">
    <div class="flex flex-col md:flex-row justify-between mb-6">
        <div>
            <h4 class="text-2xl font-bold text-gray-900">Peraturan Jaslab</h4>
            <p class="text-sm text-gray-500 mt-1">
                Menampilkan daftar jabatan beserta warna jaslab yang harus dikenakan sesuai jabatan.
            </p>
        </div>
    </div>

    <x-alert />

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium">No</th>
                        <th scope="col" class="px-6 py-4 font-medium">Jabatan</th>
                        <th scope="col" class="px-6 py-4 font-medium">Warna</th>
                        <th scope="col" class="px-6 py-4 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($jaslab as $index => $jl)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $jl->role }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border"
                                    style="background-color: {{ strtolower($jl->warna) == 'putih' ? '#f9fafb' : (strtolower($jl->warna) == 'biru' ? '#eff6ff' : (strtolower($jl->warna) == 'hijau' ? '#f0fdf4' : '#f3f4f6')) }}; 
                                           border-color: {{ strtolower($jl->warna) == 'putih' ? '#e5e7eb' : (strtolower($jl->warna) == 'biru' ? '#bfdbfe' : (strtolower($jl->warna) == 'hijau' ? '#bbf7d0' : '#e5e7eb')) }};
                                           color: {{ strtolower($jl->warna) == 'putih' ? '#374151' : (strtolower($jl->warna) == 'biru' ? '#1d4ed8' : (strtolower($jl->warna) == 'hijau' ? '#15803d' : '#374151')) }};">
                                    {{ $jl->warna }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button type="button" data-modal-target="update-jaslab-{{ $jl->id }}" data-modal-toggle="update-jaslab-{{ $jl->id }}" class="font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors">Edit</button>
                                
                                {{-- Modal --}}
                                <form action="{{ route('ubahJaslab', $jl->id) }}" id="update-jaslab-{{ $jl->id }}" method="POST" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    @csrf
                                    @method('PUT')
                                    <div class="relative p-4 w-full max-w-lg max-h-full">
                                        <div class="relative bg-white rounded-xl shadow-lg border border-gray-100">
                                            <!-- Modal header -->
                                            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 rounded-t-xl">
                                                <h3 class="text-lg font-semibold text-gray-900 break-words pr-4">
                                                    Ubah Warna Jaslab: {{ $jl->role }}
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors" data-modal-hide="update-jaslab-{{ $jl->id }}">
                                                    <x-atoms.icon name="x-mark" class="w-4 h-4" />
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-5 space-y-4">
                                                <div>
                                                    <label for="warna" class="block mb-2 text-sm font-medium text-gray-700">Warna Jaslab</label>
                                                    <input type="text" id="warna" name="warna" value="{{ $jl->warna }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors" placeholder="Masukkan warna Jaslab..." required />
                                                </div>
                                            </div>
                                            <!-- Modal footer -->
                                            <div class="flex items-center px-5 py-4 border-t border-gray-100 rounded-b-xl bg-gray-50">
                                                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors shadow-sm">Simpan Perubahan</button>
                                                <button data-modal-hide="update-jaslab-{{ $jl->id }}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-700 focus:outline-none bg-white rounded-lg border border-gray-300 hover:bg-gray-50 hover:text-gray-900 focus:z-10 focus:ring-4 focus:ring-gray-100 transition-colors">Batal</button>
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
</div>
@endsection
