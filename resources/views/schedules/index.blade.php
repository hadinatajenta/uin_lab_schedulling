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

                    // Re-initialize Flowbite components for newly injected elements
                    if (typeof initFlowbite === 'function') {
                        initFlowbite();
                    }
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
            <div class="ui-surface border border-default/80 rounded-3xl p-5 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-foreground-muted uppercase tracking-wider mb-1">Jadwal Hari Ini</p>
                        <h3 class="text-3xl font-black text-foreground tracking-tight tabular-nums">{{ $totalToday ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl ui-primary-soft flex items-center justify-center text-primary shadow-inner group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-[24px]">calendar_month</span>
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 ui-primary-soft0/5 rounded-full blur-2xl"></div>
            </div>

            <div class="ui-surface border border-default/80 rounded-3xl p-5 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-bold text-foreground-muted uppercase tracking-wider mb-1">Lab Tersedia</p>
                        <h3 class="text-3xl font-black text-foreground tracking-tight tabular-nums">{{ $availableLabs ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl ui-success-soft flex items-center justify-center text-success shadow-inner group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-[24px]">corporate_fare</span>
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 ui-success-soft0/5 rounded-full blur-2xl"></div>
            </div>

            <div
                class="ui-surface border {{ ($totalConflicts ?? 0) > 0 ? 'border-danger-soft ring-1 ring-rose-500/20' : 'border-default/80' }} rounded-3xl p-5 shadow-sm relative overflow-hidden group">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p
                            class="text-[13px] font-bold {{ ($totalConflicts ?? 0) > 0 ? 'text-danger' : 'text-foreground-muted' }} uppercase tracking-wider mb-1">
                            Konflik Jadwal</p>
                        <h3
                            class="text-3xl font-black {{ ($totalConflicts ?? 0) > 0 ? 'text-danger' : 'text-foreground' }} tracking-tight tabular-nums">
                            {{ $totalConflicts ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl {{ ($totalConflicts ?? 0) > 0 ? 'bg-rose-100 text-danger' : 'ui-warning-soft text-orange-500' }} flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-[24px]">warning</span>
                    </div>
                </div>
                <div
                    class="absolute -bottom-4 -right-4 w-24 h-24 {{ ($totalConflicts ?? 0) > 0 ? 'ui-danger-soft0/10' : 'ui-warning-soft0/5' }} rounded-full blur-2xl">
                </div>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div class="ui-surface border border-default/80 rounded-3xl p-4 shadow-sm" x-data="{ advancedOpen: dateFilter || statusFilter ? true : false }">
            <form x-ref="filterForm" @submit.prevent="submitForm" class="flex flex-col">
                {{-- Primary Bar --}}
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                    {{-- Search Input --}}
                    <div class="relative flex-grow">
                        <x-ui.input type="search" name="keyword" x-model="keyword" x-on:input.debounce.500ms="submitForm"
                            icon="search" alpineLoading="isLoading"
                            placeholder="Cari Mata Kuliah, Dosen, atau Kelas..." />
                        
                        {{-- Helper Text --}}
                        <p x-show="showHelper" x-cloak
                            class="absolute -bottom-5 left-0 text-[10px] text-danger font-medium">Minimal 3 karakter untuk
                            mencari.</p>
                    </div>

                    {{-- Toggle Advanced --}}
                    <button type="button" @click="advancedOpen = !advancedOpen"
                        class="w-full md:w-auto h-11 md:h-10 px-4 inline-flex items-center justify-center text-sm md:text-xs font-semibold text-foreground-muted bg-surface border border-default rounded-xl hover:bg-surface-muted transition-colors shadow-sm shrink-0 focus:outline-none">
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
                    <div class="pt-4 mt-4 border-t border-default/50 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <div>
                            <label class="block text-[11px] font-bold text-foreground-muted mb-1.5 ml-1 uppercase tracking-wider">Tanggal Praktikum</label>
                            <x-ui.date-picker name="date" x-model="dateFilter" @change="submitForm" />
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-foreground-muted mb-1.5 ml-1 uppercase tracking-wider">Status Jadwal</label>
                            <div class="relative">
                                <select name="status" x-model="statusFilter" @change="submitForm"
                                    class="w-full text-sm h-10 border border-default rounded-xl pl-3 pr-8 bg-surface focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-foreground-muted appearance-none">
                                    <option value="">Semua Status</option>
                                    <option value="dijadwalkan">Dijadwalkan</option>
                                    <option value="berlangsung">Berlangsung</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                    <option value="konflik">Konflik</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                                    <span class="material-symbols-rounded text-foreground-muted/60 text-[20px]">expand_more</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Active Filter Chips --}}
                <div class="flex flex-wrap gap-2 mt-4 pt-3 border-t border-default/50" x-show="keyword || dateFilter || statusFilter" x-cloak>
                    <div class="text-[11px] font-bold text-foreground-muted/60 uppercase tracking-wider py-1 mr-1">Filter Aktif:</div>

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
                        class="inline-flex items-center px-2 py-1 text-[11px] font-bold text-foreground-muted/60 hover:text-foreground-muted hover:bg-surface-muted rounded-lg transition-colors ml-1 uppercase tracking-wider focus:outline-none">
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