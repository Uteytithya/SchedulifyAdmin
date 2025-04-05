<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\BaseAPI;
use App\Models\Course;
use App\Services\CourseSV;
use Illuminate\Http\Request;

class CourseController extends BaseAPI
{

    protected $Course; // Service instance

    public function __construct(CourseSV $courseSV)
    {
        $this->Course= $courseSV;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $params = [];
            $params['order_by'] = request()->get('order_by');
            $params['filter_by'] = request()->get('filter_by');
            $params['search'] = request()->get('search');
            $params['columns'] = request()->get('columns');
            $data = $this->Course->getAllCourses($params);
            return $this->successResponse($data, 'Get all courses successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(course $course)
    {
        //
    }
}
