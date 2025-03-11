<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\StudentGroup;
use App\Services\StudentGroupSV;
use App\Services\TimetableSV;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\v1\BaseAPI;

class StudentsGroupController extends BaseAPI
{
    private $StudentGroupService;
    public function __construct()
    {
        $this->StudentGroupService = new StudentGroupSV();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $params = [];
            // $params['status'] = $request->boolean("status");
            $params['order_by'] = $request->order_by;
            $params['filter_by'] = $request->filter_by;
            $params['search'] = $request->search;
            $params['columns'] = $request->columns;
            $data = $this->StudentGroupService->getAllStudentGroups($params);
            return $this->successResponse($data, 'Get all student group successfully');
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
    public function show(StudentGroup $students_group, String $id)
    {
        try {
            $data = $this->StudentGroupService->getStudentGroupById($id);
            return $this->successResponse($data, "get student group by id successfully");
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentGroup $students_group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentGroup $students_group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentGroup $students_group)
    {
        //
    }
}
