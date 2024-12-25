<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PubliserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class PubliserApiController extends Controller
{
    //
    //
    public function __construct(
        protected PubliserService $publiserService
    ) {
    }

    public function getPubliserList(Request $request)
    {
        $data = $this->publiserService->getPubliserList($request->all());

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

    public function createPubliser(Request $request)
    {
        try {
            //code...
            $publiser = $this->publiserService->getPubliser(['name' => $request->name]);
            if ($publiser) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Nhà xuất bản đã tồn tại'
                ]);
            }
            $data = $this->publiserService->createPubliser($request->all());

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data['data'],
                    'msg' => 'Tạo thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Create Publiser', [$th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Tạo thất bại'
            ]);
        }
    }
    /**
     * update Publiser
     *
     * @param Request $request
     * @return void
     */
    public function updatePubliser(Request $request)
    {
        try {
            $publiser = $this->publiserService->getPubliser([['name', '=', $request->name], ['id', '!=', $request->id]]);
            if ($publiser) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Thể loại đã tồn tại'
                ]);
            }
            $data = $this->publiserService->updatePubliser($request->all());
            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                    'msg' => 'Sửa thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Update Publiser', [$th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Sửa thất bại'
            ]);
        }
    }
    /**
     * delete Publiser
     *
     * @param Request $request
     * @return void
     */
    public function deletePubliser(Request $request)
    {
        $publiser = $this->publiserService->getPubliser(['id' => $request->id]);
        if ($publiser->book) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Thể loại không được xóa'
            ]);
        }
        $data = $this->publiserService->deletePubliser($request->id);

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
