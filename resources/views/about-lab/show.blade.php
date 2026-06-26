@extends('layouts.app')

@section('title', 'About Lab')

@section('content')
<div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6">
    {{-- Header --}}
    <x-ui.page-header title="About Lab" description="Laboratory operational standards and organizational structure information.">
        @if (Auth::user() && Auth::user()->jabatan !== 'Mahasiswa')
            <a href="{{ route('editInfoLab') }}"
                class="w-full md:w-auto inline-flex items-center justify-center rounded-xl ui-primary px-4 h-11 md:h-10 font-semibold text-sm md:text-xs shadow-sm shadow-[rgb(var(--color-primary))_/_0.1] hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2">
                <span class="material-symbols-rounded text-[18px] mr-2">edit</span>
                Edit Information
            </a>
        @endif
    </x-ui.page-header>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        
        {{-- SOP Section --}}
        <div class="xl:col-span-7 space-y-6">
            <div class="ui-surface p-6 md:p-8 rounded-2xl shadow-sm border border-[rgb(var(--color-border))]">
                <div class="flex items-center space-x-3 mb-6 pb-6 border-b border-[rgb(var(--color-border))]">
                    <div class="w-11 h-11 rounded-xl bg-[rgb(var(--color-primary)_/_0.1)] text-[rgb(var(--color-primary))] flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded">gavel</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Standard Operating Procedures</h2>
                        <p class="text-sm ui-text-muted mt-0.5">Rules and guidelines for laboratory usage.</p>
                    </div>
                </div>
                
                <div class="prose prose-sm md:prose-base prose-blue max-w-none text-gray-600 leading-relaxed break-words">
                    {!! !empty($aboutLab->sop) ? $aboutLab->sop : '<p class="italic text-gray-400">No SOP information available.</p>' !!}
                </div>
            </div>
        </div>

        {{-- Structure Section --}}
        <div class="xl:col-span-5 space-y-6">
            <div class="ui-surface p-6 md:p-8 rounded-2xl shadow-sm border border-[rgb(var(--color-border))]">
                <div class="flex items-center space-x-3 mb-6 pb-6 border-b border-[rgb(var(--color-border))]">
                    <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded">account_tree</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Organizational Structure</h2>
                        <p class="text-sm ui-text-muted mt-0.5">Laboratory management hierarchy.</p>
                    </div>
                </div>

                @if(!empty($aboutLab) && !empty($aboutLab->stuktur))
                    <div class="rounded-xl overflow-hidden border border-[rgb(var(--color-border))] bg-gray-50 flex items-center justify-center p-4">
                        <img src="{{ asset('storage/' . $aboutLab->stuktur) }}" alt="Organizational Structure" class="max-w-full h-auto object-contain rounded-lg hover:scale-[1.02] transition-transform duration-300">
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center p-8 rounded-xl border border-dashed border-gray-300 bg-gray-50">
                        <span class="material-symbols-rounded text-gray-400 text-4xl mb-2">image_not_supported</span>
                        <p class="text-sm text-gray-500 font-medium">No structure image uploaded</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection