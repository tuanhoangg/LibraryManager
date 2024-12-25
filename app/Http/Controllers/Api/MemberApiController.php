<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MemberService;
use Illuminate\Http\Request;

class MemberApiController extends Controller
{
    //
    public function __construct(
        protected MemberService $memberService,
    ) {
    }

    public function getMemberList(Request $request)
    {
        $data = $this->memberService->getMemberList($request->all());
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

    public function getMemberById(Request $request)
    {
        $data = $this->memberService->getMemberById($request->id);
        // dd($data->books_can_borrow);
        if ($data) {
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'data' => [],
            'total' => 0,
        ]);
    }
}
