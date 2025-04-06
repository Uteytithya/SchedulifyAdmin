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

            <!-- Term Input -->
            <div>
                <label class="block text-sm font-medium">Term</label>
                <input type="text" name="term" class="w-full p-2 border rounded mt-1" required>
            </div>

            <!-- Courses (Checkbox max 6) -->
            <div class="mb-5">
                <label class="block mb-2 text-md font-medium text-gray-900">Courses (Select up to 6):</label>
                <div class="selected-count text-sm text-gray-500 mb-2">Selected: <span id="selected-course-count">0</span>/6</div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto p-3 border border-gray-300 rounded-lg bg-white">
                    @foreach ($courses->sortBy('name') as $course)
                        <div class="flex items-start p-2 hover:bg-gray-50 rounded">
                            <input type="checkbox" name="courses[]" id="course-{{ $course->id }}"
                                value="{{ $course->id }}" class="course-checkbox mt-1"
                                {{ (collect(old('courses'))->contains($course->id)) ? 'checked' : '' }}>
                            <label for="course-{{ $course->id }}" class="ml-2 text-sm cursor-pointer">
                                <div class="font-medium">{{ $course->name }}</div>
                                <div class="text-xs text-gray-500">{{ $course->code ?? '' }}</div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Start Date</label>
                    <input type="date" name="start_date" class="w-full p-2 border rounded" required>
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
        // Course selection limit
        const courseCheckboxes = document.querySelectorAll('.course-checkbox');
        const selectedCountElement = document.getElementById('selected-course-count');

        function updateSelectedCount() {
            const selectedCount = document.querySelectorAll('.course-checkbox:checked').length;
            selectedCountElement.textContent = selectedCount;

            if (selectedCount >= 6) {
                courseCheckboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        checkbox.disabled = true;
                    }
                });
            } else {
                courseCheckboxes.forEach(checkbox => {
                    checkbox.disabled = false;
                });
            }
        }

        courseCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        // Initialize count
        updateSelectedCount();

        // Group selection
        const generationSelect = document.getElementById('generation');
        const groupSelectionDiv = document.getElementById('group-selection');
        const groupSelect = document.getElementById('group');

        generationSelect.addEventListener('change', function() {
            if (this.value) {
                // Show group selection
                groupSelectionDiv.style.display = 'block';

                // Populate groups based on generation
                fetch(`/api/groups-by-generation/${this.value}`)
                    .then(response => response.json())
                    .then(groups => {
                        groupSelect.innerHTML = '';
                        groups.forEach(group => {
                            const option = document.createElement('option');
                            option.value = group.id;
                            option.textContent = group.name;
                            groupSelect.appendChild(option);
                        });
                    });
            } else {
                groupSelectionDiv.style.display = 'none';
            }
        });
    </script>
@endsection
