<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * model
     *
     * @var mixed
     */
    protected $model;

    /**
     * Create new controller instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * @return mixed
     */
    abstract public function getModel();

    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * @var mixed $with
     */
    protected $with;

    /**
     * @param array<int,string> $relations
     * @return $this
     */
    public function with($relations)
    {
        $this->model->with($relations);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @param array<mixed,mixed> $conditions
     * @param array<string,string> $orderConditions
     * @return mixed
     */
    public function find(array $conditions = [], array $orderConditions = [])
    {
        $result = $this->model->where($conditions);
        if (!empty($orderConditions)) {
            foreach ($orderConditions as $orderCondition => $direction) {
                $result->orderBy($orderCondition, $direction);
            }
        }
        return $result->get();
    }

    /**
     * @param array<mixed,mixed> $conditions
     * @param array<int,array<string,string>> $orderConditions
     * @return mixed
     */
    public function queryBuilderByCondition(array $conditions = [], array $orderConditions = [])
    {
        $result = $this->model->where($conditions);
        if (!empty($orderConditions)) {
            foreach ($orderConditions as $orderCondition) {
                $result->orderBy($orderCondition['orderBy'], $orderCondition['orderDirection']);
            }
        }
        return $result;
    }

    /**
     * Create a record
     *
     * @param array<mixed,mixed> $attributes
     * @return mixed
     */
    public function create(array $attributes = [])
    {
        return $this->model->create($attributes);
    }


    /**
     * Buck insert multiple record
     * @param array<mixed> $records
     * @return bool
     */
    public function insert(array $records)
    {
        return $this->model->insert($records);
    }

    /**
     * @inheritdoc
     */
    public function update($id, array $attributes = [])
    {
        return $this->model->where('id', $id)->update($attributes);
    }

    /**
     * @inheritdoc
     */
    public function delete(int $id)
    {
        $result = $this->findById($id);
        if ($result) {
            try {
                $delete = $result->delete();
                if (!$delete) {
                    throw new Exception();
                }
                return true;
            } catch (Exception $ex) {
                Log::error('delete: ' . $ex);
                return false;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function findCondition($whereConditions = [], $selectColumns = [])
    {
        $data = $this->model;
        foreach ($whereConditions as $condition) {
            $data = $data->where($condition[0], $condition[1], $condition[2]); // [field, operator, value]
        }
        foreach ($selectColumns as $key => $item) {
            $data = $data->addselect($item);
        }

        return $data->get();
    }

    /**
     * Find a specific record by its ID
     *
     * @param int|string $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }


    /**
     * Find a specific record that matches a given conditions
     *
     * @param array<mixed,mixed> $conditions
     * @return mixed
     */
    public function findOne(array $conditions)
    {
        return $this->model->where($conditions)->first();
    }

    /**
     * @param array<int,string> $key
     * @param array<mixed,mixed> $attributes
     * @return mixed
     */
    public function upsert(array $key, array $attributes = [])
    {
        return  $this->model->upsert([$attributes], [$key]);
    }

    /**
     * Buck upsert multiple record
     * @param array<mixed> $records
     * @param array<int, string> $keys
     * @return void
     */
    public function batchUpsert($records, $keys)
    {
        $this->model->upsert($records, $keys);
    }

    public function updateCondition($whereConditions = [], $updateData = [])
    {
        $result = $this->model;
        foreach ($whereConditions as $condition) {
            $result = $result->where($condition[0], $condition[1], $condition[2]); // [field, operator, value]
        }
        if ($result) {
            return $result->update($updateData);
        }

        return 0;
    }

    public function countCondition($whereConditions = [])
    {
        $data = $this->model;
        foreach ($whereConditions as $key => $item) {
            $data = $data->where($key, $item);
        }
        $data = $data->get();
        return count($data->toArray());
    }

    /**
     * @return mixed
     */
    public function cursor()
    {
        return $this->model->cursor();
    }

    /**
     * @param array $conditions
     * @return mixed
     */
    public function where(array $conditions)
    {
        return  $this->model->where($conditions);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function destroy($data)
    {
        return $this->model->destroy($data);
    }

    /**
     * @param array $condition
     * @return mixed
     */
    public function findByConditions($condition = [])
    {
        return $this->model->where($condition)->get();
    }

    /**
     * @return mixed
     */
    public function truncateOldData()
    {
        return $this->model->query()->truncate();
    }
}
