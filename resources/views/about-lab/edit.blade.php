@extends('layouts.app')

@section('title', 'Edit Tentang LAB')

@section('content')
    <div class="container mx-auto my-8">
        <h2 class="text-2xl font-bold mb-4">Edit Informasi LAB</h2>

        {{-- Display success or error messages --}}
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('editInfo') }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            {{-- SOP --}}
            <div class="mb-4">
                <label for="sop" class="block text-gray-700 text-sm font-bold mb-2">SOP:</label>
                <textarea name="sop" id="editor" class="ss">{{ $aboutlab->sop }}</textarea>
            </div>

            {{-- Struktur Organisasi --}}
            <div class="mb-4">
                <label for="stuktur" class="block text-gray-700 text-sm font-bold mb-2">stuktur Organisasi:</label>
                <input type="file" name="stuktur" id="stuktur"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @if ($aboutlab->stuktur)
                    <div class="mt-4">
                        <p>Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $aboutlab->stuktur) }}" alt="Struktur Organisasi"
                            class="max-w-xs h-auto mt-2">
                    </div>
                @endif
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
