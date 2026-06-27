@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
    <div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6">
        <x-ui.page-header title="User Data" description="Manage laboratory system users.">
            @if (Auth::user()->jabatan !== 'Mahasiswa')
                <x-ui.dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button type="button"
                            class="w-full md:w-auto inline-flex items-center justify-center rounded-xl ui-primary px-4 h-11 md:h-10 font-semibold text-sm md:text-xs shadow-sm shadow-[rgb(var(--color-primary))_/_0.1] hover:opacity-90 transition-opacity focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary))] focus:ring-offset-2">
                            <span class="material-symbols-rounded text-[20px] md:text-[18px] mr-2">add</span>
                            Tambah Pengguna
                            <span class="material-symbols-rounded text-[20px] md:text-[18px] ml-1 opacity-70">expand_more</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-ui.dropdown-item as="button" data-modal-target="add-modal" data-modal-toggle="add-modal"
                            icon="person_add">
                            Input Manual
                        </x-ui.dropdown-item>

                        <x-ui.dropdown-divider />

                        <x-ui.dropdown-item href="{{ route('users.import.view') }}" icon="upload_file">
                            Import Excel (Bulk)
                        </x-ui.dropdown-item>
                    </x-slot>
                </x-ui.dropdown>
            @endif
        </x-ui.page-header>

        <div class="ui-surface border border-zinc-200/80 rounded-3xl p-4 shadow-sm" x-data="{ 
                        advancedOpen: {{ request('name') || request('email') || request('nim') || request('nip') || request('faculty') || request('department_id') ? 'true' : 'false' }},
                        selectedRole: '{{ request('jabatan', '') }}',
                        selectedFaculty: '{{ request('faculty', '') }}', 
                        keyword: '{{ request('keyword', '') }}',
                        departments: {{ json_encode($allDepartments ?? []) }},
                        isLoading: false,
                        showHelper: false,
                        async submitForm() {
                            if (this.keyword.length > 0 && this.keyword.length < 3) {
                                this.showHelper = true;
                                return;
                            }

                            this.showHelper = false;
                            this.isLoading = true;

                            await this.$nextTick();

                            let form = this.$refs.filterForm;
                            let formData = new FormData(form);
                            let queryString = new URLSearchParams(formData).toString();

                            try {
                                let response = await fetch(`{{ route('users.index') }}?${queryString}`, {
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
                                if(name === 'jabatan') this.selectedRole = '';
                                if(name === 'faculty') this.selectedFaculty = '';
                                this.submitForm();
                            }
                        }
                    }">

            <form x-ref="filterForm" method="GET" action="{{ route('users.index') }}" class="flex flex-col">

                {{-- Primary Bar --}}
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">

                    {{-- Search Input --}}
                    <div class="relative flex-grow">
                        <x-ui.input type="search" name="keyword" x-model="keyword" x-on:input.debounce.500ms="submitForm"
                            icon="search" alpineLoading="isLoading"
                            placeholder="Cari Nama, Email, atau ID..." />

                        {{-- Helper Text --}}
                        <p x-show="showHelper" x-cloak
                            class="absolute -bottom-5 left-0 text-[10px] text-rose-500 font-medium">Minimal 3 karakter untuk
                            mencari.</p>
                    </div>

                    {{-- Role Dropdown using Custom Component --}}
                    <div class="relative w-full md:w-48 shrink-0">
                        <input type="hidden" name="jabatan" x-model="selectedRole">
                        <x-ui.dropdown align="left" width="full">
                            <x-slot name="trigger">
                                <button type="button" class="w-full flex items-center justify-between px-4 h-11 md:h-10 text-sm md:text-xs font-medium text-zinc-700 border border-zinc-200 rounded-xl bg-white hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors">
                                    <span x-text="
                                        selectedRole === 'admin_lab' ? 'Admin Lab' :
                                        selectedRole === 'lecturer' ? 'Dosen' :
                                        selectedRole === 'assistant' ? 'Asisten Dosen' :
                                        selectedRole === 'student' ? 'Mahasiswa' : 'Semua Role'
                                    "></span>
                                    <span class="material-symbols-rounded text-[20px] text-zinc-400">expand_more</span>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <button type="button" @click="selectedRole = ''; submitForm()" class="w-full text-left px-3 py-2 text-sm md:text-xs font-medium text-zinc-700 hover:bg-zinc-50 rounded-lg transition-colors">Semua Role</button>
                                <button type="button" @click="selectedRole = 'admin_lab'; submitForm()" class="w-full text-left px-3 py-2 text-sm md:text-xs font-medium text-zinc-700 hover:bg-zinc-50 rounded-lg transition-colors">Admin Lab</button>
                                <button type="button" @click="selectedRole = 'lecturer'; submitForm()" class="w-full text-left px-3 py-2 text-sm md:text-xs font-medium text-zinc-700 hover:bg-zinc-50 rounded-lg transition-colors">Dosen</button>
                                <button type="button" @click="selectedRole = 'assistant'; submitForm()" class="w-full text-left px-3 py-2 text-sm md:text-xs font-medium text-zinc-700 hover:bg-zinc-50 rounded-lg transition-colors">Asisten Dosen</button>
                                <button type="button" @click="selectedRole = 'student'; submitForm()" class="w-full text-left px-3 py-2 text-sm md:text-xs font-medium text-zinc-700 hover:bg-zinc-50 rounded-lg transition-colors">Mahasiswa</button>
                            </x-slot>
                        </x-ui.dropdown>
                    </div>

                    {{-- Toggle Advanced --}}
                    <button type="button" @click="advancedOpen = !advancedOpen"
                        class="w-full md:w-auto h-11 md:h-10 px-4 inline-flex items-center justify-center text-sm md:text-xs font-semibold text-zinc-600 bg-white border border-zinc-200 rounded-xl hover:bg-zinc-50 transition-colors shadow-sm shrink-0 focus:outline-none">
                        <span class="material-symbols-rounded text-[18px] mr-1.5"
                            :class="advancedOpen ? 'text-[rgb(var(--color-primary))]' : ''">tune</span>
                        Filter Lanjutan
                        <span class="material-symbols-rounded text-[18px] ml-1 transition-transform"
                            :class="advancedOpen ? 'rotate-180' : ''">expand_more</span>
                    </button>
                </div>

                {{-- Advanced Filters Panel --}}
                <div x-show="advancedOpen" x-collapse x-cloak>
                    <div class="pt-4 mt-4 border-t border-zinc-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        <div>
                            <label
                                class="block text-[11px] font-bold text-zinc-500 mb-1.5 ml-1 uppercase tracking-wider">Nama
                                Spesifik</label>
                            <x-ui.input type="text" name="name" value="{{ request('name') }}"
                                x-on:input.debounce.500ms="submitForm" placeholder="Cari nama..." />
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-zinc-500 mb-1.5 ml-1 uppercase tracking-wider">Email
                                Spesifik</label>
                            <x-ui.input type="email" name="email" value="{{ request('email') }}"
                                x-on:input.debounce.500ms="submitForm" placeholder="Cari email..." />
                        </div>

                        {{-- Dynamic Field NIM (Mahasiswa/Asisten) --}}
                        <div x-show="['student', 'assistant', ''].includes(selectedRole)">
                            <label
                                class="block text-[11px] font-bold text-zinc-500 mb-1.5 ml-1 uppercase tracking-wider">NIM</label>
                            <x-ui.input type="text" name="nim" value="{{ request('nim') }}"
                                x-on:input.debounce.500ms="submitForm" placeholder="Cari NIM..." />
                        </div>

                        {{-- Dynamic Field NIP (Dosen) --}}
                        <div x-show="['lecturer', ''].includes(selectedRole)">
                            <label
                                class="block text-[11px] font-bold text-zinc-500 mb-1.5 ml-1 uppercase tracking-wider">NIP</label>
                            <x-ui.input type="text" name="nip" value="{{ request('nip') }}"
                                x-on:input.debounce.500ms="submitForm" placeholder="Cari NIP..." />
                        </div>

                        <div x-show="['student', 'assistant', 'lecturer', ''].includes(selectedRole)">
                            <label
                                class="block text-[11px] font-bold text-zinc-500 mb-1.5 ml-1 uppercase tracking-wider">Fakultas</label>
                            <div class="relative">
                                <select name="faculty" x-model="selectedFaculty" @change="submitForm"
                                    class="w-full text-sm h-10 border border-zinc-200 rounded-xl pl-3 pr-8 bg-white focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-zinc-700 appearance-none">
                                    <option value="">Semua Fakultas</option>
                                    @foreach($faculties ?? [] as $faculty)
                                        <option value="{{ $faculty }}">{{ $faculty }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                                    <span class="material-symbols-rounded text-zinc-400 text-[20px]">expand_more</span>
                                </div>
                            </div>
                        </div>

                        <div x-show="['student', 'assistant', 'lecturer', ''].includes(selectedRole)">
                            <label
                                class="block text-[11px] font-bold text-zinc-500 mb-1.5 ml-1 uppercase tracking-wider">Jurusan</label>
                            <div class="relative">
                                <select name="department_id" @change="submitForm"
                                    class="w-full text-sm h-10 border border-zinc-200 rounded-xl pl-3 pr-8 bg-white focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-zinc-700 appearance-none">
                                    <option value="">Semua Jurusan</option>
                                    <template
                                        x-for="dept in departments.filter(d => !selectedFaculty || d.faculty === selectedFaculty)"
                                        :key="dept.id">
                                        <option :value="dept.id" x-text="dept.name"
                                            :selected="dept.id == '{{ request('department_id') }}'"></option>
                                    </template>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                                    <span class="material-symbols-rounded text-zinc-400 text-[20px]">expand_more</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Active Filter Indicators (Chips) --}}
                @php
                    $activeFilters = collect([
                        'keyword' => request('keyword') ? 'Pencarian: ' . request('keyword') : null,
                        'jabatan' => request('jabatan') ? 'Role: ' . ucwords(str_replace('_', ' ', request('jabatan'))) : null,
                        'name' => request('name') ? 'Nama: ' . request('name') : null,
                        'email' => request('email') ? 'Email: ' . request('email') : null,
                        'nim' => request('nim') ? 'NIM: ' . request('nim') : null,
                        'nip' => request('nip') ? 'NIP: ' . request('nip') : null,
                        'faculty' => request('faculty') ? 'Fakultas: ' . request('faculty') : null,
                        'department_id' => request('department_id') ? 'Jurusan Spesifik' : null,
                    ])->filter();
                @endphp

                @if($activeFilters->count() > 0)
                    <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-zinc-100">
                        <span class="text-[11px] font-bold text-zinc-400 mr-1 uppercase tracking-wider">Filter Aktif:</span>
                        @foreach($activeFilters as $key => $label)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-[rgb(var(--color-primary)_/_0.1)] text-[rgb(var(--color-primary))] border border-[rgb(var(--color-primary)_/_0.2)]">
                                {{ $label }}
                                <button type="button" @click="removeFilter('{{ $key }}')"
                                    class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-[rgb(var(--color-primary)_/_0.2)] transition-colors focus:outline-none">
                                    <span class="material-symbols-rounded text-[14px]">close</span>
                                </button>
                            </span>
                        @endforeach

                        <a href="{{ route('users.index') }}"
                            class="inline-flex items-center px-2 py-1 text-[11px] font-bold text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 rounded-lg transition-colors ml-1 uppercase tracking-wider">
                            Reset Semua
                        </a>
                    </div>
                @endif

            </form>
            <div id="table-container" class="flex-grow flex flex-col">
                @include('users.partials.table')
            </div>
        </div>

        @if (Auth::user()->jabatan !== 'Mahasiswa')
            <x-admin.users.add-modal />
        @endif
@endsection