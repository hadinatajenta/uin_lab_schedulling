@props(['title', 'description' => null, 'breadcrumbs' => []])

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-5 mb-8 border-b border-default/80">
    <div class="w-full">
        <!-- Breadcrumbs -->
        <x-ui.breadcrumbs :breadcrumbs="$breadcrumbs" />

        <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-foreground">{{ $title }}</h1>
        @if($description)
            <p class="text-sm leading-6 ui-text-muted mt-2 max-w-3xl">{{ $description }}</p>
        @endif
    </div>
    
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="flex items-center gap-3 shrink-0 w-full md:w-auto mt-2 md:mt-0">
            {{ $slot }}
        </div>
    @endif
</div>