@extends('layouts.layout')

@section('content')
    <div class="container mx-auto p-6 mt-5">
         <span class="text-md text-gray-500 flex gap-1 ">
            <a class="font-bold" href="{{route('admin.rooms_index')}}">Room></a>
            <p>Edit </p>
        </span>
        <h1 class="text-2xl font-bold mb-6">Edit Room</h1>

        <form action="{{ route('admin.rooms_update', $room->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <label class="block">Room Name:</label>
            <input type="text" name="name" value="{{ $room->name }}" required class="w-full p-2 border rounded">

            <label class="block">Floor:</label>
            <input type="number" name="floor" value="{{ $room->floor }}" required class="w-full p-2 border rounded">

            <label class="block">Capacity:</label>
            <input type="number" name="capacity" value="{{ $room->capacity }}" required class="w-full p-2 border rounded">

            <label class="block">Status:</label>
            <select name="is_active" class="w-full p-2 border rounded">
                <option value="1" {{ $room->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$room->is_active ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 mr-5 rounded-md">Update</button>
            <a href="{{ route('admin.rooms_index') }}" class="bg-red-500 text-white px-4 py-2 inline-block text-center rounded-md">Cancel</a>
        </form>
    </div>
@endsection
