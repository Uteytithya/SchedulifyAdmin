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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $timetables = Timetables::with([
                'studentGroup',
                'scheduleSessions.room',
                'scheduleSessions.courseUser.course',
                'scheduleSessions.courseUser.user',
                'scheduleSessions.sessionType'
            ])->paginate(10); // Adjust pagination as needed

            return $this->successResponse($timetables, 'Get all timetables with sessions successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        try {
            $timetable = Timetables::with([
                'studentGroup',
                'scheduleSessions.room',
                'scheduleSessions.courseUser.course',
                'scheduleSessions.courseUser.user',
                'scheduleSessions.sessionType'
            ])->findOrFail($id);

            return $this->successResponse($timetable, 'Get timetable with sessions successfully');
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
