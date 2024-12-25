<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookItemService;
use Illuminate\Http\Request;

class BookItemApiController extends Controller
{
    //
    public function __construct(
        protected BookItemService $bookItemService,
    ) {
    }

    public function getBookItems(Request $request)
    {
    }

    public function getBookItemsByIsbn(Request $request)
    {
        $data = $this->bookItemService->getBookItemListByIsbn($request->all());
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
