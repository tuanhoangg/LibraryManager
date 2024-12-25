<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookLanguage;
use App\Services\BookLanguageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookLanguageApiController extends Controller
{
    //
    public function __construct(
        protected BookLanguageService $bookLanguageService
    ) {
    }

    public function getBookLanguageList(Request $request)
    {
        $data = $this->bookLanguageService->getBookLanguageList($request->all());

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

    public function createBookLanguage(Request $request)
    {
        try {
            //code...
            $bookLanguage = $this->bookLanguageService->getBookLanguage([['language_code', '=', $request->language_code]]);
            if ($bookLanguage) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Thể loại đã tồn tại'
                ]);
            }
            $data = $this->bookLanguageService->createBookLanguage($request->all());

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data['data'],
                    'msg' => 'Tạo thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Create BookLanguage', [$th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Tạo thất bại'
            ]);
        }
    }
    /**
     * update BookLanguage
     *
     * @param Request $request
     * @return void
     */
    public function updateBookLanguage(Request $request)
    {
        // dd($request->id);
        try {
            $bookLanguage = $this->bookLanguageService->getBookLanguage([['language_code', '=', $request->language_code], ['id', '!=', $request->id]]);
            if ($bookLanguage) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Mã ngôn ngữ đã tồn tại'
                ]);
            }
            $data = $this->bookLanguageService->updateBookLanguage($request->all());
            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                    'msg' => 'Sửa thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Update BookLanguage', [$th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Sửa thất bại'
            ]);
        }
    }
    /**
     * delete BookLanguage
     *
     * @param Request $request
     * @return void
     */
    public function deleteBookLanguage(Request $request)
    {
        $bookLanguage = $this->bookLanguageService->getBookLanguage(['id' => $request->id]);
        if ($bookLanguage->book_item) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Thể loại không được xóa'
            ]);
        }
        $data = $this->bookLanguageService->deleteBookLanguage($request->id);

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
