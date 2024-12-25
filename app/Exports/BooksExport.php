<?php

namespace App\Exports;

use App\Models\Book;
use App\Services\BookService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromView
{

    public function view(): View
    {
        $bookService = app(BookService::class);
        return view('exports.books', [
            'books' => $bookService->exportBookDetail(),
        ]);
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '[$$-409]#,##0.00', // Format the price column as currency
        ];
    }
}
