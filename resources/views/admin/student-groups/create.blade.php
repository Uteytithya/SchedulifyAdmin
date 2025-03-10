@extends('layouts.layout')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Create New Group</h1>

        <form action="{{ route('admin.student-groups.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Group Name</label>
                <input type="text" name="name" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Generation Year</label>
                <input type="number" name="generation_year" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Department</label>
                <input type="text" name="department" class="border p-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg">Save</button>
        </form>
    </div>
@endsection
