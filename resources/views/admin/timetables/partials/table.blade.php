@foreach ($timetables as $timetable)
    <tr class="border-b">
        <td class="px-4 py-3">{{ $timetable->year }}</td>
        <td class="px-4 py-3">{{ $timetable->start_date }}</td>
        <td class="px-4 py-3">{{ $timetable->studentGroup->name }}</td>
        <td class="px-4 py-3 text-center">
            <a href="{{ route('admin.timetables_edit', $timetable->id) }}" class="text-blue-500">Edit</a>
        </td>
        <td class="px-4 py-3 text-center">
            <form action="{{ route('admin.timetables_destroy', $timetable->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 delete-btn">Delete</button>
            </form>
        </td>
    </tr>
@endforeach
