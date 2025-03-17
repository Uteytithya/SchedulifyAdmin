@foreach($groups as $group)
    <tr class="border-b">
        <td class="p-4"><input type="checkbox"></td>
        <td class="p-4">{{ $group->name }}</td>
        <td class="p-4">{{ $group->generation_year }}</td>
        <td class="p-4">{{ $group->department }}</td>
        <td class="p-4 text-center">
            <a href="{{ route('admin.student-groups_edit', $group->id) }}" class="text-blue-500 hover:text-blue-700">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        </td>
        <td class="p-4 text-center">
            <form action="{{ route('admin.student-groups_destroy', $group->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 delete-btn">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
