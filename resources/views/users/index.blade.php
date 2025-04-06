@extends('layouts.layout')

@section('content')
    @if(session('success'))
        <x-toast :message="session('success')" :type="'success'" />
    @endif

    <div class="container mx-auto p-6 mt-5">
        <!-- Breadcrumb -->
        <span class="text-md text-gray-500 flex gap-1">
            <p class="font-bold">Users ></p>
            <p>List</p>
        </span>

        <div class="flex justify-between items-center mb-6 mt-2">
            <h1 class="text-3xl font-semibold">User List</h1>
            <a href="{{ route('admin.users.create') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors">
                Create User
            </a>
        </div>

        <!-- Search -->
        <div class="flex items-center space-x-4 mb-4">
            <input
                type="text"
                id="search"
                placeholder="Search Users..."
                class="pl-10 p-2 rounded-lg border w-64 bg-gray-100"
            >
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto" id="user-table">
                <thead class="bg-gray-200">
                    <tr class="border-b border-gray-300">
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b border-gray-300">
                            <td class="px-4 py-4">{{ $user->name }}</td>
                            <td class="px-4 py-4">{{ $user->email }}</td>
                            <td class="px-4 py-4">{{ $user->role }}</td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline-block ml-2" id="delete-form-{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-500 hover:text-red-700" onclick="confirmDelete('{{ $user->id }}')">
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
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('search').addEventListener('input', function() {
            const searchQuery = this.value;

            fetch('{{ route("admin.users.search") }}?search=' + searchQuery)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#user-table tbody');
                    tableBody.innerHTML = '';
                    if (data.users.length > 0){
                        data.users.forEach(user => {
                            const row = document.createElement('tr');
                            row.classList.add('border-b', 'border-gray-300');
                            
                            row.innerHTML = `
                                <td class="px-4 py-4">${user.name}</td>
                                <td class="px-4 py-4">${user.email}</td>
                                <td class="px-4 py-4">${user.role}</td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline-block ml-2" id="delete-form-{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-500 hover:text-red-700" onclick="confirmDelete('{{ $user->id }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center">No users found</td></tr>';
                    }
                });
        });

        function confirmDelete(userId) {
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
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }
    </script>
@endsection
