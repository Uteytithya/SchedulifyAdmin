@extends('layouts.layout')

@section('content')
    <div class="container mx-auto p-6 mt-5">
        <h1 class="text-2xl font-bold mb-4">Create Timetable</h1>

        <form action="{{ route('admin.timetables_store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <!-- Dropdown to select generation -->
                <label class="block text-sm font-medium">Select Generation</label>
                <select id="generation" name="generation" required class="w-full p-2 border rounded mt-1">
                    <option value="">Choose Generation</option>
                    @foreach($studentGroups->groupBy('generation_year') as $year => $groups)
                        <option value="{{ $year }}">Gen {{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown to select multiple groups based on the selected generation -->
            <div id="group-selection" style="display: none;">
                <label class="block text-sm font-medium mt-4">Select Groups</label>
                <select id="group" name="groups[]" class="w-full p-2 border rounded mt-1" multiple>
                    <!-- Options will be dynamically populated based on generation -->
                </select>
                <p class="text-gray-500 text-sm mt-1">Hold Ctrl (or Cmd on Mac) to select multiple groups </p>
            </div>

            <script>
                // JavaScript to handle dynamic selection of multiple groups based on generation
                document.getElementById('generation').addEventListener('change', function() {
                    const generation = this.value;
                    const groupSelection = document.getElementById('group-selection');
                    const groupDropdown = document.getElementById('group');

                    // Clear any existing group options
                    groupDropdown.innerHTML = '';

                    // If no generation is selected, hide the group dropdown
                    if (!generation) {
                        groupSelection.style.display = 'none';
                        return;
                    }

                    // Get the groups that belong to the selected generation
                    const groups = @json($studentGroups->groupBy('generation_year'));

                    if (groups[generation]) {
                        // Show the group dropdown
                        groupSelection.style.display = 'block';

                        // Populate the group dropdown with the correct groups
                        groups[generation].forEach(group => {
                            const option = document.createElement('option');
                            option.value = group.id;
                            option.textContent = group.department + ' - ' + group.name;
                            groupDropdown.appendChild(option);
                        });
                    } else {
                        groupSelection.style.display = 'none';
                    }
                });
            </script>

            <!-- Term Input -->
            <div>
                <label class="block text-sm font-medium">Term</label>
                <input type="text" name="term" class="w-full p-2 border rounded mt-1" required>
            </div>

            <!-- Courses (Checkbox max 6) -->
            <div class="mb-5 col-span-2">
                <label for="courses" class="block mb-2 text-md font-medium text-gray-900">Courses:</label>
                <select name="courses[]" id="courses" multiple
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}"
                            {{ (collect(old('courses'))->contains($course->id)) ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-gray-500 text-sm mt-1">Hold Ctrl (or Cmd on Mac) to select multiple courses. (Max: 6)</p>
            </div>

            <!-- Room Info (for Admin's reference only) -->
            <div>
                <label class="block text-sm font-medium mb-1">Available Rooms</label>
                <ul class="bg-gray-100 p-3 rounded">
                    @foreach($rooms as $room)
                        <li>{{ $room->name }} — Capacity: {{ $room->capacity }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Time Inputs -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Start Time</label>
                    <input type="time" name="start_time" class="w-full p-2 border rounded" value="08:30" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">End Time</label>
                    <input type="time" name="end_time" class="w-full p-2 border rounded" value="16:15" required>
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Start Date</label>
                    <input type="date" name="start_date" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">End Date</label>
                    <input type="date" name="end_date" class="w-full p-2 border rounded" required>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Generate Timetable
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const courseSelect = document.getElementById('courses');

        courseSelect.addEventListener('change', function () {
            const selectedOptions = [...this.selectedOptions];
            if (selectedOptions.length > 6) {
                alert('You can select up to 6 courses only.');
                // Deselect the last selected item
                selectedOptions[selectedOptions.length - 1].selected = false;
            }
        });
    </script>
@endsection
