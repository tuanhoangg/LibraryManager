<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get all
     * @return mixed
     */
    public function getAll();

    /**
     * Find all records that match a given conditions
     *
     * @param array<mixed,mixed> $conditions
     * @return Model
     */
    public function find(array $conditions = []);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes = []);

    /**
     * Update a record
     *
     * @param int $id
     * @param array<mixed,mixed> $attributes
     * @return bool
     */
    public function update(int $id, array $attributes = []);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param array<mixed> $whereConditions
     * @param array<string> $selectColumns
     * @return mixed
     */
    public function findCondition($whereConditions = [], $selectColumns = []);

    /**
     * @param array<mixed> $whereConditions
     * @param array<mixed> $updateData
     * @return mixed
     */
    public function updateCondition($whereConditions = [], $updateData = []);

    /**
     * @param array<mixed> $whereConditions
     * @return mixed
     */
    public function countCondition($whereConditions = []);
}
