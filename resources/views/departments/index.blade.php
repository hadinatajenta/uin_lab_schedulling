@extends('layouts.app')

@section('content')
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Department Management</h1>
            <p class="mt-2 text-sm text-gray-500">Manage all departments and faculties. (Super Admin Only)</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b border-gray-200 py-3 px-4 font-semibold text-sm text-gray-700">Department Name</th>
                            <th class="border-b border-gray-200 py-3 px-4 font-semibold text-sm text-gray-700">Faculty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                        <tr>
                            <td class="border-b border-gray-100 py-3 px-4 text-sm text-gray-800">{{ $department->name }}</td>
                            <td class="border-b border-gray-100 py-3 px-4 text-sm text-gray-800">{{ $department->faculty ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
