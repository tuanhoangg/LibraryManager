<?php

namespace App\Repositories;

use App\Models\Members;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberRepository extends BaseRepository
{
    function getModel()
    {
        return Members::class;
    }

    public function getMemberList($request)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 0;
        $orderBy = $request['orderBy'] ?? 'DESC';
        $sortBy = $request['sortBy'] ?? 'id';

        $searchValue = $request['searchValue'] ?? '';
        $searchKey = $request['searchKey'] ?? '';
        $query = $this->model->with('user');

        if ($searchValue) {
            $query = $query->where('member_code', 'LIKE', '%' . $searchValue . '%');
        }
        // if ($searchKey) {
        //     $query = $query->where($searchKey, $searchValue);
        // }
        $result['total'] = $query->count();

        $result['data'] = $query->limit($limit)->offset($offset)->orderBy($sortBy, $orderBy)->get();

        return $result;
    }

    public function updateMemberStatusJob()
    {
        try {
            //code...
            $expiredMembers = $this->model->where('due_date', '<', now())->pluck('user_id');

            $this->model->whereIn('user_id', $expiredMembers)->update(['status' => Members::EXPIRED]);

            DB::table('users')
                ->whereIn('id', $expiredMembers)
                ->update(['is_member' => User::NOT_MEMBER]);

            Log::info("update member");
        } catch (\Throwable $th) {
            //throw $th;

            Log::error("update member", [$th]);
        }
    }
}
