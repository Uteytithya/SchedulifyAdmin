<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Validation\Rule;
class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::query();

        if ($request->has('query')) {
            $query->where('name', 'like', '%' . $request->query . '%');
        }

        if ($request->has('sort')) {
            $query->orderBy($request->sort, 'asc');
        } else {
            $query->orderBy('name', 'asc');
        }

        $rooms = $query->paginate(2);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.rooms.partials.table', compact('rooms'))->render(),
                'pagination' => $rooms->links()->render(),
            ]);
        }

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rooms.create'); // No need to pass $room
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => ['required', Rule::unique('rooms', 'name')],
            'floor' => ['required'],
            'capacity' => ['required', 'integer'],
            'is_active' => ['required', 'boolean']
        ]);

        Room::create($incomingFields);

        return redirect()->route('admin.rooms_index')->with('success', 'Room created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'name' => ['required', Rule::unique('rooms', 'name')],
            'floor' => ['required'],
            'capacity' => ['required', 'integer'],
            'is_active' => ['required', 'boolean']
        ]);

        $room->update($request->all());

        return redirect()->route('admin.rooms_index')->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms_index')->with('success', 'Room deleted successfully!');
    }
    public function search(Request $request)
    {
        $query = Room::query();

        // Search by name
        if ($request->has('query') && !empty($request->query('query'))) {
            $query->where('name', 'like', '%' . $request->query('query') . '%');
        }

        // Sorting
        if ($request->has('sort') && in_array($request->sort, ['name', 'floor', 'capacity'])) {
            $query->orderBy($request->sort, 'asc');
        } else {
            $query->orderBy('name', 'asc'); // Default sorting
        }

        // Paginate results
        $rooms = $query->paginate(2);

        return response()->json([
            'table' => view('admin.rooms.partials.table', compact('rooms'))->render(),
            'pagination' => $rooms->links()->render(),
        ]);
    }
}
