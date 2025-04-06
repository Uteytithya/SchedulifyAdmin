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

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                            <th class="border border-gray-300 py-2 px-4 text-center">Time</th>
                            <th class="border border-gray-300 py-2 px-4 text-center">Monday</th>
                            <th class="border border-gray-300 py-2 px-4 text-center">Tuesday</th>
                            <th class="border border-gray-300 py-2 px-4 text-center">Wednesday</th>
                            <th class="border border-gray-300 py-2 px-4 text-center">Thursday</th>
                            <th class="border border-gray-300 py-2 px-4 text-center">Friday</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group sessions by start/end times
                            $timeBlocks = $timetable->scheduleSessions
                                ->groupBy(function ($session) {
                                    return date('H:i', strtotime($session->start_time)) . '-' .
                                           date('H:i', strtotime($session->end_time));
                                })
                                ->sortBy(function ($group, $timeBlock) {
                                    return explode('-', $timeBlock)[0]; // Sort by start time
                                });

                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                        @endphp

                        @forelse ($timeBlocks as $timeBlock => $sessions)
                            <tr>
                                <td class="border border-gray-300 py-2 px-4 text-center font-medium">
                                    {{ $timeBlock }}
                                </td>

                                @foreach ($days as $day)
                                    <td class="border border-gray-300 py-2 px-4">
                                        @php
                                            $daySession = $sessions->firstWhere('day', $day);
                                        @endphp

                                        @if ($daySession)
                                            <div class="mb-1">
                                                <div class="font-medium">{{ $daySession->courseUser->course->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $daySession->courseUser->user->name }}</div>
                                                <div class="text-sm text-gray-500">Room: {{ $daySession->room->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $daySession->sessionType->name }}</div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                @endforeach
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
    </div>
@endsection
