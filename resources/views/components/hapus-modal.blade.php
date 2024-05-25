<a data-modal-target="hapus-modal-{{ $id }}" data-modal-toggle="hapus-modal-{{ $id }}"
    class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3 hover:cursor-pointer">Hapus</a>
<div id="hapus-modal-{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    {{ $slot }}
</div>
