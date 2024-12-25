<?php

namespace App\Repositories;

use App\Models\BorrowHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    function getModel()
    {
        return User::class;
    }

    public function getUserList($request)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'DESC';
        $sortBy = $request['sortBy'] ?? 'id';
        $searchValue = $request['searchValue'] ?? '';
        $statusBorrowed = BorrowHistory::STATUS_BORROWED;

        // Query để đếm tổng số người dùng (không có hàm COUNT cho borrow_histories)
        $totalQuery = $this->model->query();

        if ($searchValue) {
            $totalQuery = $totalQuery->where('users.name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('users.email', 'LIKE', '%' . $searchValue . '%');
        }

        // Đếm tổng số người dùng
        $result['total'] = $totalQuery->count();

        // Query để lấy danh sách người dùng cùng số lần mượn
        $query = $this->model->query()
            ->with('members.membership_plan')
            ->leftJoin('borrow_histories', function ($join) use ($statusBorrowed) {
                $join->on('users.id', '=', 'borrow_histories.user_id')
                    ->where('borrow_histories.status', '=', $statusBorrowed);
            })
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.password',
                'users.role_id',
                'users.address',
                'users.phone',
                'users.member_id',
                'users.image',
                'users.email_verified_at',
                'users.remember_token',
                'users.created_at',
                'users.updated_at',
                'users.is_member',
                'users.member_code',
                DB::raw('COUNT(borrow_histories.id) as borrow_count')
            )
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.password',
                'users.role_id',
                'users.address',
                'users.phone',
                'users.member_id',
                'users.image',
                'users.email_verified_at',
                'users.remember_token',
                'users.created_at',
                'users.updated_at',
                'users.is_member',
                'users.member_code'
            );

        if ($searchValue) {
            $query = $query->where('users.name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('users.email', 'LIKE', '%' . $searchValue . '%');
        }

        // Lấy dữ liệu với giới hạn và phân trang
        $result['data'] = $query->limit($limit)->offset($offset)->orderBy('users.' . $sortBy, $orderBy)->get();

        return $result;
    }

    public function exportUserDetail()
    {
        return $this->model->with('role', 'members')->get();
    }

    public function getUser($userId)
    {
        return $this->model->with('role', 'members.membership_plan')->where('id', $userId)->first();
    }
}
