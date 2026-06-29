<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-foreground bg-surface-muted flex items-center justify-center min-h-screen relative overflow-hidden">
    
    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0 pointer-events-none flex items-center justify-center overflow-hidden">
        <div class="absolute w-[500px] h-[500px] bg-[rgb(var(--color-primary)_/_0.05)] rounded-full blur-3xl -top-32 -left-32"></div>
        <div class="absolute w-[400px] h-[400px] bg-blue-600/5 rounded-full blur-3xl bottom-0 right-0"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 w-full max-w-lg p-6 mx-auto text-center">
        <!-- Icon / Illustration -->
        <div class="relative w-28 h-28 mx-auto mb-8 ui-primary-soft rounded-full flex items-center justify-center ring-8 ring-surface shadow-sm">
            <span class="material-symbols-rounded text-[56px] text-[rgb(var(--color-primary))] animate-pulse">
                explore_off
            </span>
            <div class="absolute -bottom-1 -right-1 bg-surface w-10 h-10 rounded-full flex items-center justify-center shadow-md border border-default/50">
                <span class="material-symbols-rounded text-[22px] text-danger">
                    sentiment_dissatisfied
                </span>
            </div>
        </div>

        <!-- Typography -->
        <h1 class="text-7xl md:text-8xl font-extrabold text-foreground tracking-tight mb-2">
            404
        </h1>
        <h2 class="text-2xl md:text-3xl font-bold text-foreground tracking-tight mb-4">
            Ups! Halaman Tidak Ditemukan
        </h2>
        <p class="text-foreground-muted mb-8 max-w-md mx-auto leading-relaxed text-sm md:text-base">
            Halaman yang Anda cari mungkin telah dihapus, namanya diubah, atau memang masih dalam tahap pengembangan sistem Antigravity. Jangan khawatir, mari kita kembali ke rute yang benar.
        </p>

        <!-- CTA Button -->
        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 ui-primary font-semibold rounded-xl shadow-md shadow-[rgb(var(--color-primary))_/_0.2] hover:opacity-90 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group ring-1 ring-inset ring-surface/10">
            <span class="material-symbols-rounded mr-2 text-[20px] group-hover:-translate-x-1 transition-transform">
                arrow_back
            </span>
            Kembali ke Dashboard
        </a>
    </div>

</body>
</html>
