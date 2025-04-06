<?php

namespace App\Services;

use App\Models\LecturerAvailability;
use App\Models\SessionRequest;
use App\Models\Timetables;
use Exception;

class LecturerAvailabilitySV extends BaseService
{
    protected function getQuery()
    {
        return LecturerAvailability::query();
    }

    public function getAllLecturerAvailability($params)
    {
        try {
            $lecturerAvailability = $this->getAll($params);
            return $lecturerAvailability;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function createLecturerAvailability($params)
    {
        try {
            $lecturerAvailability = $this->create($params);
            return $lecturerAvailability->fresh(['lecturer', 'scheduleSessions.room', 'scheduleSessions.course', 'scheduleSessions.sessionType']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateLecturerAvailability($params, $id)
    {
        try {
            $lecturerAvailability = $this->update($params, $id);
            return $lecturerAvailability->fresh(['lecturer', 'scheduleSessions.room', 'scheduleSessions.course', 'scheduleSessions.sessionType']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
    public function deleteLecturerAvailability($id)
    {
        try {
            $lecturerAvailability = LecturerAvailability::findOrFail($id);
            $lecturerAvailability->delete();
            return $lecturerAvailability;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

}
