@extends('layouts.layout')

@section('content')

    @if(session('success'))
        <x-toast :message="session('success')" :type="'success'" />
    @endif

    <div class="container mx-auto p-6 mt-5">
        <!-- Breadcrumb -->
        <span class="text-md text-gray-500 flex gap-1">
            <p class="font-bold">Rooms ></p>
            <p>List</p>
        </span>

        <!-- Page Title & Create Button -->
        <div class="flex justify-between items-center mb-6 mt-2">
            <h1 class="text-3xl font-semibold">Room List</h1>
            <a href="{{ route('admin.rooms_create') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors">
                Create Room
            </a>
        </div>

        <!-- Search & Sorting -->
        <div class="flex items-center space-x-4 mb-4">
            <input type="text" id="search" placeholder="Search Rooms..." class="pl-10 p-2 rounded-lg border w-64 bg-gray-100">
            <select id="sort" class="p-2 border rounded">
                <option value="name">Sort by Name</option>
                <option value="floor">Sort by Floor</option>
                <option value="capacity">Sort by Capacity</option>
            </select>
        </div>

        <!-- Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                <tr class="border-b border-gray-300">
                    <th class="px-4 py-3 text-left">Room Name</th>
                    <th class="px-4 py-3 text-left">Floor</th>
                    <th class="px-4 py-3 text-left">Capacity</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Edit</th>
                    <th class="px-4 py-3 text-center">Delete</th>
                </tr>
                </thead>
                <tbody id="room-results">
                @include('admin.rooms.partials.table', ['rooms' => $rooms])
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4" id="pagination">
            {{ $rooms->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            let timer;

            // AJAX Search & Sort
            function fetchRooms() {
                let query = $('#search').val();
                let sort = $('#sort').val();

                $.ajax({
                    url: "{{ route('admin.rooms_search') }}",
                    type: "GET",
                    data: { query: query, sort: sort },
                    success: function (response) {
                        $("#room-results").html(response.table);
                        $("#pagination").html(response.pagination);
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr);
                    }
                });
            }

            // Search Event
            $('#search').on("keyup", function () {
                clearTimeout(timer);
                timer = setTimeout(fetchRooms, 300);
            });

            // Sorting Event
            $('#sort').on('change', fetchRooms);

            // Delete Confirmation
            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();
                let form = $(this).closest("form");

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        form.submit();
                    }
                });
            });
        });
    </script>

@endsection
