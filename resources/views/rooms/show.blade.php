@extends('layouts.app')

@section('content')
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('rooms.index') }}" class="text-foreground-muted hover:text-foreground-muted">
                    <span class="material-symbols-rounded">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-foreground">{{ $room->room_code }} - {{ $room->nama_ruangan }}</h1>
                    <p class="mt-1 text-sm text-foreground-muted">{{ $room->building ?? 'N/A' }}, Floor: {{ $room->floor ?? 'N/A' }} | Capacity: {{ $room->kapasitas }}</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2">
                @if($room->is_active && $room->isAvailable())
                    <button type="button" x-data @click="$dispatch('open-modal', 'maintenance-modal')" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none">
                        <span class="material-symbols-rounded mr-2 text-[18px]">build</span> Schedule Maintenance
                    </button>
                    
                    <button type="button" x-data @click="$dispatch('open-modal', 'emergency-modal')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none">
                        <span class="material-symbols-rounded mr-2 text-[18px]">emergency</span> Laporkan Darurat
                    </button>
                @elseif(!$room->is_active)
                    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="1">
                        <input type="hidden" name="room_code" value="{{ $room->room_code }}">
                        <input type="hidden" name="nama_ruangan" value="{{ $room->nama_ruangan }}">
                        <input type="hidden" name="kapasitas" value="{{ $room->kapasitas }}">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none">
                            <span class="material-symbols-rounded mr-2 text-[18px]">power</span> Activate Room
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-md bg-red-50 p-4">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Details -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Photo Card -->
                <div class="bg-surface rounded-xl shadow-sm border border-default overflow-hidden">
                    @if($room->photo)
                        <img src="{{ asset('storage/' . $room->photo) }}" alt="Room Photo" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-surface-muted flex items-center justify-center">
                            <span class="material-symbols-rounded text-foreground-muted/60 text-5xl">meeting_room</span>
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-foreground mb-2">Description</h3>
                        <p class="text-sm text-foreground-muted">{{ $room->description ?? 'No description provided.' }}</p>
                        
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-xs font-medium text-foreground-muted uppercase tracking-wider">PIC (Admin Lab)</dt>
                                    <dd class="mt-1 text-sm font-medium text-foreground">{{ $room->pic->name ?? 'Not Assigned' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-foreground-muted uppercase tracking-wider">Status</dt>
                                    <dd class="mt-1">
                                        @if(!$room->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif / Darurat</span>
                                        @elseif(!$room->isAvailable())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Maintenance</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tersedia</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Facilities Card -->
                <div class="bg-surface rounded-xl shadow-sm border border-default overflow-hidden">
                    <div class="px-6 py-4 border-b border-default bg-surface-muted">
                        <h3 class="text-sm font-semibold text-foreground uppercase tracking-wider">Facilities</h3>
                    </div>
                    <div class="p-6">
                        @if($room->facilities->count() > 0)
                            <ul class="space-y-3">
                                @foreach($room->facilities as $facility)
                                    <li class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @if($facility->icon)
                                                <span class="material-symbols-rounded text-foreground-muted/60 mr-2 text-[18px]">{{ $facility->icon }}</span>
                                            @endif
                                            <span class="text-sm text-foreground-muted">{{ $facility->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="text-foreground-muted">Qty: {{ $facility->pivot->quantity }}</span>
                                            <span class="px-2 py-0.5 rounded-full {{ $facility->pivot->condition === 'baik' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ ucfirst(str_replace('_', ' ', $facility->pivot->condition)) }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-foreground-muted text-center">No facilities assigned.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Timeline & Schedules -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Active Maintenances / Emergencies -->
                @php
                    $activeMaintenances = $room->maintenances->whereIn('status', ['scheduled', 'in_progress']);
                @endphp
                
                @if($activeMaintenances->count() > 0)
                    <div class="bg-surface rounded-xl shadow-sm border border-red-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                            <h3 class="text-sm font-semibold text-red-900 uppercase tracking-wider">Active Alerts</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($activeMaintenances as $maintenance)
                                <div class="p-4 rounded-lg border {{ $maintenance->is_emergency ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold {{ $maintenance->is_emergency ? 'text-red-900' : 'text-yellow-900' }}">
                                                {{ $maintenance->is_emergency ? '🚨 DARURAT: ' : '🔧 MAINTENANCE: ' }} {{ $maintenance->title }}
                                            </h4>
                                            <p class="text-sm mt-1 {{ $maintenance->is_emergency ? 'text-red-700' : 'text-yellow-800' }}">
                                                {{ $maintenance->description }}
                                            </p>
                                            <div class="text-xs mt-2 font-medium {{ $maintenance->is_emergency ? 'text-red-600' : 'text-yellow-700' }}">
                                                From: {{ $maintenance->start_date->format('d M Y') }}
                                                To: {{ $maintenance->end_date ? $maintenance->end_date->format('d M Y') : 'TBD' }}
                                            </div>
                                        </div>
                                        <div>
                                            <button type="button" x-data @click="$dispatch('open-modal', 'complete-maintenance-{{ $maintenance->id }}')" class="px-3 py-1 bg-surface shadow-sm border border-default rounded text-xs font-semibold text-foreground-muted hover:bg-surface-muted">
                                                Resolve
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                @include('rooms.partials.complete-modal', ['maintenance' => $maintenance])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Future Schedules -->
                <div class="bg-surface rounded-xl shadow-sm border border-default overflow-hidden">
                    <div class="px-6 py-4 border-b border-default bg-surface-muted flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-foreground uppercase tracking-wider">Upcoming Schedules</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b border-default py-3 px-6 font-semibold text-xs text-foreground-muted uppercase">Date & Time</th>
                                    <th class="border-b border-default py-3 px-6 font-semibold text-xs text-foreground-muted uppercase">Subject</th>
                                    <th class="border-b border-default py-3 px-6 font-semibold text-xs text-foreground-muted uppercase">Lecturer</th>
                                    <th class="border-b border-default py-3 px-6 font-semibold text-xs text-foreground-muted uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($room->schedules as $schedule)
                                    <tr>
                                        <td class="border-b border-gray-100 py-3 px-6 text-sm">
                                            <div class="font-medium text-foreground">{{ \Carbon\Carbon::parse($schedule->tanggal_jadwal)->format('d M Y') }}</div>
                                            <div class="text-foreground-muted text-xs">{{ $schedule->waktu_mulai }} - {{ $schedule->waktu_selesai }}</div>
                                        </td>
                                        <td class="border-b border-gray-100 py-3 px-6 text-sm text-foreground">
                                            {{ $schedule->mata_kuliah }}
                                            <div class="text-xs text-foreground-muted">Kelas: {{ $schedule->kelas }}</div>
                                        </td>
                                        <td class="border-b border-gray-100 py-3 px-6 text-sm text-foreground-muted">
                                            {{ $schedule->dosen }}
                                        </td>
                                        <td class="border-b border-gray-100 py-3 px-6">
                                            @if($schedule->status === 'dijadwalkan')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Scheduled</span>
                                            @elseif($schedule->status === 'ditunda_darurat')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Suspended (Emergency)</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-surface-muted text-foreground">{{ $schedule->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="border-b border-gray-100 py-6 px-6 text-sm text-center text-foreground-muted">
                                            No upcoming schedules.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('rooms.partials.maintenance-modal')
    @include('rooms.partials.emergency-modal')
@endsection
