@props(['rounded' => true, 'maxHeight' => 'max-h-[60vh]'])

<div {{ $attributes->merge(['class' => 'ui-surface shadow-sm overflow-hidden border border-default ' . ($rounded ? 'rounded-2xl' : '')]) }}>
    <div class="overflow-x-auto overflow-y-auto custom-scrollbar {{ $maxHeight }}">
        <table class="w-full text-left border-collapse whitespace-nowrap relative">
            <thead class="sticky top-0 z-10 shadow-sm">
                <tr class="bg-surface-muted/95 backdrop-blur-sm border-b border-default/80">
                    {{ $header }}
                </tr>
            </thead>
            <tbody class="divide-y divide-default/50">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>

