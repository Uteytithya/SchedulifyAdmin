<?php

namespace App\Services;

use Auth;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

abstract class BaseService {
    protected function getLimit(Builder $query, int $limit)
    {
        return isset($limit) && $limit !=-1 ? $query->paginate($limit) : $query->get();
    }

    public function create(array $params = array())
    {
        $query = $this->getQuery();

        if(isset($query)){
            $data = $query->create($params);
            return $data;
        } else {
            throw new Exception('Query not found');
        }

    }

    public function update(array $params = array(), String $global_id = null)
    {
        $query = $this->getQuery();

        if(isset($query)){
            $data = $query->where('global_id', $global_id)->first();
            if(isset($data)){
                $data->update($params);
                return $data;
            } else {
                throw new Exception("Record ".$global_id." not found in model ".$query->getModel()::class."");
            }
        } else {
            throw new Exception('Query not found');
        }
    }

    public function getByGlobalId(String $global_id)
    {
        $query = $this->getQuery();

        if(isset($query)){
            $data = $query->where('global_id', $global_id)->first();
            if(isset($data)){
                return $data;
            } else {
                throw new Exception("Record ".$global_id." not found in ".$query->getModel()::class ?? ''."");
            }
        } else {
            throw new Exception('Query not found');
        }
    }

    public function getAll($params = null)
    {
        $query = $this->getQuery();

        $limit = $params['limit'] ?? 10;
        $page = $params['page'] ?? 1;
        $orderBy = $params['order_by'] ?? 'asc';
        $filterBy = $params['filter_by'] ?? null;
        $search = $params['search'] ?? null;
        $columns = $params['columns'] ?? null;

        // Calculate the offset based on the page number and limit
        $offset = ($page - 1) * $limit;

        // Order by created_at desc by default
        $query = $query->orderBy('created_at', $orderBy);

        if (isset($search)) {
            $query = $query->where($columns, 'like', '%' . $search . '%');
        }

        // Apply other filters
        if (isset($filterBy)) {
            foreach ($filterBy as $column => $value) {
                $query = $query->where($column, $value);
            }
        }

        // Count total records without applying limit and offset
        $total = $query->count();

        // Apply limit and offset for pagination
        $query = $query->skip($offset)->take($limit);

        // Retrieve the data for the current page
        $data = $query->get();

        return $data;
    }

    // Get id by global_id
    public function getIdByGlobalId($modelName, $global_id) {
        $model = new $modelName();
        $query = $model->getQuery();

        if(isset($query)){
            $data = $query->where('global_id', $global_id)->first();

            $dataId = $data ? $data->id : null;
            return $dataId;
        }
        else {
            throw new Exception('Query not found');
        }
    }

    // Activate and Deactivate a record
    public function activate($global_id, bool $status) {
        $query = $this->getQuery();

        if (isset($query)) {
            $data = $query->where('global_id', $global_id)->first();
            $data->update([
                'active' => $status
            ]);
            return $data;
        }
        else {
            throw new Exception('Query not found.');
        }
    }

    protected function getQuery()
    {
        return null;
    }
}
