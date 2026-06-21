@props(['rounded' => true])

<div {{ $attributes->merge(['class' => 'bg-white border border-zinc-200/80 shadow-sm overflow-hidden' . ($rounded ? ' rounded-2xl' : '')]) }}>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-indigo-50/50 border-b border-indigo-100/80">
                    {{ $header }}
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
