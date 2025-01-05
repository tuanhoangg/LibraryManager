<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Services\BorrowHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function __construct(
        protected BorrowHistoryService $borrowHistoryService
    ) {}

    public function dashboardView(Request $request)
    {
        $data = $this->borrowHistoryService->getInfoDashboard();
        $borrowedBooks = DB::table('borrow_histories')
            ->select(DB::raw('DATE(borrow_date) as date'), DB::raw('count(*) as total'))
            ->whereMonth('borrow_date', '=', date('m'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = [];
        $totals = [];
        foreach ($borrowedBooks as $record) {
            $dates[] = $record->date;
            $totals[] = $record->total;
        }

        $genres = DB::table('books')
            ->join('genres', 'books.genres_id', '=', 'genres.id')
            ->select('genres.name', DB::raw('count(*) as total'))
            ->groupBy('genres.name')
            ->get();

        $labelsBook = [];
        $dataBook = [];
        foreach ($genres as $genre) {
            $labelsBook[] = $genre->name;
            $dataBook[] = $genre->total;
        }
        // dd($totals, $dates);
        return view('pages.dashboard.index', [
            'data' => $data,
            'dates' => $dates,
            'totals' => $totals,
            'dataBook' => $dataBook,
            'labelsBook' => $labelsBook
        ]);
    }
}
