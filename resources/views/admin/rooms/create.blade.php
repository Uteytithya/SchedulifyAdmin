@extends('layouts.layout')

@section('content')
    <div class="container mx-auto px-4 py-6 bg-white w-full max-w-6xl">
        <h1 class="text-2xl font-bold mb-6">Create Room</h1>

        <form action="{{ route('admin.rooms.store') }}" method="POST" class="space-y-4">
            @csrf

            <label class="block">Room Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-2 border rounded">

            <label class="block">Floor:</label>
            <input type="number" name="floor" value="{{ old('floor') }}" required class="w-full p-2 border rounded">

            <label class="block">Capacity:</label>
            <input type="number" name="capacity" value="{{ old('capacity') }}" required class="w-full p-2 border rounded">

            <label class="block">Status:</label>
            <select name="is_active" class="w-full p-2 border rounded">
                <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Create Room</button>
        </form>
    </div>
@endsection
