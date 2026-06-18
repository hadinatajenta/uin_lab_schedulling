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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="font-sans antialiased text-zinc-900 bg-zinc-50 transition-colors" x-data>
    <div class="min-h-screen flex flex-col">
        <x-navigation.topbar />
        <x-navigation.mobile-sidebar />
        <x-navigation.sidebar />

        <div class="flex-1 flex flex-col" :class="$store.sidebar.expanded ? 'lg:ml-64' : 'lg:ml-20 md:ml-20'">
            <div class="flex-1 p-4 lg:p-6 mx-auto w-full max-w-7xl">
                @yield('content')
            </div>
            <div class="mt-auto px-4 lg:px-6 py-4 border-t border-zinc-200 bg-white">
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