@extends('layouts.layout')

@section('content')
    <div class="container mx-auto p-6 mt-5">
        <h1 class="text-3xl font-semibold mb-6">Timetable Details</h1>

        <div class="bg-white shadow-md rounded p-4">
            <h2 class="text-2xl font-semibold mb-4">Group: {{ $timetable->studentGroup->name }}</h2>
            <p class="text-gray-600 mb-4">
                <strong>Year:</strong> {{ $timetable->year }}<br>
                <strong>Term:</strong> {{ $timetable->term }}<br>
                <strong>Start Date:</strong> {{ date('M d, Y', strtotime($timetable->start_date)) }}
            </p>

            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="border border-gray-300 py-2 px-4 text-center">Day</th>
                        <th class="border border-gray-300 py-2 px-4 text-center">Time</th>
                        <th class="border border-gray-300 py-2 px-4 text-center">Course</th>
                        <th class="border border-gray-300 py-2 px-4 text-center">Lecturer</th>
                        <th class="border border-gray-300 py-2 px-4 text-center">Room</th>
                        <th class="border border-gray-300 py-2 px-4 text-center">Session Type</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($timetable->scheduleSessions as $session)
                        <tr>
                            <td class="border border-gray-300 py-2 px-4 text-center capitalize">{{ $session->day }}</td>
                            <td class="border border-gray-300 py-2 px-4 text-center">
                                {{ date('H:i', strtotime($session->start_time)) }} - {{ date('H:i', strtotime($session->end_time)) }}
                            </td>
                            <td class="border border-gray-300 py-2 px-4 text-center">{{ $session->courseUser->course->name }}</td>
                            <td class="border border-gray-300 py-2 px-4 text-center">{{ $session->courseUser->user->name }}</td>
                            <td class="border border-gray-300 py-2 px-4 text-center">{{ $session->room->name }}</td>
                            <td class="border border-gray-300 py-2 px-4 text-center">{{ $session->sessionType->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-gray-300 py-2 px-4 text-center text-gray-500">
                                No sessions available for this timetable.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
