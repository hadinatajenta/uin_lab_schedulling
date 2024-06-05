@extends('layouts.app')

@section('title', 'Tentang LAB')

@section('content')
    {{-- SOP --}}
    <div class="container mx-auto my-8">
        <h2 class="text-2xl font-bold mb-4">SOP</h2>
        <article class="ckeditor-content""> {!! $aboutLab->sop !!} </article>
    </div>

    {{-- Struktur organisasi --}}
    <div class="container mx-auto my-8">
        <h2 class="text-2xl font-bold mb-4">Struktur Organisasi</h2>
        <div class="flex justify-center">
            <img src="{{ asset('storage/' . $aboutLab->stuktur) }}" alt="Struktur Organisasi" class="max-w-full h-auto">
        </div>
    </div>

    <div class="container mx-auto my-8">
        <div class="flex justify-end">
            <a href="{{ route('editInfoLab') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Edit
            </a>
        </div>
    </div>
@endsection
