@extends('layouts.layout')

@section('content')

        @if(session('success'))
            <x-toast :message="session('success')" :type="'success'" />
        @endif

    <div class="container mx-auto px-4 py-6 max-w-7xl mt-20">
        <span class="text-md text-gray-500 flex gap-1 ">
            <p class="font-bold">User ></p>
            <p>List </p>
        </span>
        
        <div class="flex justify-between items-center mb-6 mt-2">
            <h1 class="text-3xl font-semibold">User List</h1>
            <a href="{{ route('users.create') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors">
                Create User
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden ">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr class="border-b border-gray-300">
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users->isEmpty())
                        <tr class="h-48">
                            <td class="px-4 py-4 text-center" colspan="4">No users found</td>
                        </tr>
                    @endif
                    @foreach ($users as $user)
                        <tr class="border-b border-gray-300">
                            <td class="px-4 py-4">{{ $user->name }}</td>
                            <td class="px-4 py-4">{{ $user->email }}</td>
                            <td class="px-4 py-4">{{ $user->role }}</td>
                            <td class="px-4 py-4">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block ml-2" id="delete-form-{{ $user->id }}">
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
    </div>
@endsection

@section('scripts')
    <script>
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