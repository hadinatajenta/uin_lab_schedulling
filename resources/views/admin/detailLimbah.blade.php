@extends('layouts.app')

@section('title', 'Detail limbah')

@section('content')
    <main class="container mx-auto mt-8">
        <article class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold mb-4">Limbah : {{ $findLimbah->nama_limbah }} </h2>
            <p class="text-gray-600 mb-2"> Published on <span class="font-semibold">
                    @php
                        use Carbon\Carbon;
                    @endphp
                    {{ Carbon::parse($findLimbah->created_at)->format('d F Y') }}
                </span>
            </p>


            <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Daftar isi:</h2>
            <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400 mb-6">
                <li>
                    Cara penggunaan
                </li>
                <li>
                    Materi
                </li>
                <li>
                    Cara pengolahan
                </li>
            </ul>


            <div class="prose max-w-none">
                <h3 class="text-2xl font-semibold mb-2"> Cara penggunaan </h3>

                <p class="mb-4"> {{ $findLimbah->cara_penggunaan }} </p>
                <h3 class="text-2xl font-semibold mb-2">Materi</h3>
                <p class="mb-4">{!! $findLimbah->materi !!}</p>

            </div>
        </article>
    </main>
@endsection
