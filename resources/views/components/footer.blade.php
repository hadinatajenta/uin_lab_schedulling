<footer class="w-full">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between py-4">
        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm ui-text-muted">
            <span class="font-medium text-zinc-600">© {{ date('Y') }}</span>
            <a href="{{ url('/') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                Lab Biologi UIN
            </a>
            <span class="hidden sm:inline text-zinc-300">•</span>
            <span class="text-zinc-500">Lab management system</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-full ui-surface bg-white px-3 py-1 text-xs font-semibold text-zinc-600 shadow-sm">
                Versi 1.0.0
            </span>
        </div>
    </div>
</footer>
