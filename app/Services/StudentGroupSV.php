<?php

namespace App\Services;
use App\Models\StudentGroup;
use Exception;

class StudentGroupSV extends BaseService
{
    protected function getQuery()
    {
        return StudentGroup::query();
    }
    public function getAllStudentGroups(array $params = array())
    {
        try {
            $student_groups = $this->getAll();
            return $student_groups;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
    public function getStudentGroupById(String $id)
    {
        try {
            $student_group = $this->getByGlobalId($id, $this->getQuery());
            return $student_group;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
