@extends('layouts.app')

@section('title', 'Penjadwalan Lab')

@section('content')
    <div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6" x-data="{ 
        selectedName: '', 
        deleteUrl: '',
        keyword: '{{ request('keyword', '') }}',
        dateFilter: '{{ request('date', '') }}',
        statusFilter: '{{ request('status', '') }}',
        isLoading: false,
        showHelper: false,
        async submitForm() {
            if (this.keyword.length > 0 && this.keyword.length < 3) {
                this.showHelper = true;
                return;
            }

            this.showHelper = false;
            this.isLoading = true;

            let form = this.$refs.filterForm;
            let formData = new FormData(form);
            let queryString = new URLSearchParams(formData).toString();

            try {
                let response = await fetch(`{{ route('lab') }}?${queryString}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    let html = await response.text();
                    document.getElementById('table-container').innerHTML = html;
                    window.history.pushState({}, '', `?${queryString}`);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.isLoading = false;
            }
        },
        removeFilter(name) {
            let input = this.$refs.filterForm.elements[name];
            if(input) {
                input.value = '';
                if(name === 'keyword') this.keyword = '';
                if(name === 'date') this.dateFilter = '';
                if(name === 'status') this.statusFilter = '';
                this.submitForm();
            }
        }
    }">
        <x-ui.page-header title="Schedules" description="Manage laboratory room usage schedules.">
            @if (Auth::user()->jabatan !== 'Mahasiswa')
                <a href="{{ route('addJadwalView') }}"
                    class="w-full md:w-auto inline-flex items-center justify-center rounded-xl ui-primary px-4 h-11 md:h-10 font-semibold text-sm md:text-xs shadow-sm shadow-[rgb(var(--color-primary))_/_0.1] hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2">
                    <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
                    Tambah Jadwal
                </a>
            @endif
        </x-ui.page-header>

        {{-- Dashboard Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
            <div class="ui-surface border border-zinc-200/80 rounded-3xl p-5 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Jadwal Hari Ini</p>
                        <h3 class="text-3xl font-black text-zinc-900 tracking-tight tabular-nums">{{ $totalToday ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 shadow-inner group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-[24px]">calendar_month</span>
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
            </div>

            <div class="ui-surface border border-zinc-200/80 rounded-3xl p-5 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-zinc-500 uppercase tracking-wider mb-1">Lab Tersedia</p>
                        <h3 class="text-3xl font-black text-zinc-900 tracking-tight tabular-nums">{{ $availableLabs ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 shadow-inner group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-[24px]">corporate_fare</span>
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
            </div>

            <div
                class="ui-surface border {{ ($totalConflicts ?? 0) > 0 ? 'border-rose-200 ring-1 ring-rose-500/20' : 'border-zinc-200/80' }} rounded-3xl p-5 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p
                            class="text-[13px] font-bold {{ ($totalConflicts ?? 0) > 0 ? 'text-rose-500' : 'text-zinc-500' }} uppercase tracking-wider mb-1">
                            Konflik Jadwal</p>
                        <h3
                            class="text-3xl font-black {{ ($totalConflicts ?? 0) > 0 ? 'text-rose-600' : 'text-zinc-900' }} tracking-tight tabular-nums">
                            {{ $totalConflicts ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl {{ ($totalConflicts ?? 0) > 0 ? 'bg-rose-100 text-rose-500' : 'bg-orange-50 text-orange-500' }} flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-[24px]">warning</span>
                    </div>
                </div>
                <div
                    class="absolute -bottom-4 -right-4 w-24 h-24 {{ ($totalConflicts ?? 0) > 0 ? 'bg-rose-500/10' : 'bg-orange-500/5' }} rounded-full blur-2xl">
                </div>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div class="ui-surface border border-zinc-200/80 rounded-3xl p-4 shadow-sm" x-data="{ advancedOpen: dateFilter || statusFilter ? true : false }">
            <form x-ref="filterForm" @submit.prevent="submitForm" class="flex flex-col">
                {{-- Primary Bar --}}
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                    {{-- Global Search --}}
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <x-atoms.icon name="search" class="w-5 h-5 md:w-4 md:h-4 text-zinc-400" x-show="!isLoading" />
                            <div class="w-4 h-4 rounded-full border-2 border-zinc-200 border-t-[rgb(var(--color-primary))] animate-spin"
                                x-show="isLoading" style="display: none;"></div>
                        </div>
                        <input type="search" name="keyword" x-model="keyword" x-on:input.debounce.500ms="submitForm"
                            class="block w-full h-11 md:h-10 pl-10 pr-4 text-sm md:text-xs font-medium text-zinc-800 border border-zinc-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] shadow-sm transition-colors"
                            placeholder="Cari Mata Kuliah, Dosen, atau Kelas..." />

                        {{-- Helper Text --}}
                        <p x-show="showHelper" x-cloak
                            class="absolute -bottom-5 left-0 text-[10px] text-rose-500 font-medium">Minimal 3 karakter untuk
                            mencari.</p>
                    </div>

                    {{-- Toggle Advanced --}}
                    <button type="button" @click="advancedOpen = !advancedOpen"
                        class="w-full md:w-auto h-11 md:h-10 px-4 inline-flex items-center justify-center text-sm md:text-xs font-semibold text-zinc-600 bg-white border border-zinc-200 rounded-xl hover:bg-zinc-50 transition-colors shadow-sm shrink-0 focus:outline-none">
                        <span class="material-symbols-rounded text-[18px] mr-1.5" :class="advancedOpen ? 'text-[rgb(var(--color-primary))]' : ''">tune</span>
                        Filter Lanjutan
                        <span class="material-symbols-rounded text-[18px] ml-1 transition-transform" :class="advancedOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    
                    {{-- Search Button --}}
                    <button type="button" @click="submitForm" class="hidden md:flex h-11 md:h-10 px-6 ui-primary hover:opacity-90 font-bold text-white text-sm md:text-xs rounded-xl shadow-sm transition-colors focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.5)] justify-center items-center">
                        Cari
                    </button>
                </div>

                {{-- Advanced Filters Panel --}}
                <div x-show="advancedOpen" x-collapse x-cloak>
                    <div class="pt-4 mt-4 border-t border-zinc-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <div>
                            <label class="block text-[11px] font-bold text-zinc-500 mb-1.5 ml-1 uppercase tracking-wider">Tanggal Praktikum</label>
                            <x-ui.date-picker name="date" x-model="dateFilter" @change="submitForm" />
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-zinc-500 mb-1.5 ml-1 uppercase tracking-wider">Status Jadwal</label>
                            <div class="relative">
                                <select name="status" x-model="statusFilter" @change="submitForm"
                                    class="w-full text-sm h-10 border border-zinc-200 rounded-xl pl-3 pr-8 bg-white focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-zinc-700 appearance-none">
                                    <option value="">Semua Status</option>
                                    <option value="dijadwalkan">Dijadwalkan</option>
                                    <option value="berlangsung">Berlangsung</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                                    <span class="material-symbols-rounded text-zinc-400 text-[20px]">expand_more</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Active Filter Chips --}}
                <div class="flex flex-wrap gap-2 mt-4 pt-3 border-t border-zinc-100" x-show="keyword || dateFilter || statusFilter" x-cloak>
                    <div class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider py-1 mr-1">Filter Aktif:</div>

                    <template x-if="keyword">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-[rgb(var(--color-primary)_/_0.1)] text-[rgb(var(--color-primary))] border border-[rgb(var(--color-primary)_/_0.2)]">
                            Pencarian: <span x-text="keyword" class="ml-1 font-bold"></span>
                            <button type="button" @click="removeFilter('keyword')"
                                class="ml-1.5 p-0.5 rounded-md hover:bg-[rgb(var(--color-primary)_/_0.2)] transition-colors focus:outline-none">
                                <x-atoms.icon name="x-mark" class="w-3 h-3" />
                            </button>
                        </span>
                    </template>

                    <template x-if="dateFilter">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-[rgb(var(--color-primary)_/_0.1)] text-[rgb(var(--color-primary))] border border-[rgb(var(--color-primary)_/_0.2)]">
                            Tanggal: <span x-text="dateFilter" class="ml-1 font-bold"></span>
                            <button type="button" @click="removeFilter('date')"
                                class="ml-1.5 p-0.5 rounded-md hover:bg-[rgb(var(--color-primary)_/_0.2)] transition-colors focus:outline-none">
                                <x-atoms.icon name="x-mark" class="w-3 h-3" />
                            </button>
                        </span>
                    </template>

                    <template x-if="statusFilter">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-[rgb(var(--color-primary)_/_0.1)] text-[rgb(var(--color-primary))] border border-[rgb(var(--color-primary)_/_0.2)]">
                            Status: <span x-text="statusFilter" class="ml-1 font-bold uppercase"></span>
                            <button type="button" @click="removeFilter('status')"
                                class="ml-1.5 p-0.5 rounded-md hover:bg-[rgb(var(--color-primary)_/_0.2)] transition-colors focus:outline-none">
                                <x-atoms.icon name="x-mark" class="w-3 h-3" />
                            </button>
                        </span>
                    </template>

                    <button type="button" @click="removeFilter('keyword'); removeFilter('date'); removeFilter('status');"
                        class="inline-flex items-center px-2 py-1 text-[11px] font-bold text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 rounded-lg transition-colors ml-1 uppercase tracking-wider focus:outline-none">
                        Reset Semua
                    </button>
                </div>
            </form>
        </div>

        <div id="table-container" class="flex-grow flex flex-col">
            @include('schedules.partials.table')
        </div>

    </div>
@endsection