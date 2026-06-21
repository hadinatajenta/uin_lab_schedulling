@props(['user'])
<form method="POST" action="{{ route('delete.users', $user->id) }}" id="delete-modal-{{ $user->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full text-left">
    @csrf
    @method('DELETE')
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-3xl shadow-xl border border-zinc-200">
            <button type="button" class="absolute top-3 right-3 text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center focus:outline-none" data-modal-hide="delete-modal-{{ $user->id }}">
                <x-atoms.icon name="x-mark" class="w-4 h-4" />
                <span class="sr-only">Tutup</span>
            </button>
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4">
                    <x-atoms.icon name="trash" class="w-6 h-6" />
                </div>
                <h3 class="text-sm font-bold text-zinc-900 mb-2">Hapus Pengguna</h3>
                <p class="text-xs text-zinc-500 font-medium mb-6 leading-relaxed">Apakah Anda yakin ingin menghapus <b>{{ $user->name }}</b>? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex items-center justify-center gap-3">
                    <button data-modal-hide="delete-modal-{{ $user->id }}" type="button" class="py-2.5 px-4 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-xl transition-colors">Batalkan</button>
                    <button type="submit" class="text-white bg-rose-600 hover:bg-rose-700 font-semibold rounded-xl text-xs px-4 py-2.5 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>
