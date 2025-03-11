@extends('layouts.layout')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Groups</h1>

        <!-- Search & Filter -->
        <div class="flex items-center space-x-4 mb-4">
            <input type="text" id="search" placeholder="Search Groups..." class="pl-10 p-2 rounded-lg border w-64 bg-gray-100">

            <!-- Sorting -->
            <select id="sort" class="p-2 border rounded">
                <option value="name">Sort by Name</option>
                <option value="generation_year">Sort by Generation</option>
                <option value="department">Sort by Department</option>
            </select>

            <!-- Create New Button -->
            <a href="{{ route('admin.student-groups_create') }}" class="bg-blue-500 text-white p-2 rounded-lg">
                Create New
            </a>
        </div>

        <!-- Table -->
        <div id="table-container">
            @include('admin.student-groups.partials.table', ['groups' => $groups])
        </div>
        <!-- Pagination -->
        <div class="mt-4" id="pagination">
            {{ $groups->links() }}
        </div>
    </div>

    <!-- jQuery & AJAX Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let timer;
            // AJAX Search
            $(document).on("keyup", "#search", function () {
                let query = $(this).val();

                $.ajax({
                    url: "{{ route('admin.student-groups_search') }}", // ✅ Correct route
                    type: "GET",
                    data: { query: query },
                    success: function (response) {
                        $("#table-container").html(response.table); // ✅ Update table
                        $("#pagination").html(response.pagination); // ✅ Update pagination
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr);
                    }
                });
            });
            // AJAX Sorting
            $('#sort').on('change', function () {
                let query = $('#search').val();
                let sort = $(this).val();
                fetchGroups(query, sort);
            });

            // Function to Fetch Data via AJAX
            function fetchGroups(query, sort) {
                $.ajax({
                    url: "{{ route('admin.student-groups_search') }}",
                    type: "GET",
                    data: { query: query, sort: sort },
                    success: function (data) {
                        $('#group-results').html(data.table);
                        $('#pagination').html(data.pagination);
                    }
                });
            }

            // Delete Confirmation
            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();
                let form = $(this).closest("form");

                if (confirm("Are you sure you want to delete this group?")) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
