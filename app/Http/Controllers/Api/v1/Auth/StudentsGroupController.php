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

    public function show(String $id)
    {
        try {
            // Fetch the student group by ID with its timetable and related data
            $studentGroup = StudentGroup::with('timetable.scheduleSessions.courseUser.course', 'timetable.scheduleSessions.room')
                ->findOrFail($id);

            // Format the response data
            $data = [
                'id' => $studentGroup->id,
                'name' => $studentGroup->name,
                'generation_year' => $studentGroup->generation_year,
                'department' => $studentGroup->department,
                'timetable' => $studentGroup->timetable ? [
                    'id' => $studentGroup->timetable->id,
                    'year' => $studentGroup->timetable->year,
                    'term' => $studentGroup->timetable->term,
                    'schedule_sessions' => $studentGroup->timetable->scheduleSessions->map(function ($session) {
                        return [
                            'day' => ucfirst($session->day),
                            'start_time' => $session->start_time,
                            'end_time' => $session->end_time,
                            'course' => $session->courseUser->course->name ?? null,
                            'room' => $session->room->name ?? null,
                        ];
                    }),
                ] : null,
            ];

            return $this->successResponse($data, "Get student group by ID successfully");
        }catch (\Exception $e) {
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
