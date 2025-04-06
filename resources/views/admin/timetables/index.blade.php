@extends('layouts.layout')

@section('content')
    <div class="container mx-auto p-6 mt-5">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold">Timetable List</h1>
            <a href="{{ route('admin.timetables_create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Create Timetable</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded mb-6">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="py-3 px-6 text-left">Group</th>
                        <th class="py-3 px-6 text-left">Generation</th>
                        <th class="py-3 px-6 text-left">Term</th>
                        <th class="py-3 px-6 text-left">Start Date</th>
                        <th class="py-3 px-6 text-left">Sessions</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @forelse($timetables as $timetable)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-4 px-6">
                                @if($timetable->studentGroup)
                                    {{ $timetable->studentGroup->name }}
                                @else
                                    <span class="text-red-500">Missing Group</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">{{ $timetable->year }}</td>
                            <td class="py-4 px-6">{{ $timetable->term }}</td>
                            <td class="py-4 px-6">{{ date('M d, Y', strtotime($timetable->start_date)) }}</td>
                            <td class="py-4 px-6">{{ $timetable->scheduleSessions->count() }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.timetables_show', $timetable->id) }}" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.timetables_edit', $timetable->id) }}" class="text-green-500 hover:text-green-700">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.timetables_destroy', $timetable->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this timetable?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-6 text-center text-gray-500">No timetables found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $timetables->links() }}
        </div>
    </div>
@endsection
