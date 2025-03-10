<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseService
{
    protected function getLimit(Builder $query, int $limit)
    {
        return isset($limit) && $limit != -1 ? $query->paginate($limit) : $query->get();
    }

    public function create(array $params = array())
    {
        $query = $this->getQuery();

        if (isset($query)) {
            $data = $query->create($params);
            return $data;
        } else {
            throw new Exception('Query not found');
        }
    }

    public function update(array $params = array(), String $id = null)
    {
        $query = $this->getQuery();

        if (isset($query)) {
            $data = $query->where('id', $id)->first();
            if (isset($data)) {
                $data->update($params);
                return $data;
            } else {
                throw new Exception("Record " . $id . " not found in model " . $query->getModel()::class . "");
            }
        } else {
            throw new Exception('Query not found');
        }
    }

    public function getByGlobalId(String $id, $query)
    {
        if (isset($query)) {
            $data = $query->where('id', $id)->first();
            if (isset($data)) {
                return $data;
            } else {
                throw new Exception("Record " . $id . " not found in " . $query->getModel()::class ?? '' . "");
            }
        } else {
            throw new Exception('Query not found');
        }
    }

    public function getAll($params = null, $query = null)
    {
        if ($query === null) {
            $query = $this->getQuery();
            if (!$query) {
                throw new Exception('Query not found');
            }
        }

        $limit    = isset($params['limit']) && is_numeric($params['limit']) ? (int) $params['limit'] : 10;
        $page     = isset($params['page']) && is_numeric($params['page']) ? (int) $params['page'] : 1;
        $orderBy  = $params['order_by'] ?? 'asc';
        $filterBy = $params['filter_by'] ?? [];
        $search   = $params['search'] ?? null;
        $columns  = $params['columns'] ?? null;

        // Calculate the offset based on the page number and limit
        $offset = ($page - 1) * $limit;

        // Ensure ordering by 'created_at' exists
        if (!isset($query->getQuery()->orders)) {
            $query = $query->orderBy('created_at', $orderBy);
        }

        // Apply search filter
        if (!empty($search) && !empty($columns)) {
            if (is_array($columns)) {
                $query->where(function ($q) use ($columns, $search) {
                    foreach ($columns as $column) {
                        $q->orWhere($column, 'like', '%' . $search . '%');
                    }
                });
            } else {
                $query->where($columns, 'like', '%' . $search . '%');
            }
        }

        // Apply other filters
        foreach ($filterBy as $column => $value) {
            if (!empty($column) && $value !== null) {
                $query = $query->where($column, $value);
            }
        }

        // Count total records
        $total = $query->count();

        // If total records are less than offset, reset page to 1
        if ($total <= $offset) {
            $page = 1;
            $offset = 0;
        }

        // Apply limit and offset for pagination
        if ($limit > 0) {
            $query = $query->skip($offset)->take($limit);
        }

        // Retrieve the data for the current page
        $data = $query->get();

        return [$data, $total, $limit, $page];
    }


    // Get id by id
    public function getIdByGlobalId($modelName, $id)
    {
        $model = new $modelName();
        $query = $model->getQuery();

        if (isset($query)) {
            $data = $query->where('id', $id)->first();

            $dataId = $data ? $data->id : null;
            return $dataId;
        } else {
            throw new Exception('Query not found');
        }
    }

    // Activate and Deactivate a record
    public function activate($id, $query)
    {

        if (isset($query)) {
            $data = $query->where('id', $id)->first();
            $data->update([
                'is_active' => !$data->is_active
            ]);
            return $data;
        } else {
            throw new Exception('Query not found.');
        }
    }

    protected function getQuery()
    {
        return null;
    }
}
