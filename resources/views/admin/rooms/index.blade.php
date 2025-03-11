@extends('layouts.layout')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white w-full max-w-6xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Room Management</h1>
            <a href="{{ route('admin.rooms_create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700">
                Create Room
            </a>
        </div>

        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead class="bg-gray-200 text-gray-700">
            <tr>
                <th class="py-3 px-6 border-b">Room Name</th>
                <th class="py-3 px-6 border-b">Floor</th>
                <th class="py-3 px-6 border-b">Capacity</th>
                <th class="py-3 px-6 border-b">Status</th>
                <th class="py-3 px-6 border-b text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rooms as $room)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-6">{{ $room->name }}</td>
                    <td class="py-3 px-6">Floor {{ $room->floor }}</td>
                    <td class="py-3 px-6">{{ $room->capacity }} people</td>
                    <td class="py-3 px-6">
                        @if ($room->is_active)
                            <span class="px-3 py-1 text-green-700 bg-green-200 rounded-full text-sm">Active</span>
                        @else
                            <span class="px-3 py-1 text-red-700 bg-red-200 rounded-full text-sm">Inactive</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('admin.rooms_edit', ['room' => $room->id]) }}" class="bg-yellow-500 text-white px-3 py-1 rounded shadow hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('admin.rooms_destroy', ['room' => $room->id]) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded shadow hover:bg-red-600" onclick="return confirm('Delete this room? {{$room->name}}')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="mt-4 flex justify-center">
            {{ $rooms->links() }}
        </div>
    </div>
@endsection
