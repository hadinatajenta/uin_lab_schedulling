<!-- Complete Maintenance/Emergency Modal -->
<div x-data="{ show: false }" 
     @open-modal.window="if ($event.detail === 'complete-maintenance-{{ $maintenance->id }}') show = true"
     @close-modal.window="if ($event.detail === 'complete-maintenance-{{ $maintenance->id }}') show = false"
     x-show="show" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
     
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div x-show="show" class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75" @click="show = false"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            
            <form action="{{ route('rooms.maintenance.complete', $maintenance->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 border-b pb-2">
                        Resolve: {{ $maintenance->title }}
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes / Laporan Penyelesaian</label>
                            <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Jelaskan tindakan yang telah dilakukan..."></textarea>
                        </div>

                        @if($maintenance->is_emergency)
                            @php
                                $suspendedSchedules = $room->schedules()->where('status', 'ditunda_darurat')->get();
                                $expiredSchedules = $suspendedSchedules->where('tanggal_jadwal', '<', now()->toDateString());
                                $activeSchedules = $suspendedSchedules->where('tanggal_jadwal', '>=', now()->toDateString());
                                $availableRooms = app(\App\Domains\Room\Repositories\RoomRepositoryInterface::class)->getAvailableRoomsOn(now()->toDateString()); // Approximation for demo
                            @endphp

                            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                <h4 class="font-semibold text-red-900 mb-2">Tindakan Pemulihan Jadwal (Recovery)</h4>
                                <p class="text-xs text-red-700 mb-4">Ruangan ini sebelumnya berstatus darurat. Silakan tentukan nasib jadwal-jadwal yang sempat di-suspend otomatis.</p>
                                
                                @if($expiredSchedules->count() > 0)
                                    <div class="mb-6">
                                        <h5 class="text-sm font-medium text-gray-900 bg-gray-200 px-3 py-1 rounded">Kelompok A: Jadwal Kadaluarsa ({{ $expiredSchedules->count() }})</h5>
                                        <p class="text-xs text-gray-500 mt-1 mb-2">Jadwal ini tanggalnya sudah terlewat saat masa perbaikan.</p>
                                        <div class="space-y-3">
                                            @foreach($expiredSchedules as $i => $schedule)
                                                <div class="flex items-center justify-between text-sm border-b border-red-100 pb-2">
                                                    <div>
                                                        <span class="font-medium">{{ \Carbon\Carbon::parse($schedule->tanggal_jadwal)->format('d M') }}</span> - {{ $schedule->mata_kuliah }}
                                                        <input type="hidden" name="schedule_recovery[expired_schedules][{{$i}}][id]" value="{{ $schedule->id }}">
                                                    </div>
                                                    <select name="schedule_recovery[expired_schedules][{{$i}}][action]" class="text-sm border-gray-300 rounded-md shadow-sm py-1 pl-2 pr-8 focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="tidak_terlaksana_darurat">Tandai: Tidak Terlaksana Darurat (Rekam Jejak)</option>
                                                        <option value="delete">Hapus Jadwal (Tidak Disarankan)</option>
                                                    </select>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($activeSchedules->count() > 0)
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-900 bg-gray-200 px-3 py-1 rounded">Kelompok B: Jadwal Mendatang ({{ $activeSchedules->count() }})</h5>
                                        <p class="text-xs text-gray-500 mt-1 mb-2">Jadwal ini belum terjadi dan bisa dipulihkan atau dipindahkan.</p>
                                        <div class="space-y-3">
                                            @foreach($activeSchedules as $i => $schedule)
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between text-sm border-b border-red-100 pb-2 gap-2">
                                                    <div>
                                                        <span class="font-medium">{{ \Carbon\Carbon::parse($schedule->tanggal_jadwal)->format('d M') }}</span> - {{ $schedule->mata_kuliah }}
                                                        <input type="hidden" name="schedule_recovery[active_schedules][{{$i}}][id]" value="{{ $schedule->id }}">
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <select name="schedule_recovery[active_schedules][{{$i}}][action]" class="text-sm border-gray-300 rounded-md shadow-sm py-1 pl-2 pr-8 focus:border-indigo-500 focus:ring-indigo-500">
                                                            <option value="resume">Aktifkan Kembali di Ruangan Ini</option>
                                                            <option value="transfer">Pindahkan ke Ruangan Lain</option>
                                                        </select>
                                                        <select name="schedule_recovery[active_schedules][{{$i}}][new_room_id]" class="text-sm border-gray-300 rounded-md shadow-sm py-1 pl-2 pr-8 focus:border-indigo-500 focus:ring-indigo-500">
                                                            <option value="">-- Pilih Ruangan --</option>
                                                            @foreach($availableRooms as $r)
                                                                @if($r->id !== $room->id)
                                                                    <option value="{{ $r->id }}">{{ $r->nama_ruangan }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                @if($suspendedSchedules->count() === 0)
                                    <p class="text-sm text-gray-500 italic">Tidak ada jadwal yang terdampak selama masa darurat.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan & Selesaikan
                    </button>
                    <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
            
            <form action="{{ route('rooms.maintenance.cancel', $maintenance->id) }}" method="POST" class="absolute bottom-4 left-4" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan laporan ini?')">
                @csrf
                @method('PUT')
                <button type="submit" class="text-xs text-red-600 hover:text-red-800 underline">Batalkan Laporan Ini (Salah Input)</button>
            </form>
        </div>
    </div>
</div>
