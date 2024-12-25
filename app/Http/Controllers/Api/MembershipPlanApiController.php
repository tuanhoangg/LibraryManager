<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MembershipPlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MembershipPlanApiController extends Controller
{
    //
    public function __construct(
        protected MembershipPlanService $membershipPlanService,
    ) {
    }

    public function getMembershipPlanList(Request $request)
    {
        $data = $this->membershipPlanService->getMembershipPlanList($request->all());

        if ($data) {
            return response()->json([
                'status' => 'success',
                'data' => $data['data'],
                'total' => $data['total'],
            ]);
        }
        return response()->json([
            'status' => 'error',
            'data' => [],
            'total' => 0,
        ]);
    }

    public function createMembershipPlan(Request $request)
    {
        try {
            //code...
            $membershipPlan = $this->membershipPlanService->getMembershipPlan(['name' => $request->name]);
            if ($membershipPlan) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Thể loại đã tồn tại'
                ]);
            }
            $data = $this->membershipPlanService->createMembershipPlan($request->all());

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data['data'],
                    'msg' => 'Tạo thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Create MembershipPlan', [$th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Tạo thất bại'
            ]);
        }
    }
    /**
     * update MembershipPlan
     *
     * @param Request $request
     * @return void
     */
    public function updateMembershipPlan(Request $request)
    {
        try {
            $membershipPlan = $this->membershipPlanService->getMembershipPlan([['name', '=', $request->name], ['id', '!=', $request->id]]);
            if ($membershipPlan) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Gói loại đã tồn tại'
                ]);
            }
            $data = $this->membershipPlanService->updateMembershipPlan($request->all());
            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                    'msg' => 'Sửa thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Update MembershipPlan', [$th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Sửa thất bại'
            ]);
        }
    }
    /**
     * delete MembershipPlan
     *
     * @param Request $request
     * @return void
     */
    public function deleteMembershipPlan(Request $request)
    {
        // $MembershipPlan = $this->membershipPlanService->getMembershipPlan(['id' => $request->id]);
        // if ($MembershipPlan->books) {
        //     return response()->json([
        //         'status' => 'error',
        //         'data' => [],
        //         'msg' => 'Thể loại không được xóa'
        //     ]);
        // }
        $data = $this->membershipPlanService->deleteMembershipPlan($request->id);

        if ($data) {
            return response()->json([
                'status' => 'success',
                'data' => [],
                'msg' => 'Xóa thành công'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'data' => [],
            'msg' => 'Xóa thất bại'
        ]);
    }
}
