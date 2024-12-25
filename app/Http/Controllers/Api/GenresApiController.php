<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GenresService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GenresApiController extends Controller
{
    //
    public function __construct(
        protected GenresService $genresService
    ) {
    }

    public function getGenresList(Request $request)
    {
        $data = $this->genresService->getGenresList($request->all());

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

    public function createGenres(Request $request)
    {
        try {
            //code...
            $genres = $this->genresService->getGenres(['name' => $request->name]);
            if ($genres) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Thể loại đã tồn tại'
                ]);
            }
            $data = $this->genresService->createGenres($request->all());

            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data['data'],
                    'msg' => 'Tạo thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Create genres', [$th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Tạo thất bại'
            ]);
        }
    }
    /**
     * update genres
     *
     * @param Request $request
     * @return void
     */
    public function updateGenres(Request $request)
    {
        try {
            $genres = $this->genresService->getGenres([['name', '=', $request->name], ['id', '!=', $request->id]]);
            if ($genres) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'msg' => 'Thể loại đã tồn tại'
                ]);
            }
            $data = $this->genresService->updateGenres($request->all());
            if ($data) {
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                    'msg' => 'Sửa thành công'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Update genres', [$th->getMessage()]);

            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Sửa thất bại'
            ]);
        }
    }
    /**
     * delete genres
     *
     * @param Request $request
     * @return void
     */
    public function deleteGenres(Request $request)
    {
        $genres = $this->genresService->getGenres(['id' => $request->id]);
        if ($genres->books) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'msg' => 'Thể loại không được xóa'
            ]);
        }
        $data = $this->genresService->deleteGenres($request->id);

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
