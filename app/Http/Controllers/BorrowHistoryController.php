<?php

namespace App\Http\Controllers;

use App\Exports\BorrowHistoryExport;
use App\Services\BookItemService;
use App\Services\BorrowHistoryService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BorrowHistoryController extends Controller
{
    //
    public function __construct(
        protected BorrowHistoryService $borrowHistoryService,
        protected BookItemService $bookItemService,
    ) {
    }

    public function index()
    {
        return view('pages.borrow-history.index');
    }

    public function export(Request $request)
    {
        return Excel::download(new BorrowHistoryExport($request), 'borrow.xlsx');
    }
}
