<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Laravel')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-uin.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=IBM+Plex+Mono:wght@400;500&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet" />
    {{-- Flowbite --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    {{-- Ckeditor --}}
    <script src="/assets/vendor/ckeditor5/build/ckeditor.js"></script>
    <link rel="stylesheet" href="/css/editor.css">
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- styles --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <style>
        /* Minimalist Flatpickr Override */
        .flatpickr-calendar { box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important; border: 1px solid #e4e4e7 !important; border-radius: 1rem !important; padding: 4px !important; font-family: 'IBM Plex Sans', sans-serif !important; }
        .flatpickr-day.selected { background: rgb(var(--color-primary)) !important; border-color: rgb(var(--color-primary)) !important; font-weight: bold; }
        .flatpickr-day:hover { background: #f4f4f5 !important; border-color: #f4f4f5 !important; color: #18181b !important; }
        .flatpickr-current-month .flatpickr-monthDropdown-months { border-radius: 0.5rem; padding: 2px; }
        .flatpickr-current-month .flatpickr-monthDropdown-months:hover { background: #f4f4f5; }

        /* Tabular numbers utility for metric displays */
        .tabular-nums { font-feature-settings: "tnum"; font-variant-numeric: tabular-nums; }
    </style>

</head>

<body class="font-sans antialiased text-zinc-900 bg-[rgb(var(--color-bg))] transition-colors" x-data>
    <div class="min-h-screen flex flex-col bg-[rgb(var(--color-bg))]">
        <x-navigation.topbar />
        <x-navigation.mobile-sidebar />
        <x-navigation.sidebar />

        <div class="flex-1 flex flex-col lg:ml-64">
            <main class="flex-1 w-full mx-auto max-w-7xl px-4 py-5 lg:px-6 lg:py-6">
                @yield('content')
            </main>
            <div class="mt-auto px-4 lg:px-6 py-4 border-t border-[rgb(var(--color-border))] ui-surface backdrop-blur-sm">
                <x-footer />
            </div>
        </div>
    </div>
    
    <!-- Global Toast Notification -->
    <x-ui.toast />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    @yield('script')

</body>

</html>
