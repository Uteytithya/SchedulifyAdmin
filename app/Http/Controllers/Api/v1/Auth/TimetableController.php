<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\BaseAPI;
use App\Models\Timetables;
use App\Services\TimetableSV;
use Illuminate\Http\Request;

class TimetableController extends BaseAPI
{
    private $TimetableService;
    public function __construct()
    {
        $this->TimetableService = new TimetableSV();
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
            $data = $this->TimetableService->getAllTimetables($params);
            return $this->successResponse($data, 'Get all timetable successfully');
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
    public function show(Timetables $timetable, String $id)
    {
        try {
            $data = $this->TimetableService->getTimetableByID($id);
            return $this->successResponse($data, 'Get timetable successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timetables $timetable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timetables $timetable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timetables $timetable)
    {
        //
    }
}
