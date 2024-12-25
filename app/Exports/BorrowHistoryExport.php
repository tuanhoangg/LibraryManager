<?php

namespace App\Exports;

use App\Models\BorrowHistory;
use App\Services\BorrowHistoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class BorrowHistoryExport implements FromView
{

    public $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $borrowService = app(BorrowHistoryService::class);
        $data = $borrowService->getBorrowHistoryList($this->request, null);
        // dd($data['data']);
        return view('exports.borrow', [
            'borrows' => $data['data']
        ]);
    }
}
