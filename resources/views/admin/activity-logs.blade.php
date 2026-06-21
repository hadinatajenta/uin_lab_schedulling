@extends('layouts.app')

@section('title', 'Aktivitas Pengguna')

@section('content')
<div class="px-2 pb-8 min-h-[calc(100vh-12rem)] flex flex-col">
    {{-- Header Section --}}
    <x-ui.page-header title="Aktivitas Pengguna" description="Pantau log perubahan dan aktivitas seluruh pengguna sistem." />

    {{-- Filter Section --}}
    <div class="bg-white border border-zinc-200 shadow-sm rounded-2xl p-5 mb-8">
        <form method="GET" action="{{ route('activity.logs') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="user_id" class="block text-xs font-bold text-zinc-700 mb-1.5">Pilih Aktor (User)</label>
                <select name="user_id" id="user_id" class="w-full text-sm rounded-xl border-zinc-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    <option value="">-- Semua Pengguna --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-48">
                <label for="date_start" class="block text-xs font-bold text-zinc-700 mb-1.5">Dari Tanggal</label>
                <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}" 
                    class="w-full text-sm rounded-xl border-zinc-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
            </div>

            <div class="w-full md:w-48">
                <label for="date_end" class="block text-xs font-bold text-zinc-700 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}" 
                    class="w-full text-sm rounded-xl border-zinc-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="h-[42px] px-5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter
                </button>
                <a href="{{ route('activity.logs') }}" class="h-[42px] px-5 flex items-center justify-center bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-sm font-bold rounded-xl transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Timeline --}}
    <div class="bg-white border border-zinc-200 shadow-sm rounded-2xl p-6">
        @if($logs->isEmpty())
            <div class="py-12 text-center">
                <div class="w-16 h-16 bg-zinc-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900 mb-1">Tidak ada log aktivitas</h3>
                <p class="text-sm text-zinc-500">Belum ada aktivitas yang dicatat atau tidak cocok dengan filter Anda.</p>
            </div>
        @else
            <div class="relative border-l-2 border-zinc-100 ml-4 md:ml-6 pb-4">
                @foreach($logs as $log)
                    @php
                        // Determine colors based on action
                        $actionColor = 'bg-zinc-100 text-zinc-500 ring-zinc-200';
                        $icon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        
                        if (str_contains(strtolower($log->action), 'create') || str_contains(strtolower($log->action), 'login')) {
                            $actionColor = 'bg-emerald-100 text-emerald-600 ring-white';
                            $icon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>';
                        } elseif (str_contains(strtolower($log->action), 'update') || str_contains(strtolower($log->action), 'edit')) {
                            $actionColor = 'bg-blue-100 text-blue-600 ring-white';
                            $icon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>';
                        } elseif (str_contains(strtolower($log->action), 'delete') || str_contains(strtolower($log->action), 'fail')) {
                            $actionColor = 'bg-rose-100 text-rose-600 ring-white';
                            $icon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
                        }
                    @endphp

                    <div class="mb-8 relative" x-data="{ expanded: false }">
                        {{-- Timeline Dot/Icon --}}
                        <div class="absolute -left-[33px] mt-1.5 w-8 h-8 rounded-full flex items-center justify-center ring-8 {{ $actionColor }} shadow-sm">
                            {!! $icon !!}
                        </div>

                        {{-- Content --}}
                        <div class="pl-6">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-1">
                                <div>
                                    <h4 class="text-sm font-bold text-zinc-900">
                                        {{ $log->user ? $log->user->name : 'System/Unknown' }}
                                    </h4>
                                    <p class="text-sm text-zinc-600 mt-0.5">
                                        <span class="font-medium text-zinc-900">{{ strtoupper($log->action) }}</span> - 
                                        {{ $log->description ?? 'Melakukan aktivitas pada ' . class_basename($log->subject_type) }}
                                    </p>
                                </div>
                                <div class="text-[11px] font-semibold text-zinc-400 bg-zinc-50 px-2.5 py-1 rounded-md shrink-0 border border-zinc-100">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            <p class="text-[11px] text-zinc-400 mb-3 flex items-center gap-2">
                                IP: {{ $log->ip_address ?? '-' }}
                                @if($log->old_values || $log->new_values)
                                    <button @click="expanded = !expanded" class="text-indigo-600 hover:text-indigo-800 font-bold ml-2 underline decoration-indigo-200 underline-offset-2">
                                        <span x-text="expanded ? 'Sembunyikan Detail' : 'Lihat Detail'"></span>
                                    </button>
                                @endif
                            </p>

                            {{-- Collapsible Details (Diff) --}}
                            @if($log->old_values || $log->new_values)
                                <div x-show="expanded" x-collapse x-cloak class="mt-3">
                                    <div class="bg-zinc-50 border border-zinc-200 rounded-xl overflow-hidden text-xs">
                                        <table class="w-full text-left">
                                            <thead class="bg-zinc-100 border-b border-zinc-200">
                                                <tr>
                                                    <th class="px-4 py-2 font-bold text-zinc-700 w-1/3">Field</th>
                                                    <th class="px-4 py-2 font-bold text-zinc-700 w-1/3 border-l border-zinc-200">Old Value</th>
                                                    <th class="px-4 py-2 font-bold text-zinc-700 w-1/3 border-l border-zinc-200">New Value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-zinc-200">
                                                @php
                                                    $allKeys = array_unique(array_merge(
                                                        is_array($log->old_values) ? array_keys($log->old_values) : [],
                                                        is_array($log->new_values) ? array_keys($log->new_values) : []
                                                    ));
                                                @endphp
                                                @foreach($allKeys as $key)
                                                    @if(in_array($key, ['created_at', 'updated_at', 'remember_token'])) @continue @endif
                                                    <tr>
                                                        <td class="px-4 py-2.5 font-medium text-zinc-900 bg-white">{{ $key }}</td>
                                                        <td class="px-4 py-2.5 text-rose-600 bg-rose-50/30 border-l border-zinc-200 break-all">
                                                            {{ is_array($log->old_values) && isset($log->old_values[$key]) ? (is_array($log->old_values[$key]) ? json_encode($log->old_values[$key]) : $log->old_values[$key]) : '-' }}
                                                        </td>
                                                        <td class="px-4 py-2.5 text-emerald-600 bg-emerald-50/30 border-l border-zinc-200 break-all">
                                                            {{ is_array($log->new_values) && isset($log->new_values[$key]) ? (is_array($log->new_values[$key]) ? json_encode($log->new_values[$key]) : $log->new_values[$key]) : '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6 pt-6 border-t border-zinc-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
