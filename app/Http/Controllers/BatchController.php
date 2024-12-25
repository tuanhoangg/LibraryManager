<?php

namespace App\Http\Controllers;

use App\Services\BorrowHistoryService;
use App\Services\LateReturnService;
use App\Services\MemberService;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    //

    public function __construct(
        protected LateReturnService $lateReturnService,
        protected MemberService $memberService,
    ) {
    }

    public function batchUpdateLateReturn(Request $request)
    {
        $this->lateReturnService->updateLateReturn();
        return response()->json(['message' => 'Cập nhật trả muộn thành công']);
    }

    public function batchMember(Request $request)
    {
        $this->memberService->updateMemberStatusJob();
        return response()->json(['message' => 'Cập nhật danh sách thành viên thành công']);
    }
}
