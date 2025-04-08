@extends('layouts.layout')
@section('content')
    <div class="container mx-auto px-4 py-6 max-w-7xl mt-20">
        <span class="text-md text-gray-500 flex gap-1 ">
            <a class="font-bold" href="/users">User ></a>
            <p>Create </p>
        </span>
        <h1 class="text-3xl font-semibold mt-2">Create User</h1>

        <div class="mt-8">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-x-6 gap-y-4 bg-white shadow-sm p-6 rounded-lg">
                    <div class="mb-5">
                        <label for="name" class="block mb-2 text-md font-medium text-gray-900">Name :</label>
                        <input type="text" id="name" name="name"
                            class="p-2 w-full rounded-lg border border-gray-300 focus:border-2 focus:border-blue-500 outline-none" />
                        @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="email" class="block mb-2 text-md font-medium text-gray-900">Email :</label>
                        <input type="email" name="email" id="email"
                            class="p-2 w-full rounded-lg border border-gray-300 focus:border-2 focus:border-blue-500 outline-none" />
                        @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block mb-2 text-md font-medium text-gray-900">Password :</label>
                        <input type="password" name="password" id="password"
                            class="p-2 w-full rounded-lg border border-gray-300 focus:border-2 focus:border-blue-500 outline-none" />
                        @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block mb-2 text-md font-medium text-gray-900">Role :</label>
                        <select id="role" name="role"
                            class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 focus:border-2 outline-none">
                            <option value="" disabled selected>Choose a role</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-5 col-span-2">
                        <label for="courses" class="block mb-2 text-md font-medium text-gray-900">Courses (Select up to
                            2):</label>
                        <div class="selected-count text-sm text-gray-500 mb-2">Selected: <span
                                id="selected-course-count">0</span>/2</div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto p-3 border border-gray-300 rounded-lg bg-white">
                            @foreach ($courses->sortBy('name') as $course)
                                <div class="flex items-start p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" name="courses[]" id="course-{{ $course->id }}"
                                        value="{{ $course->id }}" class="course-checkbox mt-1"
                                        {{ collect(old('courses'))->contains($course->id) ? 'checked' : '' }}>
                                    <label for="course-{{ $course->id }}" class="ml-2 text-sm cursor-pointer">
                                        <div class="font-medium">{{ $course->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $course->code ?? '' }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="submit"
                    class="focus:outline-none text-white bg-blue-600 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 mt-5">Create</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    @push('scripts')
        <script>
            // Course selection limit
            const courseCheckboxes = document.querySelectorAll('.course-checkbox');
            const selectedCountElement = document.getElementById('selected-course-count');

            function updateSelectedCount() {
                const selectedCount = document.querySelectorAll('.course-checkbox:checked').length;
                selectedCountElement.textContent = selectedCount;

                if (selectedCount >= 2) {
                    courseCheckboxes.forEach(checkbox => {
                        if (!checkbox.checked) {
                            checkbox.disabled = true;
                        }
                    });
                } else {
                    courseCheckboxes.forEach(checkbox => {
                        checkbox.disabled = false;
                    });
                }
            }

            courseCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Initialize count
            updateSelectedCount();
        </script>
    @endpush
