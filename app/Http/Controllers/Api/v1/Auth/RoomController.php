<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\v1\BaseAPI;
use App\Services\RoomSV;

class RoomController extends BaseAPI
{
    protected $Room; // Service instance

    public function __construct(RoomSV $roomSV)
    {
        $this->Room = $roomSV;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $params = [];
            $params['order_by'] = $request->order_by;
            $params['filter_by'] = $request->filter_by;
            $params['search'] = $request->search;
            $params['columns'] = $request->columns;
            $data = $this->Room->getAllRooms($params);
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
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }
}
