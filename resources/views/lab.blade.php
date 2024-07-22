@extends('layouts.app')

@section('title', 'Tentang LAB')

@section('content')
    <div class="flex flex-col md:flex-row items-center justify-start lg:justify-between mb-4  ">
        <div>
            <h4 class="text-2xl font-bold wedustext-white">Jadwal</h4>
            <p class="text-sm font-normal text-gray-500 lg:text-sm wedustext-gray-400">
                Kamu bisa mengelola jadwal pemakaian Ruangan LAB disini.
            </p>
        </div>
        <div class="flex self-start p-2">
            @if (Auth::user()->jabatan !== 'Mahasiswa')
                <a href="{{ route('addJadwalView') }}"
                    class="text-white bg-[#152F8B]  focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 wedusbg-blue-600 wedushover:bg-blue-700 focus:outline-none wedusfocus:ring-blue-800">Tambah
                    Jadwal</a>
            @endif
        </div>
    </div>
    <x-alert />
    <div class="grid grid-cols-12 gap-4 mt-4">
        {{-- Left Side --}}
        <div class="col-span-12 md:col-span-4 ">
            {{-- calendar --}}
            <div class="calendar bg-white shadow-md rounded-lg overflow-hidden"
                style="width: 100%;
                        max-width: 600px;
                        margin: 0 auto;">
                <div class="calendar-header"
                    style="display: flex;
                            justify-content: space-between;
                            align-items: center;
                            padding: 1rem;">
                    <h2 id="monthYear" class="text-lg font-bold"></h2>
                    <div>
                        <button id="prev" class="text-lg font-bold">&lt;</button>
                        <button id="next" class="text-lg font-bold">&gt;</button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 p-2 bg-[#8685EF] rounded-full mx-2">
                    <div class="text-center p-2 font-bold text-gray">Status</div>
                    <div class="rounded-full  text-center font-bold bg-[#FEFEDF] text-gray-800 p-2">
                        Dijadwalkan
                    </div>
                </div>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class=" text-gray-800">
                            <th class="w-1/7 py-2">Sun</th>
                            <th class="w-1/7 py-2">Mon</th>
                            <th class="w-1/7 py-2">Tue</th>
                            <th class="w-1/7 py-2">Wed</th>
                            <th class="w-1/7 py-2">Thu</th>
                            <th class="w-1/7 py-2">Fri</th>
                            <th class="w-1/7 py-2">Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendarBody">
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Right side --}}
        <div class="col-span-12 md:col-span-8 bg-white  rounded shadow-md">
            <div class="flex bg-[#8685EF]  rounded-t-md text-gray p-2 items-center mb-4">
                <div class="me-3">
                    <h1 class="text-xl font-bold ms-2">Jadwal untuk </h1>
                </div>
                <div class=" bg-orange-100 rounded-full wedusborder-gray-700">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                        data-tabs-toggle="#default-tab-content" role="tablist">
                        <li class="me-2" role="presentation">
                            <button class="inline-block p-4  rounded-t-lg" id="profile-tab" data-tabs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false">Hari
                                ini</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button
                                class="inline-block p-4  rounded-t-lg hover:text-gray-600 hover:border-gray-300 wedushover:text-gray-300"
                                id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab"
                                aria-controls="dashboard" aria-selected="false">Besok</button>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- jadwal --}}
            <div class="container rounded-lg mx-auto p-6">
                <div id="default-tab-content">
                    {{-- Hari ini --}}
                    <div class="hidden p-4 rounded-lg " id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <ol class="relative border-s border-gray-200 wedusborder-gray-700">
                            @foreach ($jadwal as $agenda)
                                <li class="mb-10 ms-6">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-8 ring-white wedusring-gray-900 wedusbg-blue-900">
                                        <svg class="w-2.5 h-2.5 text-blue-800 wedustext-blue-300" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 wedustext-white">
                                        {{ $agenda->mata_kuliah }} - Kelas {{ $agenda->kelas }} Semester
                                        {{ $agenda->semester }}
                                    </h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 wedustext-gray-500">Dijadwalkan
                                        untuk
                                        {{ \Carbon\Carbon::parse($agenda->tanggal_jadwal)->locale('id_ID')->isoFormat('MMMM Do, YYYY') }}
                                    </time>
                                    <p class="mb-4 text-base font-normal text-gray-500 wedustext-gray-400">Jadwal pemakaian
                                        lab
                                        untuk Matkul {{ $agenda->mata_kuliah }} Submateri {{ $agenda->submateri ?? '-' }}
                                        dengan dosen pembimbing
                                        {{ $agenda->dosen }}. Pukul
                                        {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') }}

                                    </p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                    {{-- besok --}}
                    <div class="hidden p-4 rounded-lg  wedusbg-gray-800" id="dashboard" role="tabpanel"
                        aria-labelledby="dashboard-tab">
                        <ol class="relative border-s border-gray-200 wedusborder-gray-700">
                            @foreach ($jadwal_besok as $agenda)
                                <li class="mb-10 ms-6">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-8 ring-white wedusring-gray-900 wedusbg-blue-900">
                                        <svg class="w-2.5 h-2.5 text-blue-800 wedustext-blue-300" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </span>
                                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 wedustext-white">
                                        {{ $agenda->mata_kuliah }} - Kelas {{ $agenda->kelas }} Semester
                                        {{ $agenda->semester }}
                                    </h3>
                                    <time
                                        class="block mb-2 text-sm font-normal leading-none text-gray-400 wedustext-gray-500">Dijadwalkan
                                        untuk
                                        {{ \Carbon\Carbon::parse($agenda->tanggal_jadwal)->locale('id_ID')->isoFormat('MMMM Do, YYYY') }}
                                    </time>
                                    <p class="mb-4 text-base font-normal text-gray-500 wedustext-gray-400">Jadwal pemakaian
                                        lab
                                        untuk Praktikum Biologi dengan dosen pembimbing Dr.Sucrypto. Pukul
                                        {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') }}
                                    </p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3 grid --}}
    {{-- <div class="grid grid-cols-3 gap-4 my-4">
        <div class="flex items-center justify-center h-24 rounded bg-gray-800 wedusbg-gray-800">
        </div>
        <div class="flex items-center justify-center h-24 rounded bg-gray-800 wedusbg-gray-800">
            <p class="text-2xl text-gray-400 wedustext-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
        <div class="flex items-center justify-center h-24 rounded bg-gray-800 wedusbg-gray-800">
            <p class="text-2xl text-gray-400 wedustext-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
            </p>
        </div>
    </div> --}}

    <div class="mt-4">
        <h4 class="text-2xl font-bold wedustext-white">Daftar semua jadwal</h4>
        <p class="text-sm font-normal text-gray-500 lg:text-sm wedustext-gray-400">
            Kamu bisa mengelola jadwal pemakaian Ruangan LAB disini.
        </p>
    </div>
    <div class="grid grid-cols-2 gap-4 my-4 ">
        <form class="w-full mx-auto">
            <label for="default-search"
                class="mb-2 text-sm font-medium text-gray-900 sr-only wedustext-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 wedustext-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="search" name="keyword"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 wedusbg-gray-700 wedusborder-gray-600 wedusplaceholder-gray-400 wedustext-white wedusfocus:ring-blue-500 wedusfocus:border-blue-500"
                    placeholder="Cari Matakuliah" />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        </form>

        <form class="w-full mx-auto">
            <label for="default-search"
                class="mb-2 text-sm font-medium text-gray-900 sr-only wedustext-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 wedustext-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="search-kelas" name="keyword"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 wedusbg-gray-700 wedusborder-gray-600 wedusplaceholder-gray-400 wedustext-white wedusfocus:ring-blue-500 wedusfocus:border-blue-500"
                    placeholder="Cari kelas..." />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 wedusbg-blue-600 wedushover:bg-blue-700 wedusfocus:ring-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 wedustext-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 wedusbg-gray-700 wedustext-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Mata Kuliah
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Submateri
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Dosen
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Kelas
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Semester
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Waktu mulai
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Waktu selesai
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    @if (Auth::user()->jabatan !== 'Mahasiswa')
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody id="alat-table">
                @foreach ($schedule as $jadwal)
                    @php
                        $currentDateTime = \Carbon\Carbon::now();
                        $jadwalDateTime = \Carbon\Carbon::createFromFormat(
                            'Y-m-d H:i:s',
                            $jadwal->tanggal_jadwal . ' ' . $jadwal->waktu_selesai,
                        );
                    @endphp
                    <tr
                        class="bg-white border-b wedusbg-gray-800 wedusborder-gray-700 hover:bg-gray-50 wedushover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap wedustext-white">
                            {{ Str::ucfirst($jadwal->mata_kuliah ?? '-') }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $jadwal->submateri ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $jadwal->dosen ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $jadwal->kelas ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $jadwal->semester ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $jadwal->tanggal_jadwal ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $jadwal->waktu_mulai ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $jadwal->waktu_selesai ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($jadwalDateTime < $currentDateTime)
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full font-bold ">Selesai</span>
                            @else
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full font-bold">Dijadwalkan</span>
                            @endif
                        </td>
                        <td class="flex items-center px-6 py-4">
                            @if (Auth::user()->jabatan !== 'Mahasiswa')
                                <a href="{{ route('updateJadwal', $jadwal->id) }}"
                                    class="font-medium text-blue-600 wedustext-blue-500 hover:underline">Edit</a>
                                <x-pop-up id="{{ $jadwal->id }}" action="{{ route('hapusJadwal', $jadwal->id) }}"
                                    buttonName="Hapus" />
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $schedule->links() }}

    </div>

@endsection

@section('script')
    <script>
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September",
            "October", "November", "December"
        ];
        let currentDate = new Date();

        function generateCalendar(date) {
            const calendarBody = document.getElementById("calendarBody");
            calendarBody.innerHTML = "";
            const monthYear = document.getElementById("monthYear");
            const month = date.getMonth();
            const year = date.getFullYear();

            monthYear.textContent = `${monthNames[month]} ${year}`;

            const firstDayOfMonth = new Date(year, month, 1).getDay();
            const lastDateOfMonth = new Date(year, month + 1, 0).getDate();
            const lastDateOfPreviousMonth = new Date(year, month, 0).getDate();

            let dateCount = 1;
            let nextMonthDateCount = 1;

            for (let i = 0; i < 6; i++) {
                let row = document.createElement("tr");

                for (let j = 0; j < 7; j++) {
                    let cell = document.createElement("td");
                    cell.classList.add("px-4", "py-2", "text-center");

                    if (i === 0 && j < firstDayOfMonth) {
                        cell.textContent = lastDateOfPreviousMonth - firstDayOfMonth + j + 1;
                        cell.classList.add("text-gray-400");
                    } else if (dateCount > lastDateOfMonth) {
                        cell.textContent = nextMonthDateCount++;
                        cell.classList.add("text-gray-400");
                    } else {
                        cell.textContent = dateCount++;
                        if (date.getFullYear() === new Date().getFullYear() && date.getMonth() === new Date().getMonth() &&
                            cell.textContent == new Date().getDate()) {
                            cell.classList.add("bg-[#8685EF]");
                            cell.classList.add("rounded-full");
                            cell.classList.add("text-gray");
                            cell.classList.add("font-bold");
                        }
                    }

                    row.appendChild(cell);
                }

                calendarBody.appendChild(row);
            }
        }

        document.getElementById("prev").addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar(currentDate);
        });

        document.getElementById("next").addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar(currentDate);
        });

        generateCalendar(currentDate);
    </script>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            filterTable();
        });

        document.getElementById('search-kelas').addEventListener('keyup', function() {
            filterTable();
        });

        function filterTable() {
            var search = document.getElementById('search').value.toLowerCase();
            var searchKelas = document.getElementById('search-kelas').value.toLowerCase();
            var rows = document.querySelectorAll('#alat-table tr');

            rows.forEach(function(row) {
                var matakuliah = row.children[0].textContent.toLowerCase();
                var kelas = row.children[2].textContent.toLowerCase();
                if (matakuliah.includes(search) && kelas.includes(searchKelas)) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }
    </script>
@endsection
