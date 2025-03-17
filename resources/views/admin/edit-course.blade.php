@extends('layouts.layout')
@section('content')
    <div class="container mx-auto px-4 py-6 max-w-7xl mt-20">
        <span class="text-md text-gray-500 flex gap-1 ">
            <a class="font-bold" href="/users">Course ></a>
            <p>Edit </p>
        </span>
        <h1 class="text-3xl font-semibold mt-2">Edit Course</h1>

        <div class="mt-8">
            <form action="{{ route('admin.course_edit', $course->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-x-6 gap-y-4 bg-white shadow-sm p-6 rounded-lg">
                    <div class="mb-5">
                        <label for="name" class="block mb-2 text-md font-medium text-gray-900">Name :</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $course->name) }}"
                            class="p-2 w-full rounded-lg border border-gray-300 focus:border-2 focus:border-blue-500 outline-none" />
                        @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block mb-2 text-md font-medium text-gray-900">Credit :</label>
                        <input type="text" name="email" id="email" value="{{ old('email', $course->credit) }}"
                            class="p-2 w-full rounded-lg border border-gray-300 focus:border-2 focus:border-blue-500 outline-none" />
                        @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>


                </div>

                <button type="submit"
                    class="focus:outline-none text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 mt-5">Update</button>
            </form>
        </div>
    </div>
@endsection
