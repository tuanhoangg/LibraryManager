<?php

namespace App\Repositories;

use App\Models\BorrowHistory;
use App\Repositories\BaseRepository;

class BorrowHistoryRepository extends BaseRepository
{
    function getModel()
    {
        return BorrowHistory::class;
    }

    public function getBorrowHistoryList($request, $isUser)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'DESC';
        $sortBy = $request['sortBy'] ?? 'id';
        $borrowStatus = $request['borrowStatus'] ?? '';

        $searchValue = $request['searchValue'] ?? '';
        $dateRange = $request['dateRange'];
        $searchKey = $request['searchBy'] ?? '';
        $query = $this->model->with('book', 'user', 'user.members');

        if ($dateRange) {
            $startDate = explode(" - ", $dateRange)[0];
            $endDate = explode(" - ", $dateRange)[1];
            $query = $query->whereBetween('borrow_date', [$startDate, $endDate]);
        } else {
        }

        if ($searchKey) {
            if ($searchKey == 'member_code') {
                $query = $query->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%');
                });
            } else {
                $query = $query->where($searchKey, 'LIKE', '%' . $searchValue . '%');
            }
        }
        if ($borrowStatus) {
            $query = $query->where('status', $borrowStatus);
        }
        if ($isUser) {
            $query = $query->where('user_id', $isUser);
        }
        $result['total'] = $query->count();

        $result['data'] = $query->limit($limit)->offset($offset)->orderBy($sortBy, $orderBy)->get();

        return $result;
    }

    public function getHistoryStatusOverdue()
    {
        return $this->model->where(function ($query) {
            $query->where('status', BorrowHistory::STATUS_BORROWED)
                ->orWhere('status', BorrowHistory::STATUS_OVERDUE);
        })
            ->where('due_date', '<', now())->get();
    }

    public function countPendingAndBorrowedRecords($userId)
    {
        return $this->model->where('user_id', $userId)
            ->where('status', BorrowHistory::STATUS_BORROWED)
            ->orWhere('status', BorrowHistory::STATUS_PENDING)->count();
    }
}
