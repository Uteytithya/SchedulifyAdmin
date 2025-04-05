@extends('layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl mt-20">
    <span class="text-md text-gray-500 flex gap-1 ">
        <a class="font-bold" href="{{route('admin.users.index')}}">User ></a>
        <p>Edit </p>
    </span>
    <h1 class="text-3xl font-semibold mt-2">Edit User</h1>

    <div class="mt-8">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-x-6 gap-y-4 bg-white shadow-sm p-6 rounded-lg">
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-md font-medium text-gray-900">Name :</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="p-2 w-full rounded-lg border border-gray-300 focus:border-2 focus:border-blue-500 outline-none" />
                    @error('name')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="block mb-2 text-md font-medium text-gray-900">Email :</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="p-2 w-full rounded-lg border border-gray-300 focus:border-2 focus:border-blue-500 outline-none" />
                    @error('email')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block mb-2 text-md font-medium text-gray-900">Password :</label>
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep current password"
                        class="p-2 w-full rounded-lg border border-gray-300 focus:border-2 focus:border-blue-500 outline-none" />
                    @error('password')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block mb-2 text-md font-medium text-gray-900">Role :</label>
                    <select id="role" name="role"
                        class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:border-2 outline-none">
                        <option disabled>Choose a role</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-5 col-span-2">
                    <label for="courses" class="block mb-2 text-md font-medium text-gray-900">Courses :</label>
                    <select name="courses[]" id="courses" multiple
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                {{ (collect(old('courses'))->contains($course->id) || (isset($user) && $user->courses->contains($course->id))) ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-sm mt-1">Hold Ctrl (or Cmd on Mac) to select multiple courses.</p>
                </div>


            </div>

            <button type="submit" class="focus:outline-none text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 mt-5">Update</button>
            <a href="{{ route('admin.users.index') }}" class="bg-red-500 text-white px-4 py-2 inline-block text-center rounded-md">Cancel</a>
        </form>
    </div>
</div>
@endsection
