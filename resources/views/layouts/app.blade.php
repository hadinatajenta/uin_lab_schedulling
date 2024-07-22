<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Laravel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- Flowbite --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    {{-- Ckeditor --}}
    <script src="/assets/vendor/ckeditor5/build/ckeditor.js"></script>
    <link rel="stylesheet" href="/css/editor.css">
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- styles --}}
    <style>
        .active {
            background: #8685EF;
            color: #fff;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
            aria-controls="default-sidebar" type="button"
            class="inline-flex items-center w-full p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 wedustext-gray-400 wedushover:bg-gray-700 wedusfocus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                </path>
            </svg>
        </button>

        <aside id="default-sidebar"
            class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
            aria-label="Sidebar">
            <div
                class="h-full px-3 py-4 overflow-y-auto bg-white  bg-clip-border shadow-blue-gray-900 wedusbg-gray-800">
                <div class="flex flex-row items-center p-5">
                    <img class="w-12 h-12 mb-3 rounded-full shadow-lg" src="{{ asset('storage/images/jamet.jpg') }}"
                        alt="Bonnie image" />
                    <div class="flex flex-col ms-3">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 wedustext-white">{{ Auth::user()->name }}</h5>
                        <span class="text-sm text-gray-500 wedustext-gray-400"> {{ Auth::user()->jabatan }} </span>
                    </div>

                </div>
                <hr>
                <ul class="space-y-2 font-medium p-4">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center p-2 rounded-lg group {{ request()->routeIs('dashboard') ? 'text-white font-bold bg-[#8685EF]' : 'text-gray-900 hover:bg-gray-100 wedushover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>

                            <span class="flex-1 ms-3 whitespace-nowrap">Data pengguna</span>

                        </a>
                    </li>
                    <li>
                        <a href="{{ route('lab') }}"
                            class="flex items-center p-2 rounded-lg group {{ request()->routeIs('lab') || request()->routeIs('addJadwalView') ? 'text-white font-bold bg-[#8685EF]' : 'text-gray-900 hover:bg-gray-100 wedushover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Penjadwalan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporanView') }}"
                            class="flex items-center p-2 rounded-lg group {{ request()->routeIs('laporanView') || request()->routeIs('addJadwalView') ? 'text-white font-bold bg-[#8685EF]' : 'text-gray-900 hover:bg-gray-100 wedushover:bg-gray-700' }} ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('alat') }}"
                            class="flex items-center p-2 rounded-lg group {{ request()->routeIs('alat') || request()->routeIs('detailAlat') ? 'text-white font-bold bg-[#8685EF]' : 'text-gray-900 hover:bg-gray-100 wedushover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>

                            <span class="flex-1 ms-3 whitespace-nowrap">Alat & bahan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('limbah') }}"
                            class="flex items-center p-2 rounded-lg group {{ request()->routeIs('limbah') || request()->routeIs('tambahLimbah') ? 'text-white font-bold bg-[#8685EF]' : 'text-gray-900 hover:bg-gray-100 wedushover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                            </svg>


                            <span class="flex-1 ms-3 whitespace-nowrap">Limbah</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tentangLab') }}"
                            class="flex items-center p-2 rounded-lg group {{ request()->routeIs('tentangLab') || request()->routeIs('editInfoLab') ? 'text-white font-bold bg-[#8685EF]' : 'text-gray-900 hover:bg-gray-100 wedushover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Tentang LAB</span>
                        </a>
                    </li>
                    <hr>
                    <li>
                        <form method="POST" action="{{ route('logout') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg wedustext-white hover:bg-gray-100 wedushover:bg-gray-700 group">
                            @csrf
                            <button type="submit" class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                </svg>
                                <span class="flex-1 ms-3 whitespace-nowrap">Keluar</span>
                            </button>
                        </form>
                    </li>

                </ul>

            </div>
        </aside>

        <div class="px-4 pt-4 sm:ml-64">
            <div class=" rounded-lg wedusborder-gray-700">
                @yield('content')
            </div>
            <x-footer />
        </div>


    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>

    @yield('script')

</body>

</html>
