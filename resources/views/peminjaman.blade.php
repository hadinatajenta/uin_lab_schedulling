@extends('layouts.app')

@section('title', 'Laporan peminjaman')

@section('content')
    <div class="flex flex-col md:flex-row items-center justify-start lg:justify-between mb-4  ">
        <div>
            <h4 class="text-2xl font-bold wedustext-white">Laporan Peminjaman</h4>
            <p class="text-sm font-normal text-gray-500 lg:text-sm wedustext-gray-400">
                Menampilkan semua daftar peminjaman alat/bahan.
            </p>
        </div>
    </div>

    <div class="grid sm:grid-cols-12 md:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-300 p-4 rounded-lg">
            <div class="flex items-center mb-2">
                <div class="rounded-full bg-blue-200 p-2 text-blue-900  mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <span class="text-gray-500 text-sm">Peminjaman bulan ini</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold">{{ $currentMonthTotal }}</h1>
            </div>
            <div class="mt-1 flex text-sm">
                <span class="{{ $percentageClass }} font-extrabold flex mr-1">
                    {{ $formattedPercentage }}%
                </span>
                <span class="text-gray-500">{{ $comparisonText }}</span>
            </div>
        </div>
        <div class="bg-white border border-gray-300 p-4 rounded-lg">
            <div class="flex items-center mb-2">
                <div class="rounded-full bg-blue-200 p-2 text-blue-900  mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                    </svg>

                </div>
                <span class="text-gray-500 text-sm">Total Pemakaian alat/bahan</span>
            </div>
            <div>
                <h1 class="h1 text-3xl font-bold">{{ $totalBahan }}</h1>
            </div>
        </div>
        <div class="bg-white border border-gray-300 p-4 rounded-lg">
            <div class="flex items-center mb-2">
                <div class="rounded-full bg-blue-200 p-2 text-blue-900  mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                    </svg>

                </div>
                <span class="text-gray-500 text-sm">Total Pemakaian bahan</span>
            </div>
            <div>
                <h1 class="h1 text-3xl font-bold">- ml</h1>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 p-4 bg-white rounded-lg border border-gray-300">
        <div class="bg-white rounded-lg p-4">
            <h2 class="text-center text-lg font-semibold mb-2">Bar Chart Peminjaman</h2>
            <p class="text-center text-sm mb-4">Jumlah peminjaman per tanggal</p>
            <canvas id="peminjamanChart" style="width: 100%"></canvas>
        </div>
        <div class="bg-white rounded-lg p-4">
            <h2 class="text-center text-lg font-semibold mb-2">Pie Chart Peminjaman</h2>
            <p class="text-center text-sm mb-4">Jumlah peminjaman berdasarkan alat</p>
            <canvas id="donutChart"></canvas>
        </div>
    </div>

@endsection

@section('script')
    <script>
        // Fetch data for bar chart
        fetch('/chart-data')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.tanggal_peminjaman);
                const values = data.map(item => item.total_peminjaman);

                const ctx = document.getElementById('peminjamanChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: values,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });

        // Fetch data for pie chart
        fetch('/pie-chart-data')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.nama_alat);
                const values = data.map(item => item.total_peminjaman);

                const ctx = document.getElementById('donutChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                    }
                });
            });
    </script>

@endsection
