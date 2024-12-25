<?php

namespace App\Repositories;

use App\Models\MembershipPlan;

class MembershipPlanRepository extends BaseRepository
{
    function getModel()
    {
        return MembershipPlan::class;
    }

    public function getMembershipPlanList($request)
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
}
