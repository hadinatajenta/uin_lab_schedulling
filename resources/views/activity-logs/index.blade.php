@extends('layouts.app')

@section('title', 'Aktivitas Pengguna')

@section('content')
<div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col space-y-6">
    {{-- Header Section --}}
    <x-ui.page-header title="User Activity Logs" description="Pantau semua log perubahan, login, dan aktivitas sistem yang dilakukan oleh pengguna secara real-time." />

    {{-- User Context Card (Modern System-Themed) --}}
    @if($selectedUser)
    <div class="ui-surface border border-default/80 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row items-center gap-6 relative overflow-hidden">
        {{-- Decorative Background Element --}}
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[rgb(var(--color-primary)_/_0.05)] rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-[rgb(var(--color-primary)_/_0.05)] rounded-full blur-3xl pointer-events-none"></div>

        <img src="https://ui-avatars.com/api/?name={{ urlencode($selectedUser->name) }}&background=EEF2FF&color=4F46E5&bold=true&size=128" 
             alt="{{ $selectedUser->name }}" 
             class="w-20 h-20 rounded-full border-4 border-white shadow-md shrink-0 relative z-10">
        
        <div class="flex-1 text-center sm:text-left relative z-10">
            @php
                $badgeType = match (strtolower($selectedUser->role ?? $selectedUser->jabatan ?? '')) {
                    'admin lab' => 'primary',
                    'dosen' => 'primary',
                    'asisten dosen' => 'primary',
                    default => 'neutral',
                };
            @endphp
            <div class="mb-2">
                <x-ui.badge :type="$badgeType">
                    {{ Str::ucfirst($selectedUser->role ?? $selectedUser->jabatan ?? 'Pengguna') }}
                </x-ui.badge>
            </div>
            <h2 class="text-2xl font-extrabold tracking-tight text-foreground mb-1">{{ $selectedUser->name }}</h2>
            <p class="text-foreground-muted text-sm flex items-center justify-center sm:justify-start gap-2 font-medium">
                <x-atoms.icon name="mail" class="w-4 h-4" />
                {{ $selectedUser->email }}
            </p>
        </div>
        
        <div class="relative z-10 text-center sm:text-right border-t sm:border-t-0 sm:border-l border-default/50 pt-4 sm:pt-0 sm:pl-6 w-full sm:w-auto">
            <p class="text-[11px] font-bold text-foreground-muted/60 uppercase tracking-wider mb-1">Total Aktivitas Tersaring</p>
            <p class="text-3xl font-black text-[rgb(var(--color-primary))]">{{ $logs->total() }}</p>
            <a href="{{ route('activity.logs') }}" class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 ui-danger-soft text-danger hover:bg-rose-100 rounded-lg text-xs font-bold transition-colors">
                <x-atoms.icon name="x-mark" class="w-3.5 h-3.5" />
                Hapus Konteks
            </a>
        </div>
    </div>
    @endif

    {{-- Standardized Filter UI (Matching table.user) --}}
    <div class="ui-surface border border-default/80 rounded-3xl p-4 shadow-sm" x-data="{ 
            advancedOpen: {{ request('action') || request('date_start') || request('date_end') || request('user_id') ? 'true' : 'false' }},
            keyword: '{{ request('keyword', '') }}',
            selectedAction: '{{ request('action', '') }}',
            selectedUser: '{{ request('user_id', '') }}',
            isLoading: false,
            submitForm() {
                this.isLoading = true;
                this.$refs.filterForm.submit();
            },
            removeFilter(name) {
                let input = this.$refs.filterForm.elements[name];
                if(input) {
                    input.value = '';
                    if(name === 'keyword') this.keyword = '';
                    if(name === 'action') this.selectedAction = '';
                    if(name === 'user_id') this.selectedUser = '';
                    this.submitForm();
                }
            }
        }">

        <form x-ref="filterForm" method="GET" action="{{ route('activity.logs') }}" class="flex flex-col">
            
            {{-- Primary Bar --}}
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                {{-- Search Input --}}
                <div class="relative flex-grow">
                    <x-ui.input type="search" name="keyword" x-model="keyword" x-on:keydown.enter.prevent="submitForm"
                        icon="search" alpineLoading="isLoading"
                        placeholder="Cari deskripsi aktivitas atau alamat IP..." />
                </div>

                {{-- Action Dropdown using Standard Component --}}
                <div class="relative w-full md:w-64 shrink-0">
                    <input type="hidden" name="action" x-model="selectedAction">
                    <x-ui.dropdown align="left" width="full">
                        <x-slot name="trigger">
                            <button type="button" class="w-full flex items-center justify-between px-4 h-11 md:h-10 text-sm md:text-xs font-medium text-foreground-muted border border-default rounded-xl bg-surface hover:bg-surface-muted focus:outline-none focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors">
                                <span class="truncate pr-2" x-text="selectedAction ? selectedAction.toUpperCase() : 'Semua Aksi'"></span>
                                <span class="material-symbols-rounded text-[20px] text-foreground-muted/60 shrink-0">expand_more</span>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <button type="button" @click="selectedAction = ''; submitForm()" class="w-full text-left px-3 py-2 text-sm md:text-xs font-medium text-foreground-muted hover:bg-surface-muted rounded-lg transition-colors truncate">Semua Aksi</button>
                            @foreach($actions as $action)
                                <button type="button" @click="selectedAction = '{{ $action }}'; submitForm()" class="w-full text-left px-3 py-2 text-sm md:text-xs font-medium text-foreground-muted hover:bg-surface-muted rounded-lg transition-colors truncate" title="{{ strtoupper($action) }}">{{ strtoupper($action) }}</button>
                            @endforeach
                        </x-slot>
                    </x-ui.dropdown>
                </div>

                {{-- Toggle Advanced --}}
                <button type="button" @click="advancedOpen = !advancedOpen"
                    class="w-full md:w-auto h-11 md:h-10 px-4 inline-flex items-center justify-center text-sm md:text-xs font-semibold text-foreground-muted bg-surface border border-default rounded-xl hover:bg-surface-muted transition-colors shadow-sm shrink-0 focus:outline-none">
                    <span class="material-symbols-rounded text-[18px] mr-1.5" :class="advancedOpen ? 'text-[rgb(var(--color-primary))]' : ''">tune</span>
                    Filter Lanjutan
                    <span class="material-symbols-rounded text-[18px] ml-1 transition-transform" :class="advancedOpen ? 'rotate-180' : ''">expand_more</span>
                </button>
                
                {{-- Search Button (For non-AJAX fallback if user wants manual click) --}}
                <button type="button" @click="submitForm" class="hidden md:flex h-11 md:h-10 px-6 ui-primary hover:opacity-90 font-bold text-white text-sm md:text-xs rounded-xl shadow-sm transition-colors focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.5)] justify-center items-center">
                    Cari
                </button>
            </div>

            {{-- Advanced Filters Panel --}}
            <div x-show="advancedOpen" x-collapse x-cloak>
                <div class="pt-4 mt-4 border-t border-default/50 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    
                    <div>
                        <label class="block text-[11px] font-bold text-foreground-muted mb-1.5 ml-1 uppercase tracking-wider">Aktor (Pengguna)</label>
                        <div class="relative">
                            <select name="user_id" x-model="selectedUser" @change="submitForm"
                                class="w-full text-sm h-10 border border-default rounded-xl pl-3 pr-8 bg-surface focus:ring-2 focus:ring-[rgb(var(--color-primary)_/_0.2)] focus:border-[rgb(var(--color-primary))] transition-colors text-foreground-muted appearance-none">
                                <option value="">Semua Pengguna</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                                <span class="material-symbols-rounded text-foreground-muted/60 text-[20px]">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-foreground-muted mb-1.5 ml-1 uppercase tracking-wider">Dari Tanggal</label>
                        <x-ui.date-picker name="date_start" value="{{ request('date_start') }}" placeholder="Pilih tanggal mulai..." @change="submitForm" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-foreground-muted mb-1.5 ml-1 uppercase tracking-wider">Sampai Tanggal</label>
                        <x-ui.date-picker name="date_end" value="{{ request('date_end') }}" placeholder="Pilih tanggal akhir..." @change="submitForm" />
                    </div>

                </div>
            </div>

            {{-- Active Filter Indicators (Chips) --}}
            @php
                $activeFilters = collect([
                    'keyword' => request('keyword') ? 'Pencarian: ' . request('keyword') : null,
                    'action' => request('action') ? 'Aksi: ' . strtoupper(request('action')) : null,
                    'user_id' => request('user_id') ? 'Pengguna Spesifik' : null,
                    'date_start' => request('date_start') ? 'Dari: ' . request('date_start') : null,
                    'date_end' => request('date_end') ? 'Sampai: ' . request('date_end') : null,
                ])->filter();
            @endphp

            @if($activeFilters->count() > 0)
                <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-default/50">
                    <span class="text-[11px] font-bold text-foreground-muted/60 mr-1 uppercase tracking-wider">Filter Aktif:</span>
                    @foreach($activeFilters as $key => $label)
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-[rgb(var(--color-primary)_/_0.1)] text-[rgb(var(--color-primary))] border border-[rgb(var(--color-primary)_/_0.2)]">
                            {{ $label }}
                            <button type="button" @click="removeFilter('{{ $key }}')"
                                class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-[rgb(var(--color-primary)_/_0.2)] transition-colors focus:outline-none">
                                <span class="material-symbols-rounded text-[14px]">close</span>
                            </button>
                        </span>
                    @endforeach

                    <a href="{{ route('activity.logs') }}"
                        class="inline-flex items-center px-2 py-1 text-[11px] font-bold text-foreground-muted/60 hover:text-foreground-muted hover:bg-surface-muted rounded-lg transition-colors ml-1 uppercase tracking-wider">
                        Reset Semua
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Interactive Logs Layout (Cards with Timeline) --}}
    <div class="flex-grow flex flex-col">
        @if($logs->isEmpty())
            <x-ui.empty-state title="Tidak ada log aktivitas" description="Belum ada aktivitas yang dicatat, atau tidak ada data yang cocok dengan filter Anda." icon="history" />
        @else
            <div class="relative flex-grow">
                {{-- Vertical Timeline Line --}}
                <div class="absolute left-[23px] top-6 bottom-6 w-0.5 bg-gradient-to-b from-zinc-200 via-zinc-200 to-transparent hidden sm:block"></div>

                <div class="space-y-4">
                    @foreach($logs as $log)
                        @php
                            $actionLower = strtolower($log->action);
                            $colorClass = 'zinc';
                            $iconName = 'info';

                            if (str_contains($actionLower, 'create') || str_contains($actionLower, 'login') || str_contains($actionLower, 'import')) {
                                $colorClass = 'emerald';
                                $iconName = 'add_circle';
                            } elseif (str_contains($actionLower, 'update') || str_contains($actionLower, 'edit')) {
                                $colorClass = 'blue';
                                $iconName = 'edit';
                            } elseif (str_contains($actionLower, 'delete') || str_contains($actionLower, 'fail')) {
                                $colorClass = 'rose';
                                $iconName = 'delete';
                            }

                            $hasDiff = !empty($log->old_values) || !empty($log->new_values);
                        @endphp

                        <div class="relative flex gap-0 sm:gap-5" x-data="{ showDiff: false, showAgent: false }">

                            {{-- Timeline Node (Icon on the line) --}}
                            <div class="hidden sm:flex flex-col items-center shrink-0" style="width: 48px;">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 border border-{{ $colorClass }}-100 relative z-10 ring-4 ring-surface">
                                    <span class="material-symbols-rounded text-[22px]">{{ $iconName }}</span>
                                </div>
                            </div>

                            {{-- Horizontal Connector Line --}}
                            <div class="hidden sm:flex items-start shrink-0" style="width: 24px; padding-top: 20px;">
                                <div class="w-full h-0.5 bg-default/50"></div>
                            </div>

                            {{-- Card Body --}}
                            <div class="ui-surface border border-default/80 rounded-2xl shadow-sm hover:shadow-md hover:border-default/80 transition-all flex-1 min-w-0 overflow-hidden">
                                <div class="p-5">
                                    {{-- Row 1: User + Action Badge + Timestamp --}}
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-2">
                                        {{-- Left: User + Badge --}}
                                        <div class="flex items-center flex-wrap gap-2 min-w-0">
                                            @if($log->user)
                                                <a href="{{ request()->fullUrlWithQuery(['user_id' => $log->user_id]) }}"
                                                   class="group inline-flex items-center gap-2 hover:bg-[rgb(var(--color-primary)_/_0.05)] rounded-lg px-1.5 py-1 -mx-1.5 transition-colors"
                                                   title="Klik untuk melihat semua aktivitas {{ $log->user->name }}">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name) }}&background=EEF2FF&color=4F46E5&bold=true"
                                                         alt="{{ $log->user->name }}"
                                                         class="w-6 h-6 rounded-full ring-2 ring-surface shrink-0">
                                                    <span class="text-sm font-bold text-foreground group-hover:text-[rgb(var(--color-primary))] transition-colors underline decoration-zinc-300 group-hover:decoration-[rgb(var(--color-primary))] underline-offset-[3px] decoration-1">{{ $log->user->name }}</span>
                                                    <span class="material-symbols-rounded text-[14px] text-default group-hover:text-[rgb(var(--color-primary))] transition-all -ml-0.5">arrow_outward</span>
                                                </a>
                                            @else
                                                <div class="inline-flex items-center gap-2 px-1.5 py-1">
                                                    <div class="w-6 h-6 rounded-full bg-surface-muted flex items-center justify-center ring-2 ring-surface">
                                                        <span class="material-symbols-rounded text-[14px] text-foreground-muted/60">smart_toy</span>
                                                    </div>
                                                    <span class="text-sm font-bold text-foreground-muted italic">System</span>
                                                </div>
                                            @endif

                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 border border-{{ $colorClass }}-100">
                                                {{ $log->action }}
                                            </span>
                                        </div>

                                        {{-- Right: Timestamp --}}
                                        <div class="text-left sm:text-right shrink-0">
                                            <p class="text-xs font-bold text-foreground">{{ $log->created_at->diffForHumans() }}</p>
                                            <p class="text-[10px] font-semibold text-foreground-muted/60 mt-0.5 font-mono">{{ $log->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>

                                    {{-- Row 2: Description --}}
                                    <p class="text-[13px] font-medium text-foreground-muted leading-relaxed mb-3">
                                        {{ $log->description ?? 'Melakukan modifikasi pada ' . class_basename($log->subject_type ?? 'Data') }}
                                    </p>

                                    {{-- Row 3: Meta (IP, Device, Diff toggle) --}}
                                    <div class="flex flex-wrap items-center gap-2">
                                        {{-- IP Badge --}}
                                        <div class="inline-flex items-center gap-1.5 text-[10px] font-bold text-foreground-muted bg-surface-muted border border-default rounded-lg px-2 py-1">
                                            <span class="material-symbols-rounded text-[14px] text-foreground-muted/60">language</span>
                                            <span class="font-mono">{{ $log->ip_address ?? 'IP Unknown' }}</span>
                                        </div>

                                        {{-- Device Toggle --}}
                                        @if($log->user_agent)
                                            <button type="button" @click="showAgent = !showAgent"
                                                    class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-surface-muted border border-default rounded-lg px-2 py-1 transition-colors"
                                                    :class="showAgent ? 'text-[rgb(var(--color-primary))] border-[rgb(var(--color-primary)_/_0.3)] bg-[rgb(var(--color-primary)_/_0.05)]' : 'text-foreground-muted hover:text-foreground-muted hover:bg-surface-muted'"
                                                    title="Info Perangkat">
                                                <span class="material-symbols-rounded text-[14px]">devices</span>
                                                <span x-text="showAgent ? 'Sembunyikan' : 'Perangkat'"></span>
                                            </button>
                                        @endif

                                        {{-- Diff Toggle --}}
                                        @if($hasDiff)
                                            <button type="button" @click="showDiff = !showDiff"
                                                    class="inline-flex items-center gap-1.5 text-[10px] font-bold bg-surface-muted border border-default rounded-lg px-2 py-1 transition-colors"
                                                    :class="showDiff ? 'text-[rgb(var(--color-primary))] border-[rgb(var(--color-primary)_/_0.3)] bg-[rgb(var(--color-primary)_/_0.05)]' : 'text-foreground-muted hover:text-foreground-muted hover:bg-surface-muted'">
                                                <span class="material-symbols-rounded text-[14px]">difference</span>
                                                <span x-text="showDiff ? 'Sembunyikan Data' : 'Lihat Perubahan'"></span>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                {{-- Expandable: Device Info --}}
                                <div x-show="showAgent" x-collapse x-cloak>
                                    <div class="px-5 pb-4">
                                        <div class="p-3 bg-surface-muted border border-default rounded-xl text-[11px] font-mono text-foreground-muted break-words leading-relaxed">
                                            {{ $log->user_agent }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Expandable: Diff Viewer --}}
                                @if($hasDiff)
                                    <div x-show="showDiff" x-collapse x-cloak>
                                        <div class="border-t border-default/50">
                                            <div class="bg-[#1e1e2e] m-3 rounded-xl overflow-hidden border border-[#313244]">
                                                {{-- Terminal Header --}}
                                                <div class="flex items-center gap-2 px-4 py-2.5 bg-[#181825] border-b border-[#313244]">
                                                    <div class="flex gap-1.5">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-[#f38ba8]"></div>
                                                        <div class="w-2.5 h-2.5 rounded-full bg-[#f9e2af]"></div>
                                                        <div class="w-2.5 h-2.5 rounded-full bg-[#a6e3a1]"></div>
                                                    </div>
                                                    <p class="text-[10px] font-bold text-[#6c7086] uppercase tracking-widest ml-2">Perubahan Data</p>
                                                </div>
                                                {{-- Diff Content --}}
                                                <div class="p-4 space-y-2">
                                                    @php
                                                        $allKeys = array_unique(array_merge(
                                                            is_array($log->old_values) ? array_keys($log->old_values) : [],
                                                            is_array($log->new_values) ? array_keys($log->new_values) : []
                                                        ));
                                                    @endphp
                                                    @foreach($allKeys as $key)
                                                        @if(in_array($key, ['created_at', 'updated_at', 'remember_token'])) @continue @endif

                                                        @php
                                                            $oldVal = is_array($log->old_values) && isset($log->old_values[$key]) ? (is_array($log->old_values[$key]) ? json_encode($log->old_values[$key], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $log->old_values[$key]) : null;
                                                            $newVal = is_array($log->new_values) && isset($log->new_values[$key]) ? (is_array($log->new_values[$key]) ? json_encode($log->new_values[$key], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $log->new_values[$key]) : null;
                                                            $isChanged = $oldVal !== $newVal;
                                                        @endphp

                                                        <div class="rounded-lg {{ $isChanged ? 'bg-[#1e1e2e]' : '' }} font-mono text-xs">
                                                            <div class="flex items-start gap-2">
                                                                <span class="text-[#89b4fa] font-semibold shrink-0 pt-0.5">"{{ $key }}"</span>
                                                            </div>
                                                            @if($isChanged && $oldVal !== null)
                                                                <div class="mt-1 pl-4 py-1.5 bg-[#f38ba8]/10 border-l-2 border-[#f38ba8] rounded-r-md">
                                                                    <span class="text-[#f38ba8] break-words whitespace-pre-wrap">- {{ $oldVal }}</span>
                                                                </div>
                                                            @endif
                                                            <div class="mt-1 pl-4 py-1.5 {{ $isChanged ? 'bg-[#a6e3a1]/10 border-l-2 border-[#a6e3a1]' : 'bg-[#cba6f7]/10 border-l-2 border-[#cba6f7]' }} rounded-r-md">
                                                                <span class="{{ $isChanged ? 'text-[#a6e3a1]' : 'text-[#cba6f7]' }} break-words whitespace-pre-wrap">{{ $isChanged ? '+' : '=' }} {{ $newVal ?? $oldVal }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        {{-- Standard Pagination Component --}}
        @if(!$logs->isEmpty())
            <x-ui.pagination :paginator="$logs" label="Total Log" class="mt-6" />
        @endif
    </div>
</div>
@endsection
