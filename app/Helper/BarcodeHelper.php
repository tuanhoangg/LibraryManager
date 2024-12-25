<?php

namespace App\Helper;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeHelper
{
    static public function generateBarcode($code, $path)
    {
        try {
            //code...
            $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
            $barcodePath = 'barcodes' . $path . $code . '.png';
            file_put_contents(storage_path('app/public/' . $barcodePath), $barcode);

            return Storage::url($barcodePath);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("barcode", [$th]);
        }
    }

    /**
     * Generate a PDF containing barcodes for all book items of a given book.
     *
     * @param int $bookId
     * @return \Illuminate\Http\Response
     */
    public static function generateBarcodesPDF($isbnCode)
    {
        $book = \App\Models\Book::where('isbn_code', $isbnCode)->first();
        $bookItems = \App\Models\BookItem::where('isbn_code', $isbnCode)->get();

        // Ensure barcodes are generated for each book item
        // foreach ($bookItems as $item) {
        //     if (!$item->barcode) {
        //         $item->barcode = self::generateBarcode($item->book_code);
        //         $item->save();
        //     }
        // }

        $data = [
            'book' => $book,
            'bookItems' => $bookItems,
        ];

        $pdf = FacadePdf::loadView('exports.barcode', $data);

        return $pdf->download('barcodes.pdf');
    }
}
