<a data-modal-target="edit-modal-{{ $id }}" data-modal-toggle="edit-modal-{{ $id }}"
    class="font-medium text-blue-600 dark:text-blue-500 hover:underline hover:cursor-pointer">Edit</a>
<div id="edit-modal-{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    {{ $slot }}
</div>
