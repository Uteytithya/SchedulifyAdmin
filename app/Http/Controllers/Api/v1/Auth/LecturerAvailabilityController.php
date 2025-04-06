<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\BaseAPI;
use App\Models\LecturerAvailability;
use Illuminate\Http\Request;

class LecturerAvailabilityController extends BaseAPI
{
    protected $LecturerAvailability;

    public function __construct(LecturerAvailability $lecturerAvailabilitySV)
    {
        $this->LecturerAvailability = $lecturerAvailabilitySV;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $params = [];
            $params['order_by'] = request()->order_by;
            $params['filter_by'] = request()->filter_by;
            $params['search'] = request()->search;
            $params['columns'] = request()->columns;
            $data = $this->LecturerAvailability->getAll($params);
            return $this->successResponse($data, 'Get all Lecturer Availability successfully');
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
        try {
            $params = [];
            $params['lecturer_id'] = request()->lecturer_id;
            $params['course_id'] = request()->course_id;
            $params['day'] = request()->day;
            $params['start_time'] = request()->start_time;
            $params['end_time'] = request()->end_time;
            $data = $this->LecturerAvailability->createLecturerAvailability($params);
            return $this->successResponse($data, 'Get all Lecturer Availability successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LecturerAvailability $lecturerAvailability)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LecturerAvailability $lecturerAvailability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LecturerAvailability $lecturerAvailability)
    {
        try {
            $params = [];
            $params['lecturer_id'] = request()->lecturer_id;
            $params['course_id'] = request()->course_id;
            $params['day'] = request()->day;
            $params['start_time'] = request()->start_time;
            $params['end_time'] = request()->end_time;
            $data = $this->LecturerAvailability->updateLecturerAvailability($params, $lecturerAvailability->id);
            return $this->successResponse($data, 'Get all Lecturer Availability successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LecturerAvailability $lecturerAvailability)
    {
        try {
            $data = $this->LecturerAvailability->deleteLecturerAvailability($lecturerAvailability->id);
            return $this->successResponse($data, 'Get all Lecturer Availability successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
