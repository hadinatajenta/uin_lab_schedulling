@extends('layouts.app')

@section('title', 'Tambah Alat / Bahan')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-zinc-500 mb-2">
        <a href="{{ route('alat') }}" class="hover:text-[rgb(var(--color-primary))] transition-colors">Alat & Bahan</a>
        <span class="material-symbols-rounded text-[16px]">chevron_right</span>
        <span class="font-semibold text-zinc-900">Tambah Data Baru</span>
    </div>
    <h4 class="text-2xl font-bold text-zinc-900">Tambah Alat / Bahan</h4>
    <p class="text-sm font-medium text-zinc-500 mt-1">
        Masukkan informasi detail mengenai alat atau bahan baru yang masuk ke laboratorium.
    </p>
</div>

<x-alert />

<form action="{{ route('add.alat') }}" method="POST" enctype="multipart/form-data" 
      x-data="{ kategori: '{{ old('jenis_alat', 'Alat') }}' }" 
      class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    @csrf

    @include('equipments.partials.form', ['alat' => new \App\Models\Alat(), 'mode' => 'create'])

    </div>
</form>

@endsection