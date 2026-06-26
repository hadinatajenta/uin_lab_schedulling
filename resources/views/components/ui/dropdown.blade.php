@props(['align' => 'right', 'width' => '48'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0 mt-2';
        break;
    case 'top':
        $alignmentClasses = 'origin-bottom bottom-full mb-2';
        break;
    case 'top-right':
        $alignmentClasses = 'origin-bottom-right bottom-full right-0 mb-2';
        break;
    case 'top-left':
        $alignmentClasses = 'origin-bottom-left bottom-full left-0 mb-2';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0 mt-2';
        break;
}

switch ($width) {
    case '48':
        $widthClasses = 'w-48';
        break;
    case '56':
        $widthClasses = 'w-56';
        break;
    case '64':
        $widthClasses = 'w-64';
        break;
    case 'auto':
        $widthClasses = 'w-auto min-w-[200px]';
        break;
    case 'full':
        $widthClasses = 'w-full';
        break;
    default:
        $widthClasses = $width;
        break;
}
@endphp

<div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open" class="w-full">
        {{ $trigger }}
    </div>

    <div x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95 transform -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 transform -translate-y-2"
        class="absolute z-50 {{ $widthClasses }} {{ $alignmentClasses }} bg-white border border-zinc-200 shadow-md rounded-xl p-1 focus:outline-none max-h-60 overflow-y-auto"
        style="display: none;"
        @click="open = false">
        {{ $content }}
    </div>
</div>
