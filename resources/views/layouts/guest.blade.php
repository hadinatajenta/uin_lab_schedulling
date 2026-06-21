<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lab Management') }} — Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }

            /* Animated gradient background */
            .auth-bg {
                background: linear-gradient(135deg, #312e81 0%, #4338ca 25%, #6366f1 50%, #818cf8 75%, #c7d2fe 100%);
                background-size: 400% 400%;
                animation: gradientShift 15s ease infinite;
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* Floating shapes */
            .floating-shape {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.06);
                animation: float 20s ease-in-out infinite;
            }

            .floating-shape:nth-child(1) {
                width: 300px; height: 300px;
                top: -50px; left: -80px;
                animation-delay: 0s;
            }

            .floating-shape:nth-child(2) {
                width: 200px; height: 200px;
                bottom: -40px; right: -60px;
                animation-delay: -5s;
                animation-duration: 25s;
            }

            .floating-shape:nth-child(3) {
                width: 150px; height: 150px;
                top: 40%; left: 10%;
                animation-delay: -10s;
                animation-duration: 18s;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33% { transform: translateY(-30px) rotate(5deg); }
                66% { transform: translateY(20px) rotate(-3deg); }
            }

            /* Glass card */
            .glass-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen auth-bg flex items-center justify-center relative overflow-hidden">
            {{-- Floating decorative shapes --}}
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>

            {{-- Content --}}
            <div class="relative z-10 w-full max-w-[480px] mx-4">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
