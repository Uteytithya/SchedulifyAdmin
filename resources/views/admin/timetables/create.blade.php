@extends('layouts.layout')

@section('content')
    <div class="container mx-auto p-6 mt-5">
        <h1 class="text-3xl font-semibold mb-6">Create Timetable</h1>

        @include('admin.timetables.partials.form', [
            'action' => route('admin.timetables_store'),
            'method' => 'POST',
            'timetable' => null,
            'studentGroups' => $studentGroups,
            'rooms' => $rooms,
            'courses' => $courses
        ])
    </div>
@endsection
