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
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
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

</head>

<body class="font-sans antialiased text-zinc-900 bg-zinc-50 transition-colors" x-data>
    <div class="min-h-screen flex flex-col bg-zinc-50">
        <x-navigation.topbar />
        <x-navigation.mobile-sidebar />
        <x-navigation.sidebar />

        <div class="flex-1 flex flex-col lg:ml-64">
            <main class="flex-1 w-full mx-auto max-w-7xl px-4 py-5 lg:px-6 lg:py-6">
                @yield('content')
            </main>
            <div class="mt-auto px-4 lg:px-6 py-4 border-t border-zinc-200/80 bg-white/90 backdrop-blur-sm">
                <x-footer />
            </div>
        </div>
    </div>
    
    <!-- Global Toast Notification -->
    <x-ui.toast />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>

    @yield('script')

</body>

</html>
