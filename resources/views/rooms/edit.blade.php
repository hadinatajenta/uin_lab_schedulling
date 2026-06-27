@extends('layouts.app')

@section('content')
    <div class="px-4 py-8 mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('rooms.index') }}" class="text-gray-500 hover:text-gray-700">
                <span class="material-symbols-rounded">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Room</h1>
                <p class="mt-1 text-sm text-gray-500">Update {{ $room->nama_ruangan }} details.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="room_code" class="block text-sm font-medium text-gray-700">Room Code</label>
                        <input type="text" name="room_code" id="room_code" value="{{ old('room_code', $room->room_code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @error('room_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nama_ruangan" class="block text-sm font-medium text-gray-700">Room Name</label>
                        <input type="text" name="nama_ruangan" id="nama_ruangan" value="{{ old('nama_ruangan', $room->nama_ruangan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @error('nama_ruangan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $room->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label for="building" class="block text-sm font-medium text-gray-700">Building</label>
                        <input type="text" name="building" id="building" value="{{ old('building', $room->building) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="floor" class="block text-sm font-medium text-gray-700">Floor</label>
                        <input type="text" name="floor" id="floor" value="{{ old('floor', $room->floor) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="kapasitas" class="block text-sm font-medium text-gray-700">Capacity (People)</label>
                        <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', $room->kapasitas) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required min="1">
                        @error('kapasitas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="pic_user_id" class="block text-sm font-medium text-gray-700">PIC (Admin Lab)</label>
                        <select name="pic_user_id" id="pic_user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Select PIC --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('pic_user_id', $room->pic_user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_active" value="1" class="text-indigo-600 focus:ring-indigo-500" {{ old('is_active', $room->is_active) == '1' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_active" value="0" class="text-red-600 focus:ring-red-500" {{ old('is_active', $room->is_active) == '0' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Inactive</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                    <div class="mt-1 flex items-center space-x-6">
                        @if($room->photo)
                            <div class="shrink-0">
                                <img class="h-16 w-16 object-cover rounded-md" src="{{ asset('storage/' . $room->photo) }}" alt="Room Photo" />
                            </div>
                        @endif
                        <div class="flex-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <span class="material-symbols-rounded text-gray-400 text-4xl">image</span>
                                <div class="flex text-sm text-gray-600">
                                    <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Change file</span>
                                        <input id="photo" name="photo" type="file" class="sr-only" accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Room
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
