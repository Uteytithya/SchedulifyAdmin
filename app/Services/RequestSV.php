<?php

namespace App\Services;

use App\Models\SessionRequest;
use App\Models\Timetables;
use Exception;

class RequestSV extends BaseService
{
    protected function getQuery()
    {
        return SessionRequest::query();
    }
    public function createRequest($params){
        try {
            $request = $this->create($params);
            return $request->fresh(['user_id', 'session_type_id', 'course_id', 'timetable_id', 'requested_date', 'new_start_time', 'new_end_time', 'reason', 'status', 'created_at']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

}
