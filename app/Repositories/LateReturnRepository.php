<?php

namespace App\Repositories;

use App\Models\LateReturn;

class LateReturnRepository extends BaseRepository
{
    function getModel()
    {
        return LateReturn::class;
    }

    public function getlateReturnList($request, $isUser)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'DESC';
        $sortBy = $request['sortBy'] ?? 'id';
        $borrowStatus = $request['borrowStatus'] ?? '';

        $searchValue = $request['searchValue'] ?? '';
        $dateRange = $request['dateRange'];
        $searchKey = $request['searchBy'] ?? '';
        $query = $this->model->with('user', 'borrow.book', 'user.members', 'borrow.user');
        // if ($dateRange) {
        //     $startDate = explode(" - ", $dateRange)[0];
        //     $endDate = explode(" - ", $dateRange)[1];
        //     $query = $query->whereBetween('borrow_date', [$startDate, $endDate]);
        // } else {
        // }
        if ($searchValue) {
            $query = $query->whereHas('borrow.book', function ($query) use ($searchValue) {
                $query->where('book_code', 'LIKE', '%' . $searchValue . '%');
            });
        }
        // if ($searchKey) {
        //     if ($searchKey == 'member_code') {
        //         $query = $query->whereHas('user', function ($query) use ($searchValue) {
        //             $query->where('name', 'LIKE', '%' . $searchValue . '%');
        //         });
        //     } else {
        //         $query = $query->where($searchKey, 'LIKE', '%' . $searchValue . '%');
        //     }
        // }
        if ($borrowStatus != "") {
            $query = $query->where('status', $borrowStatus);
        }
        if ($isUser) {
            $query = $query->where('user_id', $isUser);
        }
        $result['total'] = $query->count();

        $result['data'] = $query->limit($limit)->offset($offset)->orderBy($sortBy, $orderBy)->get();

        return $result;
    }
}
