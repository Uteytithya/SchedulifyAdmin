<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\BaseAPI;
use App\Models\SessionRequest;
use App\Services\RequestSV;
use Exception;
use Illuminate\Http\Request;


class SessionRequestController extends BaseAPI
{
    private $SessionRequestService;

    public function __construct()
    {
        $this->SessionRequestService = new RequestSV();
    }

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
            $params['user_id'] = $request->user_id;
            $params['session_type_id'] = $request->session_type_id;
            $params['course_id'] = $request->course_id;
            $params['timetable_id'] = $request->timetable_id;
            $params['requested_date'] = $request->requested_date;
            $params['new_start_time'] = $request->new_start_time;
            $params['new_end_time'] = $request->new_end_time;
            $params['request_type'] = $request->request_type;
            $params['reason'] = $request->reason;
            $params['status'] = $request->status;
            $data = $this->SessionRequestService->createRequest($params);
            return $this->successResponse($data, "Create Request Successfully!");
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SessionRequest $SessionRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SessionRequest $SessionRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SessionRequest $SessionRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SessionRequest $SessionRequest)
    {
        //
    }
}
