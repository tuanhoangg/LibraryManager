<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthorApiController extends Controller
{
    //
    //
    public function __construct(
        protected AuthorService $authorService
    ) {
    }

    public function getAuthorList(Request $request)
    {
        $data = $this->authorService->getAuthorList($request->all());

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

    public function createAuthor(Request $request)
    {
        try {
            //code...
            $author = $this->authorService->getAuthor(['name' => $request->name]);
            if ($author) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Tác giả đã tồn tại'
                ]);
            }
            $data = $this->authorService->createAuthor($request->all());

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data['data'],
                    'msg' => 'Tạo thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Create Author', [$th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Tạo thất bại'
            ]);
        }
    }
    /**
     * update Author
     *
     * @param Request $request
     * @return void
     */
    public function updateAuthor(Request $request)
    {
        try {
            $Author = $this->authorService->getAuthor([['name', '=', $request->name], ['id', '!=', $request->id]]);
            if ($Author) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Tác giả đã tồn tại'
                ]);
            }
            $data = $this->authorService->updateAuthor($request->all());
            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                    'msg' => 'Sửa thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Update Author', [$th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Sửa thất bại'
            ]);
        }
    }
    /**
     * delete Author
     *
     * @param Request $request
     * @return void
     */
    public function deleteAuthor(Request $request)
    {
        $author = $this->authorService->getAuthor(['id' => $request->id]);
        if ($author->book) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Tác giả không được xóa'
            ]);
        }
        $data = $this->authorService->deleteAuthor($request->id);

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
