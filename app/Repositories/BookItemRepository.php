<?php

namespace App\Repositories;

use App\Models\BookItem;

class BookItemRepository extends BaseRepository
{
    function getModel()
    {
        return BookItem::class;
    }
    public function getBookItemList($request)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'DESC';
        $sortBy = $request['sortBy'] ?? 'id';

        $searchValue = $request['searchValue'] ?? '';
        $searchKey = $request['searchKey'] ?? '';
        $query = $this->model->with('author');

        if ($searchValue) {
            $query = $query->where('title', 'LIKE', '%' . $searchValue . '%');
        }
        if ($searchKey) {
            $query = $query->where($searchKey, 'LIKE', '%' . $searchValue . '%');
        }
        $result['total'] = $query->count();

        $result['data'] = $query->limit($limit)->offset($offset)->orderBy($sortBy, $orderBy)->get();

        return $result;
    }
    public function deleteBookItemByCode($bookCode, $isbn)
    {
        return $this->model->where('isbn_code', $isbn)->whereIn('book_code', $bookCode)->delete();
    }

    public function getBookItemByIsbn($request)
    {
        $isbn = $request['isbn'];
        $searchValue = $request['searchValue'] ?? '';

        $query = $this->model->with('book');
        if ($searchValue) {
            $query = $query->where('book_code', 'LIKE', '%' . $searchValue . '%');
        }
        $query = $query->where('isbn_code', $isbn);
        return $query->where('status', BookItem::STATUS_AVAIABLE)->get();
    }

    public function deleteMultiBookItem($bookId) {
        return $this->model->where('book_id', $bookId)->delete();
    }
}
