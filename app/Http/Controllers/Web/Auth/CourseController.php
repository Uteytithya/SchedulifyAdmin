<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\course;



class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showCreate(){
        return view ('admin.create-course');
    }
    

    //create a new course
    public function create(Request $request)
    {
        $incomingFields = $request->validate([
            'name'=>'required',
            'credit'=>'required'
        ]);
        Course::create($incomingFields);
        return redirect()->route('admin.course');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(course $course)
    {
        $courses=Course::all();
        return view('admin.course-view',['courses'=>$courses]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course,Request $request)
    {
        return view('admin.edit-course',['course'=>$course]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, course $course)
    {
        $incomingFields = $request->validate([
            'name'=>'required',
            'credit'=>'required'
        ]);
        $course->update($incomingFields);
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(course $course)
    {
        $course->delete();
        return redirect()->route('admin.course');
    }
}
