<!-- Emergency Modal -->
<div x-data="{ show: false }" 
     @open-modal.window="if ($event.detail === 'emergency-modal') show = true"
     @close-modal.window="if ($event.detail === 'emergency-modal') show = false"
     x-show="show" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
     
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div x-show="show" class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-surface-muted0 opacity-75" @click="show = false"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show" class="inline-block align-bottom bg-surface rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <form action="{{ route('rooms.maintenance.store', $room->id) }}" method="POST">
                @csrf
                <input type="hidden" name="is_emergency" value="1">
                
                <div class="bg-surface px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="material-symbols-rounded text-red-600">emergency</span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-foreground">
                                Lapor Kondisi Darurat (Force Majeure)
                            </h3>
                            <p class="text-sm text-foreground-muted mt-2">
                                Ruangan akan otomatis dinonaktifkan. Anda harus memutuskan nasib jadwal-jadwal yang ada.
                            </p>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-foreground-muted">Tipe Darurat</label>
                                    <select name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-default focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                                        <option value="kebakaran">Kebakaran</option>
                                        <option value="banjir">Banjir</option>
                                        <option value="bencana_alam">Bencana Alam</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-foreground-muted">Judul Laporan</label>
                                    <input type="text" name="title" class="mt-1 block w-full border-default rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-foreground-muted">Deskripsi/Kronologi</label>
                                    <textarea name="description" rows="3" class="mt-1 block w-full border-default rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-foreground-muted">Tanggal Kejadian</label>
                                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full border-default rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-foreground-muted">Tindakan pada Jadwal Mendatang</label>
                                    <select name="schedule_action" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md" required>
                                        <option value="auto_suspend">Suspend Semua Jadwal Mendatang (Disarankan)</option>
                                        <option value="auto_cancel">Batalkan Semua Jadwal Mendatang</option>
                                    </select>
                                    <p class="text-xs text-foreground-muted mt-1">Anda bisa mengatur ulang jadwal yang di-suspend setelah kondisi ruangan pulih.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-surface-muted px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Laporkan Darurat
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-default shadow-sm px-4 py-2 bg-surface text-base font-medium text-foreground-muted hover:bg-surface-muted focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
