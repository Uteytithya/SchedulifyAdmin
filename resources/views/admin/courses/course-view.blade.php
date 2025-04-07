@extends('layouts.layout')
@section('content')
<div class="container mx-auto p-6 mt-5">
    <span class="text-md text-gray-500 flex gap-1 ">
        <p class="font-bold">Course ></p>
        <p>List </p>
    </span>

    <div class="flex justify-between items-center mb-6 mt-2">
        <h1 class="text-3xl font-semibold">Course List</h1>
        <a href="{{ route('admin.course_create') }}"
            class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors">
            Create Course
        </a>
    </div>

    {{-- search bar --}}
    {{--        <div class="flex items-center ml-auto space-x-4 mb-4">--}}
    {{--            <form class="relative flex items-center bg-[#E9F5FE] p-1 rounded-lg w-72 md:w-96 border-solid border-2">--}}
    {{--                <i class="fa-solid fa-magnifying-glass absolute left-3 text-gray-400"></i>--}}
    {{--                <input class="pl-10 pr-4 py-2 bg-transparent focus:outline-none w-full" placeholder="Search..."--}}
    {{--                    name="search">--}}
    {{--            </form>--}}
    {{--        </div>--}}

    <!-- Search -->
    <div class="flex items-center space-x-4 mb-4">
        <input type="text" id="search" placeholder="Search Course..."
            class="pl-10 p-2 rounded-lg border w-64 bg-gray-100">
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
            <tbody id="course-table-body">
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
                            class="text-blue-500 hover:text-blue-700"><i class="fa-solid fa-pen-to-square"></i></a>
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
    <!-- Pagination -->
    <div class="mt-4" id="pagination">
        {{ $courses->appends(request()->query())->links()}}
    </div>
</div>
@endsection
@section('scripts')
    <script>
        document.getElementById('search').addEventListener('input', function () {
            const searchQuery = this.value;
            fetch(`{{ route('admin.courses_search') }}?search=${searchQuery}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('course-table-body');
                    tableBody.innerHTML = '';

                    if (data.courses.length > 0) {
                        data.courses.forEach(course => {
                            const row = document.createElement('tr');
                            row.classList.add('border-b', 'border-gray-300');
                            row.innerHTML = `
                            <td class="px-4 py-4">${course.name}</td>
                            <td class="px-4 py-4">${course.credit}</td>
                            <td class="px-4 py-4">
                                <a href="/admin/courses/${course.id}/edit" class="text-blue-500 hover:text-blue-700">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="/admin/courses/${course.id}" method="POST"
                                      class="inline-block ml-2" id="delete-form-${course.id}">
                                    @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-500 hover:text-red-700"
                                    onclick="confirmDelete('${course.id}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        const noResultRow = document.createElement('tr');
                        noResultRow.innerHTML = `<td colspan="3" class="text-center py-4">No courses found.</td>`;
                        tableBody.appendChild(noResultRow);
                    }
                });
        });

        function confirmDelete(courseId) {
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
                    document.getElementById('delete-form-' + courseId).submit();
                }
            });
        }
    </script>
@endsection
