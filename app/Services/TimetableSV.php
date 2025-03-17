<?php

namespace App\Services;

use App\Models\Timetables;
use Exception;

class TimetableSV extends BaseService
{
    protected function getQuery()
    {
        return Timetables::query();
    }

    public function getAllTimetables(array $params = array())
    {
        try {
            $timetable = $this->getAll($params);
            return $timetable;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getTimetableById(String $id)
    {
        try {
            $timetable = $this->getByGlobalId($id, $this->getQuery());
            return $timetable;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
