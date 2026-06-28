@props(['rounded' => true])

<div {{ $attributes->merge(['class' => 'ui-surface shadow-sm overflow-hidden' . ($rounded ? ' rounded-3xl' : '')]) }}>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="ui-surface-muted border-b border-default/80">
                    {{ $header }}
                </tr>
            </thead>
            <tbody class="divide-y divide-default/50">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
