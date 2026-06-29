@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
    <div class="px-2 pb-8 space-y-6">
        <x-ui.page-header title="Borrowings Report" description="Displays all equipment/material borrowing lists.">
        </x-ui.page-header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-surface border border-default/80 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-2xl ui-primary-soft text-[rgb(var(--color-primary))] flex items-center justify-center">
                        <x-atoms.icon name="cube" class="w-5 h-5" />
                    </div>
                    <p class="text-sm font-semibold text-foreground-muted">Peminjaman bulan ini</p>
                </div>
                <h2 class="text-3xl font-bold text-foreground">{{ $currentMonthTotal }}</h2>
                <p class="mt-1 text-sm text-foreground-muted">
                    <span class="{{ $percentageClass }} font-extrabold">{{ $formattedPercentage }}%</span>
                    {{ $comparisonText }}
                </p>
            </div>

            <div class="bg-surface border border-default/80 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-2xl ui-primary-soft text-[rgb(var(--color-primary))] flex items-center justify-center">
                        <x-atoms.icon name="cube" class="w-5 h-5" />
                    </div>
                    <p class="text-sm font-semibold text-foreground-muted">Total Pemakaian alat/bahan</p>
                </div>
                <h2 class="text-3xl font-bold text-foreground">{{ $totalBahan }}</h2>
            </div>

            <div class="bg-surface border border-default/80 rounded-3xl p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-2xl bg-surface-muted text-foreground-muted flex items-center justify-center">
                        <x-atoms.icon name="clipboard-document-list" class="w-5 h-5" />
                    </div>
                    <p class="text-sm font-semibold text-foreground-muted">Total Pemakaian bahan</p>
                </div>
                <h2 class="text-3xl font-bold text-foreground">- ml</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-surface rounded-3xl p-5 border border-default/80 shadow-sm">
                <h2 class="text-lg font-bold text-foreground">Bar Chart Peminjaman</h2>
                <p class="text-sm text-foreground-muted mt-1 mb-4">Jumlah peminjaman per tanggal</p>
                <div class="h-[320px]">
                    <canvas id="peminjamanChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="bg-surface rounded-3xl p-5 border border-default/80 shadow-sm">
                <h2 class="text-lg font-bold text-foreground">Pie Chart Peminjaman</h2>
                <p class="text-sm text-foreground-muted mt-1 mb-4">Jumlah peminjaman berdasarkan alat</p>
                <div class="h-[320px]">
                    <canvas id="donutChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        fetch('/chart-data')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.tanggal_peminjaman);
                const values = data.map(item => item.total_peminjaman);
                const ctx = document.getElementById('peminjamanChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: values,
                            backgroundColor: 'rgba(79, 70, 229, 0.12)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1,
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, grid: { color: '#e4e4e7' } }, x: { grid: { display: false } } }
                    }
                });
            });

        fetch('/pie-chart-data')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.nama_alat);
                const values = data.map(item => item.total_peminjaman);
                const ctx = document.getElementById('donutChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                            borderColor: '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            });
    </script>
@endsection
