<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Room;
use App\Models\StudentGroup;
use Illuminate\Http\Request;
use App\Models\Timetables;
use App\Services\TimetableSV;
use Illuminate\Validation\Rule;

class TimetableController extends Controller
{

    protected $timetableService;

    public function __construct(TimetableSV $timetableService)
    {
        $this->timetableService = $timetableService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Timetables::with([
            'studentGroup',
            'scheduleSessions.room',
            'scheduleSessions.course',
            'scheduleSessions.sessionType',
            'scheduleSessions.lecturer'
        ]);

        // Search functionality
        if ($request->has('search')) {
            $query->where('year', 'LIKE', '%' . $request->search . '%')
                ->orWhereHas('studentGroup', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%');
                });
        }

        // Sorting
        if ($request->has('sort') && in_array($request->sort, ['year', 'start_date'])) {
            $query->orderBy($request->sort, $request->order == 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // Default sorting
        }

        $timetables = $query->paginate(5); // Pagination

        return view('admin.timetables.index', compact('timetables'));
    }

    /**
     * AJAX search function
     */
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->input('query');
            $sort = $request->input('sort', 'year');

            $timetables = Timetables::with([
                'studentGroup',
                'scheduleSessions.room',
                'scheduleSessions.course',
                'scheduleSessions.sessionType',
                'scheduleSessions.lecturer'
            ])->when($query, function ($q) use ($query) {
                return $q->where('year', 'LIKE', "%{$query}%")
                    ->orWhereHas('studentGroup', function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    });
            })
                ->orderBy($sort, 'asc')
                ->paginate(5);

            $table = view('admin.timetables.partials.table', compact('timetables'))->render();
            $pagination = $timetables->links()->render();

            return response()->json(['table' => $table, 'pagination' => $pagination]);
        }

        return abort(403);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $timetable = Timetables::with([
            'studentGroup',
            'scheduleSessions.room',
            'scheduleSessions.courseUser.course',
            'scheduleSessions.courseUser.user',
            'scheduleSessions.sessionType',

        ])->findOrFail($id);

        return view('admin.timetables.show', compact('timetable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $timetable = Timetables::with([
            'studentGroup',
            'scheduleSessions.room',
            'scheduleSessions.course',
            'scheduleSessions.sessionType',
            'scheduleSessions.lecturer'
        ])->findOrFail($id);

        $studentGroups = StudentGroup::all();
        $rooms = Room::all();
        $courses = Course::all();

        return view('admin.timetables.edit', compact('timetable', 'studentGroups', 'rooms', 'courses'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $studentGroups = StudentGroup::all(); // We’ll use this to get both department and generation
        $rooms = Room::all();
        $courses = Course::all();
        return view('admin.timetables.create', compact('studentGroups', 'rooms', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'generation' => 'required|integer',
            'term' => 'required|integer|between:1,3',
            'courses' => 'required|array|max:6',
            'start_date' => 'required|date'
        ]);
        try {
            $this->timetableService->generateTimetable($validatedData);
            return redirect()->route('admin.timetables_index')
                ->with('success', 'Timetable generated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error generating timetable: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $timetable = Timetables::findOrFail($id);

        $data = $request->validate([
            'group_id' => ['required', Rule::exists('student_groups', 'id')],
            'year' => ['required', 'integer'],
            'start_date' => ['required', 'date'],
        ], [
            'group_id.required' => 'The Group ID is required.',
            'group_id.exists' => 'The selected Group ID is invalid.',
            'year.required' => 'Please provide a year.',
            'start_date.required' => 'Please provide a valid start date.',
        ]);

        $timetable->update($data);

        return redirect()->route('admin.timetables_index')->with('success', 'Timetable Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $timetable = Timetables::findOrFail($id);
        $timetable->delete();

        return redirect()->route('admin.timetables_index')->with('success', 'Timetable Deleted Successfully!');
    }
}
