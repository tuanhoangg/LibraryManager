<?php

namespace App\Http\Controllers\Api;

use App\Helper\BarcodeHelper;
use App\Http\Controllers\Controller;
use App\Services\AuthorService;
use App\Services\BookItemService;
use App\Services\BookService;
use App\Services\GenresService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookApiController extends Controller
{
    //

    public function __construct(
        protected BookService $bookService,
        protected GenresService $genresService,
        protected AuthorService $authorService,
        protected BookItemService $bookItemService,
    ) {
    }

    public function getBookList(Request $request)
    {
        $data = $this->bookService->getBookList($request->all());
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

    public function storeBook(Request $request)
    {
        $book = $this->bookService->getBook([['isbn_code', '=', $request->isbn]]);
        if ($book) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Đã tồn tại isbn code',
            ]);
        }
        $data = $this->bookService->storeBook($request->all());

        if ($data) {
            // Lưu thông báo vào session
            $request->session()->flash('message', 'Tạo thành công');
            $request->session()->flash('alert-type', 'success');
            return response()->json([
                'status' => 'success',
                'msg' => 'Tạo thành công',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'msg' => 'Tạo thất bại',
        ]);
    }

    public function getBook(Request $request)
    {
        $data['book'] = $this->bookService->getBook(['id' => $request->id]);

        $data['bookItem'] = $this->bookItemService->getBookItemByBookId($data['book']['id']);

        return response()->json([
            'data' => $data,
            'status' => 'success',
        ]);
    }

    public function updateBook(Request $request)
    {
        $book = $this->bookService->getBook([['isbn_code', '=', $request->isbn], ['id', '!=', $request->id]]);
        if ($book) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Đã tồn tại isbn code',
            ]);
        }
        $data = $this->bookService->updateBook($request->all());

        if ($data) {
            // Lưu thông báo vào session
            $request->session()->flash('message', 'Chỉnh sửa thành công');
            $request->session()->flash('alert-type', 'success');
            return response()->json([
                'status' => 'success',
                'msg' => 'Chỉnh sửa thành công',
            ]);
        }
        return response()->json([
            'status' => 'error',
            'msg' => 'Chỉnh sửa thất bại',
        ]);
    }

    public function deleteBook(Request $request)
    {
        $book = $this->bookService->getBook([['id', '=', $request->id]]);

        if (count($book->borrow_history) > 0) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Không thể xóa vì đang sử dụng'
            ]);
        }
        $result = $this->bookService->deleteBook($request->id);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'msg' => 'Xóa thành công',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'msg' => 'Xóa thất bại',
        ]);
    }


    public function generateBarcodesPDF(Request $request)
    {
        return BarcodeHelper::generateBarcodesPDF($request->isbn_code);
    }
}
