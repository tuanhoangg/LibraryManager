<?php

namespace App\Repositories;

use App\Models\Publiser;

class PubliserRepository extends BaseRepository
{
    function getModel()
    {
        return Publiser::class;
    }

    public function getPubliserList($request)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'DESC';
        $sortBy = $request['sortBy'] ?? 'id';

        $searchValue = $request['searchValue'] ?? '';
        $query = $this->model->query();

        if ($searchValue) {
            $query = $query->where('name', 'LIKE', '%' . $searchValue . '%');
        }

        $result['total'] = $query->count();

        $result['data'] = $query->limit($limit)->offset($offset)->orderBy($sortBy, $orderBy)->get();

        return $result;
    }

    public function getPubliser($condition)
    {
        return $this->model->findOne($condition);
    }
}
