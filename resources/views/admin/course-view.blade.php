@extends('layouts.layout')
@section('content')
    <div class="container mx-6 px-4 py-6 mt-8 bg-white pr-6">
       

        <div class="container mx-auto px-4 py-6 max-w-7xl mt-20">
            <span class="text-md text-gray-500 flex gap-1 ">
                <p class="font-bold">Course ></p>
                <p>List </p>
            </span>

            <div class="flex justify-between items-center mb-6 mt-2">
                <h1 class="text-3xl font-semibold">Course List</h1>
                <a href="{{ route('admin.course_create') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors">
                    Create User
                </a>
            </div>

            {{-- search bar --}}
            <div class="flex items-center ml-auto space-x-4  mb-4">
                <form class="relative" action="" method="GET">
                    <i class="fa-solid fa-magnifying-glass absolute left-2 top-3 text-gray-400 "></i>
                    <input class="pl-10 bg-[#E9F5FE] p-2 rounded-lg w-64 mr-6" placeholder="Search" name="search">
                </form>
               
            </div>



            <div class="bg-white shadow-md rounded-lg overflow-hidden ">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200">
                        <tr class="border-b border-gray-300">
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Credit</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($courses->isEmpty())
                            <tr class="h-48">
                                <td class="px-4 py-4 text-center" colspan="4">No course found</td>
                            </tr>
                        @endif
                        @foreach ($courses as $course)
                            <tr class="border-b border-gray-300">
                                <td class="px-4 py-4">{{ $course->name }}</td>
                                <td class="px-4 py-4">{{ $course->credit }}</td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('admin.course_edit', $course->id) }}"
                                        class="text-blue-500 hover:text-blue-700"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('admin.course_delete_post', $course->id) }}" method="POST"
                                        class="inline-block ml-2" id="delete-form-{{ $course->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-500 hover:text-red-700"
                                            onclick="confirmDelete('{{ $course->id }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete(course) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-lg shadow-lg',
                },
                preConfirm: () => {
                    document.getElementById('delete-form-' + course).submit();
                }
            });
        }
    </script>
@endsection