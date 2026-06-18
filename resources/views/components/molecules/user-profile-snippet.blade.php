@props(['name', 'profileUrl' => '#'])

<div class="flex items-center space-x-2.5">
    <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=F3F4F6&color=111827&bold=true" alt="{{ $name }}"
        class="w-8 h-8 rounded-full border border-gray-200">
    <div>
        <h2 class="text-[13px] font-semibold text-gray-900 leading-tight">{{ $name }}</h2>
        <a rel="noopener noreferrer" href="{{ $profileUrl }}"
            class="text-[11px] text-gray-500 hover:text-gray-900 transition-colors">{{ Auth::user()->email ?? 'user@example.com' }}</a>
    </div>
</div>
