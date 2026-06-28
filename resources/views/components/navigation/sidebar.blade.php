@php
    $navigation = config('navigation');
@endphp

<aside
    class="hidden md:flex fixed top-0 left-0 z-40 h-screen transition-all duration-300 ui-surface border-r border-default shadow-sm flex-col w-64"
    aria-label="Sidebar">
    <div class="h-full flex flex-col hide-scrollbar relative">

        <!-- Header -->
        <div class="h-16 flex items-center shrink-0 border-b border-default/50 relative px-4 justify-between">
            <div class="flex items-center space-x-3 cursor-pointer">
                <img src="{{ asset('images/logo-uin.svg') }}" alt="Logo UIN" class="w-8 h-8 shrink-0 object-contain" />
                <div>
                    <h2 class="text-[15px] font-bold text-foreground leading-none tracking-tight">Lab UIN Raden Intan</h2>
                    <p class="text-[11px] text-foreground-muted font-medium mt-1 uppercase tracking-wider">Lab Management System
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto hide-scrollbar px-3 pb-4 pt-2">
            <ul class="space-y-2">
                @foreach($navigation as $index => $section)
                    @php
                        // Check Role Access
                        if (isset($section['roles']) && Auth::check()) {
                            $hasRole = false;
                            foreach ($section['roles'] as $role) {
                                if (Auth::user()->roles->contains('slug', $role)) {
                                    $hasRole = true;
                                    break;
                                }
                            }
                            if (!$hasRole) continue;
                        }

                        // Check if any child route is active to keep it open by default
                        $hasActiveChild = false;
                        foreach ($section['items'] as $item) {
                            if (isset($item['active_matches'])) {
                                foreach ($item['active_matches'] as $match) {
                                    if (request()->routeIs($match)) {
                                        $hasActiveChild = true;
                                        break 2;
                                    }
                                }
                            } else if (isset($item['route'])) {
                                try {
                                    if (request()->routeIs($item['route'])) {
                                        $hasActiveChild = true;
                                        break;
                                    }
                                } catch (\Exception $e) {
                                }
                            }
                        }

                        // Dashboard section shouldn't be collapsible
                        $isDashboard = $section['section'] === 'GENERAL';
                    @endphp

                    @if($isDashboard)
                        <li>
                            <ul class="space-y-1">
                                @foreach($section['items'] as $item)
                                    @if(isset($item['children']))
                                        <x-navigation.nav-group :group="$item" :isMobile="false" />
                                    @else
                                        <x-navigation.nav-item :item="$item" :isMobile="false" />
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li x-data="{ open: {{ $hasActiveChild ? 'true' : 'false' }} }" class="pt-1">
                            <!-- Parent Toggle -->
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-2 text-[11px] font-bold tracking-wider ui-text-muted uppercase hover:text-foreground transition-colors rounded-lg hover:bg-surface-muted outline-none focus-visible:ring-2 focus-visible:ring-primary group"
                                x-transition.opacity>
                                <div class="flex items-center group-hover:translate-x-0.5 transition-transform">
                                    @if(isset($section['icon']))
                                        <span class="material-symbols-rounded text-[14px] mr-1.5">{{ $section['icon'] }}</span>
                                    @endif
                                    <span>{{ $section['section'] }}</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-foreground-muted transition-transform duration-200"
                                    :class="open ? 'rotate-180 text-foreground' : ''" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Divider when collapsed -->
                            <div x-show="!$store.sidebar.expanded" class="px-2 py-2 flex justify-center" style="display: none;">
                                <div class="w-5 h-px bg-default"></div>
                            </div>

                            <!-- Children -->
                            <ul class="space-y-1 mt-1 relative" x-show="!$store.sidebar.expanded || open"
                                :class="$store.sidebar.expanded ? 'ml-2 pl-2 border-l border-default/50' : ''"
                                style="display: none;">
                                @foreach($section['items'] as $item)
                                    @if(isset($item['children']))
                                        <x-navigation.nav-group :group="$item" :isMobile="false" />
                                    @else
                                        <x-navigation.nav-item :item="$item" :isMobile="false" />
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <!-- User Menu -->
        <div class="mt-auto shrink-0 border-t border-default p-3 bg-surface-muted/50">
            <x-navigation.user-menu :isMobile="false" />
        </div>
    </div>
</aside>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>