@extends('layouts.app')

@section('content')
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-foreground sm:text-3xl">Room Management</h1>
                <p class="mt-2 text-sm text-foreground-muted">Manage lab rooms, facilities, and emergency states. (Super Admin Only)</p>
            </div>
            <div>
                <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <span class="material-symbols-rounded mr-2 text-[18px]">add</span> Add Room
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="material-symbols-rounded text-green-400">check_circle</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-surface rounded-xl shadow-sm border border-default overflow-hidden">
            <div class="p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b border-default py-3 px-4 font-semibold text-sm text-foreground-muted">Code</th>
                            <th class="border-b border-default py-3 px-4 font-semibold text-sm text-foreground-muted">Room Name</th>
                            <th class="border-b border-default py-3 px-4 font-semibold text-sm text-foreground-muted">Location</th>
                            <th class="border-b border-default py-3 px-4 font-semibold text-sm text-foreground-muted">Capacity</th>
                            <th class="border-b border-default py-3 px-4 font-semibold text-sm text-foreground-muted">Status</th>
                            <th class="border-b border-default py-3 px-4 font-semibold text-sm text-foreground-muted text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td class="border-b border-gray-100 py-3 px-4 text-sm font-medium text-foreground">{{ $room->room_code ?? '-' }}</td>
                            <td class="border-b border-gray-100 py-3 px-4 text-sm text-foreground">
                                {{ $room->nama_ruangan }}
                                @if($room->pic)
                                <div class="text-xs text-foreground-muted mt-1">PIC: {{ $room->pic->name }}</div>
                                @endif
                            </td>
                            <td class="border-b border-gray-100 py-3 px-4 text-sm text-foreground-muted">
                                {{ $room->building ?? '-' }} <br> <span class="text-xs">{{ $room->floor ?? '' }}</span>
                            </td>
                            <td class="border-b border-gray-100 py-3 px-4 text-sm text-foreground">{{ $room->kapasitas }}</td>
                            <td class="border-b border-gray-100 py-3 px-4">
                                @if(!$room->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Nonaktif / Darurat
                                    </span>
                                @elseif(!$room->isAvailable())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Maintenance
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="border-b border-gray-100 py-3 px-4 text-sm font-medium text-right space-x-2">
                                <a href="{{ route('rooms.show', $room->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View Details">
                                    <span class="material-symbols-rounded text-[20px]">visibility</span>
                                </a>
                                <a href="{{ route('rooms.edit', $room->id) }}" class="text-primary hover:text-blue-900" title="Edit">
                                    <span class="material-symbols-rounded text-[20px]">edit</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="border-b border-gray-100 py-8 px-4 text-sm text-center text-foreground-muted">
                                No rooms found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
