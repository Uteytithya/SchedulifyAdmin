<form action="{{ $action }}" method="POST" class="space-y-4">
    @csrf
    @if(isset($method))
        @method($method)
    @endif

    <!-- Student Group Dropdown -->
    <div>
        <label for="group_id" class="block text-sm font-medium text-gray-700">Student Group</label>
        <select name="group_id" id="group_id" class="mt-1 block w-full border rounded p-2">
            <option value="">Select Group</option>
            @foreach($studentGroups as $group)
                <option value="{{ $group->id }}" {{ (isset($timetable) && $timetable->group_id == $group->id) ? 'selected' : '' }}>
                    {{ $group->name }} ({{ $group->department }} - {{ $group->generation_year }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- Academic Year -->
    <div>
        <label for="year" class="block text-sm font-medium text-gray-700">Academic Year</label>
        <input type="text" name="year" id="year" value="{{ old('year', $timetable->year ?? '') }}" class="mt-1 block w-full border rounded p-2">
    </div>

    <!-- Start Date -->
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $timetable->start_date ?? '') }}" class="mt-1 block w-full border rounded p-2">
    </div>

    <!-- Room Dropdown -->
    <div>
        <label for="room_id" class="block text-sm font-medium text-gray-700">Room</label>
        <select name="room_id" id="room_id" class="mt-1 block w-full border rounded p-2">
            <option value="">Select Room</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}" {{ (isset($timetable) && $timetable->room_id == $room->id) ? 'selected' : '' }}>
                    {{ $room->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Courses Multi-select -->
    <div>
        <label for="course_ids" class="block text-sm font-medium text-gray-700">Courses</label>
        <select name="course_ids[]" id="course_ids" multiple class="mt-1 block w-full border rounded p-2">
            @foreach($courses as $course)
                <option value="{{ $course->id }}"
                        @if(isset($timetable) && $timetable->courses && in_array($course->id, $timetable->courses->pluck('id')->toArray()))
                            selected
                    @endif
                >
                    {{ $course->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ $buttonText ?? 'Save' }}
        </button>
    </div>
</form>
