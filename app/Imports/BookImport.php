<?php

namespace App\Imports;

use App\Helper\BarcodeHelper;
use App\Models\Book;
use App\Models\BookItem;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class BookImport implements ToCollection
{
    protected $jobId;

    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }

    public function collection(Collection $rows)
    {
        // Skip header row
        $rows->shift();
        $error = false;
        $currentRow = 0;
        foreach ($rows as $row) {
            // Convert collection to array with named keys
            $currentRow++;
            $rowArray = [
                'title' => $row[0],
                'isbn_code_book' => $row[1],
                'author_id' => $row[2],
                'year' => $row[3],
                'description' => $row[4],
                'image' => $row[5],
                'genres_id' => $row[6],
                'isbn_code_item' => $row[7],
                'book_code' => $row[8],
                'publiser_id' => $row[9],
                'status' => $row[10],
                'price' => $row[11],
                'location' => $row[12],
                'edition' => $row[13],
                'book_language_id' => $row[14],
            ];

            DB::beginTransaction();
            try {
                // Validate the book data
                $bookValidator = Validator::make($rowArray, [
                    'title' => 'required|string',
                    'isbn_code_book' => 'required|integer',
                    'author_id' => 'nullable|integer',
                    'year' => 'nullable|integer',
                    'description' => 'nullable|string',
                    'image' => 'nullable|string',
                    'genres_id' => 'required|integer',
                ]);

                if ($bookValidator->fails()) {
                    throw new ValidationException($bookValidator);
                }

                // Validate the book item data
                $bookItemValidator = Validator::make($rowArray, [
                    'isbn_code_item' => 'required|integer',
                    'book_code' => 'required|string',
                    'publiser_id' => 'required|integer',
                    'status' => 'required|integer',
                    'price' => 'required|integer',
                    'location' => 'required|string',
                    'edition' => 'required|string',
                    'book_language_id' => 'required|integer',
                ]);

                if ($bookItemValidator->fails()) {
                    throw new ValidationException($bookItemValidator);
                }

                // Insert or update book
                $book = Book::updateOrCreate(
                    ['isbn_code' => $rowArray['isbn_code_book']],
                    [
                        'title' => $rowArray['title'],
                        'author_id' => $rowArray['author_id'],
                        'year' => $rowArray['year'],
                        'description' => $rowArray['description'],
                        'image' => $rowArray['image'],
                        'genres_id' => $rowArray['genres_id'],
                    ]
                );
                $pathBarcode = BarcodeHelper::generateBarcode($rowArray['isbn_code_item'] . '-' . $rowArray['book_code'], '/book/');
                // Insert book item
                BookItem::create([
                    'book_id' => $book->id,
                    'isbn_code' => $rowArray['isbn_code_item'],
                    'book_code' => $rowArray['book_code'],
                    'publiser_id' => $rowArray['publiser_id'],
                    'status' => $rowArray['status'],
                    'price' => $rowArray['price'],
                    'location' => $rowArray['location'],
                    'edition' => $rowArray['edition'],
                    'book_language_id' => $rowArray['book_language_id'],
                    'barcode' => $pathBarcode
                ]);

                DB::commit();
            } catch (ValidationException $e) {
                // Log validation errors and continue
                Log::error("Validation error for row: " . json_encode($rowArray) . " - " . $e->getMessage());
                Cache::put('import_status_' . $this->jobId, [
                    'status' => 'error',
                    'message' => 'Tại dòng ' . $row . ' ' . $e->getMessage()
                ], now()->addMinutes(10));
                DB::rollBack();
                // continue;
            } catch (\Exception $e) {
                // Log general errors and continue
                Log::error("Error importing row: " . json_encode($rowArray) . " - " . $e->getMessage());
                Cache::put('import_status_' . $this->jobId, [
                    'status' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ], now()->addMinutes(10));
                DB::rollBack();
                // continue;
            }
            if (!$error) {
                Cache::put('import_status_' . $this->jobId, [
                    'status' => 'success',
                    'message' => 'Import thành công'
                ], now()->addMinutes(10));
            }
        }
    }
}
