<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentGroup;
use Illuminate\Validation\Rule;

class StudentsGroupController extends Controller
{
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->input('query');
            $sort = $request->input('sort', 'name');

            $groups = StudentGroup::when($query, function ($q) use ($query) {
                return $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('department', 'LIKE', "%{$query}%");
            })
                ->orderBy($sort, 'asc')
                ->paginate(5);

            $table = view('admin.student-groups.partials.table', compact('groups'))->render();
            $pagination = $groups->links()->render();

            return response()->json(['table' => $table, 'pagination' => $pagination]);
        }

        return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StudentGroup::query();

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Sorting
        if ($request->has('sort') && in_array($request->sort, ['name', 'generation_year', 'department'])) {
            $query->orderBy($request->sort, $request->order == 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // Default sorting
        }
        $groups = $query->paginate(5); // Pagination
        return view(('admin.student-groups.index'), compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.student-groups.create'); // No need to pass $room
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'unique:students_groups,name'],
            'generation_year' => ['required', 'integer'],
            'department' => ['required', 'string'],
        ]);

        StudentGroup::create($data);

        return redirect()->route('admin.student-groups_index')->with('success', 'Group Created!');
    }


    /**
     * Display the specified resource.
     */
    public function show(StudentGroup $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student_group = StudentGroup::findOrFail($id);
        return view('admin.student-groups.edit', compact('student_group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student_group = StudentGroup::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', Rule::unique('students_groups', 'name')->ignore($id)],
            'generation_year' => ['required','integer'],
            'department' => ['required','string'],
        ]);

        $student_group->update($data);

        return redirect()->route('admin.student-groups_index')->with('success', 'Group Updated Successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student_group = StudentGroup::findOrFail($id);
        $student_group->delete();

        return redirect()->route('admin.student-groups_index')->with('success', 'Group Deleted Successfully!');
    }

}
