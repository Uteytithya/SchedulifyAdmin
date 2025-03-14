@foreach($rooms as $room)
    <tr class="border-b hover:bg-gray-50">
        <td class="py-3 px-6">{{ $room->name }}</td>
        <td class="py-3 px-6">Floor {{ $room->floor }}</td>
        <td class="py-3 px-6">{{ $room->capacity }} people</td>
        <td class="py-3 px-6">
            @if ($room->is_active)
                <span class="px-3 py-1 text-green-700 bg-green-200 rounded-full text-sm">Active</span>
            @else
                <span class="px-3 py-1 text-red-700 bg-red-200 rounded-full text-sm">Inactive</span>
            @endif
        </td>
        <td class="py-3 px-6 text-center">
            <a href="{{ route('admin.rooms_edit', ['room' => $room->id]) }}" class="text-blue-500 hover:text-blue-700">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('admin.rooms_destroy', ['room' => $room->id]) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 delete-btn">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
