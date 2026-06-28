@props(['breadcrumbs' => []])

@php
    // If no breadcrumbs are provided, generate them automatically (Option 2 behavior)
    if (empty($breadcrumbs)) {
        $segments = request()->segments();
        $url = '';

        // Add default root
        $breadcrumbs[] = ['name' => 'Dashboard', 'url' => url('/admin/dashboard')]; // Assuming /dashboard is the main entry

        foreach ($segments as $segment) {
            $url .= '/' . $segment;

            // Skip non-routable prefixes to prevent 404s and weird duplicates like "Dashboard -> admin -> Dashboard"
            if (in_array(strtolower($segment), ['admin', 'dashboard'])) {
                continue;
            }

            $breadcrumbs[] = [
                'name' => ucwords(str_replace(['-', '_'], ' ', $segment)),
                'url' => url($url)
            ];
        }
    }
@endphp

@if(count($breadcrumbs) > 0)
    <nav class="flex mb-3" aria-label="Breadcrumb">
        <ol role="list" class="flex flex-wrap items-center gap-2 text-xs font-medium text-foreground-muted">
            @foreach($breadcrumbs as $index => $crumb)
                <li>
                    <div class="flex items-center">
                        @if($index > 0)
                            <span class="material-symbols-rounded text-[14px] text-foreground-muted mr-2">chevron_right</span>
                        @endif

                        @if($loop->last || empty($crumb['url']))
                            <span class="text-foreground">{{ $crumb['name'] }}</span>
                        @else
                            <a href="{{ $crumb['url'] }}" class="hover:text-primary transition-colors">{{ $crumb['name'] }}</a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </nav>
@endif