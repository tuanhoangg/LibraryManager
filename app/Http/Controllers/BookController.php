<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use App\Jobs\ImportBookJob;
use App\Models\BookItem;
use App\Models\Publiser;
use App\Services\AuthorService;
use App\Services\BookItemService;
use App\Services\BookLanguageService;
use App\Services\BookService;
use App\Services\GenresService;
use App\Services\PubliserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    //

    public function __construct(
        protected BookService $bookService,
        protected GenresService $genresService,
        protected AuthorService $authorService,
        protected PubliserService $publiserService,
        protected BookLanguageService $bookLanguageService,
        protected BookItemService $bookItemService,
    ) {
    }

    public function index()
    {
        return view('pages.book.index');
    }


    public function createBook()
    {
        $optionAuthor = $this->authorService->getAllAuthor();
        $optionGenres = $this->genresService->getAllGenres();
        $optionPubliser = $this->publiserService->getAllPubliser();
        $optionBookLanguage = $this->bookLanguageService->getAllBookLanguage();
        return view('pages.book.create-book', compact('optionAuthor', 'optionGenres', 'optionPubliser', 'optionBookLanguage'));
    }

    public function editBook(Request $request)
    {
        $book = $this->bookService->getBook(['id' => $request->id]);
        if (!$book) {
            return redirect()->route('book.list')->with(['message' => 'Không tìm thấy sách', 'alert-type' => 'error']);
        }

        $bookItems = $this->bookItemService->getBookItemByBookId($request->id);
        $optionAuthor = $this->authorService->getAllAuthor();
        $optionGenres = $this->genresService->getAllGenres();
        $optionPubliser = $this->publiserService->getAllPubliser();
        $optionBookLanguage = $this->bookLanguageService->getAllBookLanguage();
        return view('pages.book.edit-book', compact('optionAuthor', 'optionGenres', 'optionPubliser', 'optionBookLanguage', 'bookItems', 'book'));
    }

    public function searchBook()
    {

        $optionAuthor = $this->authorService->getAllAuthor();
        $optionGenres = $this->genresService->getAllGenres();
        $optionPubliser = $this->publiserService->getAllPubliser();
        $optionBookLanguage = $this->bookLanguageService->getAllBookLanguage();
        return view('pages.book.search-book', compact('optionAuthor', 'optionGenres', 'optionPubliser', 'optionBookLanguage'));
    }

    public function export()
    {
        return Excel::download(new BooksExport, 'books.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $path = $request->file('file')->store('temp');
        $jobId = uniqid(); // Generate a unique ID for the job

        try {
            Cache::put('import_status_' . $jobId, [
                'status' => 'pending',
                'message' => 'Job is still processing'
            ], now()->addMinutes(10));

            ImportBookJob::dispatch($path, $jobId)->onQueue('default');

            return response()->json([
                'status' => 'success',
                'message' => 'Import thành công',
                'jobId' => $jobId
            ]);
        } catch (\Throwable $th) {
            Log::error("Error importing user", [$th]);
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function importStatus($jobId)
    {
        $status = Cache::get('import_status_' . $jobId);

        if (!$status) {
            return response()->json([
                'status' => 'pending',
                'message' => 'The job is still processing.'
            ]);
        }

        return response()->json($status);
    }
}
