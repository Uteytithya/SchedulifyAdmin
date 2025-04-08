<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;



class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::paginate(5);
        return view('admin.courses.course-view', ['courses' => $courses]);
    }

    //create a new course
    public function create(Request $request)
    {
        return view('admin.courses.create-course');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
            'credit' => 'required'
        ]);
        Course::create($incomingFields);
        return redirect()->route('admin.course');
    }

    /**
     * Display the specified resource.
     */
    public function show(course $course) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Request $request)
    {
        return view('admin.courses.edit-course', ['course' => $course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, course $course)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
            'credit' => 'required'
        ]);
        $course->update($incomingFields);
        return redirect()->route('admin.course');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(course $course)
    {
        $course->delete();
        return redirect()->route('admin.course');
    }
    public function search(Request $request)
    {
        $search = $request->input('search');

        // Fetch users based on the search query
        $courses = Course::where('name', 'like', "%{$search}%")->get();
        return response()->json([
            'courses' => $courses
        ]);
    }
}
