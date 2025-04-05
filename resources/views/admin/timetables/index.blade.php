@extends('layouts.layout')

@section('content')
    <div class="container mx-auto p-6 mt-5">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold">Timetable List</h1>
            <a href="{{ route('admin.timetables_create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Create Timetable</a>
        </div>

        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="py-2">Group</th>
                <th class="py-2">Year</th>
                <th class="py-2">Start Date</th>
                <th class="py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($timetables as $timetable)
                <tr>
                    <td class="border px-4 py-2">{{ $timetable->studentGroup->name }}</td>
                    <td class="border px-4 py-2">{{ $timetable->year }}</td>
                    <td class="border px-4 py-2">{{ $timetable->start_date }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('admin.timetables_edit', $timetable->id) }}" class="text-blue-500">Edit</a>
                        <form action="{{ route('admin.timetables_destroy', $timetable->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $timetables->links() }}
    </div>
@endsection
