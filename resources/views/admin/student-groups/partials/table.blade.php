@foreach($groups as $group)
    <tr class="border-b">
        <td class="p-2"><input type="checkbox"></td>
        <td class="p-2">{{ $group->name }}</td>
        <td class="p-2">{{ $group->generation_year }}</td>
        <td class="p-2">{{ $group->department }}</td>
        <td class="p-2 text-center">
            <a href="{{ route('admin.student-groups.edit', $group->id) }}" class="text-blue-500">
                <i class="fa-solid fa-pen"></i>
            </a>
        </td>
        <td class="p-2 text-center">
            <form action="{{ route('admin.student-groups.destroy', $group->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 delete-btn" data-id="{{ $group->id }}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
