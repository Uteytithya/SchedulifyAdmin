<?php

namespace App\Services;

use App\Models\ScheduleSession;
use Exception;

class ScheduleSV extends BaseService{
    protected function getQuery(){
       return ScheduleSession::query();
    }

    function getAllScheduleSessions($params){
       try{
        $data = $this->getAll($params);
        return $data;
       }catch(Exception $e){
        throw new Exception($e->getMessage(), $e->getCode());
       }

       
    }
}


