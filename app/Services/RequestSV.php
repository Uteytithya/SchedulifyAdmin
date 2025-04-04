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
            return $request->fresh(['user', 'sessionType', 'timetable']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

}
