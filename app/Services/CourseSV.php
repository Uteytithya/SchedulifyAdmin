<?php

namespace App\Services;

use App\Models\Course;
use Exception;

class CourseSV extends BaseService
{
    protected function getQuery()
    {
        return Course::query();
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


