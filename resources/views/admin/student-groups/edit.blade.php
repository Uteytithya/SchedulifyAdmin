@extends('layouts.layout')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Edit Student Group</h2>

        <form action="{{ route('admin.student-groups_update', $student_group->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name', $student_group->name) }}" class="border p-2 w-full">
                @error('name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium">Generation Year</label>
                <input type="number" name="generation_year" value="{{ old('generation_year', $student_group->generation_year) }}" class="border p-2 w-full">
                @error('generation_year')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium">Department</label>
                <input type="text" name="department" value="{{ old('department', $student_group->department) }}" class="border p-2 w-full">
                @error('department')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Update</button>
        </form>
    </div>
@endsection
