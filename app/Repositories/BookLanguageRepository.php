<?php

namespace App\Repositories;

use App\Models\BookLanguage;

class BookLanguageRepository extends BaseRepository
{
    function getModel()
    {
        return BookLanguage::class;
    }

    public function getBookLanguageList($request)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'DESC';
        $sortBy = $request['sortBy'] ?? 'id';

        $searchValue = $request['searchValue'] ?? '';
        $query = $this->model->query();

        if ($searchValue) {
            $query = $query->where('language_name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('language_code', 'LIKE', '%' . $searchValue . '%');
        }

        $result['total'] = $query->count();

        $result['data'] = $query->limit($limit)->offset($offset)->orderBy($sortBy, $orderBy)->get();

        return $result;
    }

    public function getBookLanguage($condition)
    {
        return $this->model->findOne($condition);
    }
}
