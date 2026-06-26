@extends('layouts.app')

@section('title', 'Edit Alat / Bahan')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-zinc-500 mb-2">
        <a href="{{ route('alat') }}" class="hover:text-[rgb(var(--color-primary))] transition-colors">Alat & Bahan</a>
        <span class="material-symbols-rounded text-[16px]">chevron_right</span>
        <span class="font-semibold text-zinc-900">Edit Data</span>
    </div>
    <h4 class="text-2xl font-bold text-zinc-900">Edit Alat / Bahan</h4>
    <p class="text-sm font-medium text-zinc-500 mt-1">
        Perbarui informasi detail mengenai alat atau bahan di laboratorium.
    </p>
</div>

<x-alert />

<form action="{{ route('perbarui', $edit->id) }}" method="POST" enctype="multipart/form-data" 
      x-data="{ kategori: '{{ old('jenis_alat', $edit->jenis_alat ?? 'Alat') }}' }" 
      class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    @csrf
    @method('PUT')

    @include('equipments.partials.form', ['alat' => $edit, 'mode' => 'edit'])

</form>

@endsection
