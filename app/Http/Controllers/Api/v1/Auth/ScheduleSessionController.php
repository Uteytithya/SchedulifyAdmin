<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Services\ScheduleSV;
use Illuminate\Http\Request;
use App\Models\ScheduleSession;
use App\Http\Controllers\Api\v1\BaseAPI;

class ScheduleSessionController extends BaseAPI
{

    /**
     * Display a listing of the resource.
     */
    protected $ScheduleSession; // Service instance

    public function __construct(ScheduleSV $scheduleSV)
    {
        $this->ScheduleSession = $scheduleSV;
    }
    public function index(Request $request)
    {
        try {
            $params = [];
            $params['timetable_id'] = $request->timetable_id;
            $params['course_user_id'] = $request->course_user_id;
            $params['room_id'] = $request->room_id;
            $params['day'] = $request->day;
            $params['start_time'] = $request->start_time;
            $params['end_time'] = $request->end_time;
            $params['status'] = $request->status;
            $params['session_type_id'] = $request->session_type_id;
            $data = $this->ScheduleSession->getAllScheduleSessions($params);
            return $this->successResponse($data, 'Get all Schedule successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getcode());
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
    public function show(ScheduleSession $session)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleSession $session)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScheduleSession $session)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduleSession $session)
    {
        //
    }
}
