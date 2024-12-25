<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository extends BaseRepository
{
    function getModel()
    {
        return Book::class;
    }

    public function getBookList($request)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'DESC';
        $sortBy = $request['sortBy'] ?? 'id';

        $searchValue = $request['searchValue'] ?? '';
        $searchKey = $request['searchKey'] ?? '';
        $query = $this->model->with('author', 'book_item');

        if ($searchValue && !$searchKey) {
            $query = $query->where('title', 'LIKE', '%' . $searchValue . '%');
        }
        if ($searchKey) {
            $query = $query->where($searchKey, $searchValue);
        }
        $result['total'] = $query->count();

        $result['data'] = $query->limit($limit)->offset($offset)->orderBy($sortBy, $orderBy)->get();

        return $result;
    }

    public function exportBookDetail()
    {
        return $this->model->with('author', 'book_item.language')->get();
    }
}
