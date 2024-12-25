<?php

namespace App\Exports;

use App\Models\Book;
use App\Services\BookService;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromView
{

    public function view(): View
    {
        $userService = app(UserService::class);
        return view('exports.users', [
            'users' => $userService->exportUserDetail(),
        ]);
    }
}
