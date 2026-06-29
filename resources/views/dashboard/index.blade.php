@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-2 pb-12 max-w-7xl mx-auto space-y-8">
    <x-ui.page-header title="Operational Dashboard" description="Overview of laboratory status today, {{ Auth::user()->name ?? 'Administrator' }}">
        <div class="flex items-center gap-2">
            {{-- Theme Cycle Button --}}
            <button @click="cycleTheme()" 
                    class="group inline-flex items-center gap-1.5 rounded-full border border-default bg-surface px-2.5 py-1 text-xs font-semibold text-foreground-muted hover:text-primary hover:border-primary/30 transition-all shadow-sm"
                    :title="'Switch Theme (current: ' + theme + ')'">
                <span class="material-symbols-rounded text-[13px]" 
                      :class="theme === 'midnight' ? 'text-blue-400' : (theme === 'clean-tech' ? 'text-primary' : 'text-foreground-muted')">palette</span>
                <span x-text="theme === 'default' ? 'Default' : (theme === 'clean-tech' ? 'Clean Tech' : 'Midnight')"></span>
            </button>
            <span class="inline-flex items-center rounded-full border border-default bg-surface px-3 py-1 text-xs font-semibold text-foreground-muted shadow-sm">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </x-ui.page-header>

    {{-- 1. Quick Actions --}}
    <x-dashboard.quick-actions />

    {{-- 2. Actionable Insights --}}
    <x-dashboard.actionable-insights :insights="$insights" />

    {{-- 3. Overview --}}
    <x-dashboard.overview-cards 
        :totalPengguna="$totalPengguna" 
        :alatTersedia="$alatTersedia" 
        :bahanTersedia="$bahanTersedia" 
    />

    {{-- 4. Today's Operation --}}
    <x-dashboard.today-summary 
        :jadwalHariIni="$jadwalHariIni" 
        :peminjamanHariIni="$peminjamanHariIni" 
        :pengembalianHariIni="$pengembalianHariIni" 
    />

    {{-- 5. Upcoming Schedule & Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10 items-stretch">
        <div class="h-auto lg:h-[450px]">
            <x-dashboard.upcoming-schedule :schedules="$upcomingSchedules" />
        </div>
        <div class="h-auto lg:h-[450px]">
            <x-dashboard.activity-feed :activities="$activities" />
        </div>
    </div>
</div>
@endsection
