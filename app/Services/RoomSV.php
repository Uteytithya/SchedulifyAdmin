<?php

namespace App\Services;

use App\Models\Room;
use Exception;

class RoomSV extends BaseService 
{
    protected function getQuery()
    {
        return Room::query();
    }

    public function getAllRooms($params)
    {
        try {
            $rooms = $this->getAll($params);
            return $rooms;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    } 

}


