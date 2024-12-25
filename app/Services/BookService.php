<?php

namespace App\Services;

use App\Helper\BarcodeHelper;
use App\Models\BookItem;
use App\Repositories\BookItemRepository;
use App\Repositories\BookRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookService
{
    public function __construct(
        protected BookRepository $bookRepository,
        protected BookItemRepository $bookItemRepository,
    ) {
        //
    }

    public function getBookList($request)
    {
        return $this->bookRepository->getBookList($request);
    }

    public function storeBook($data)
    {
        $storedFiles = [];
        $barcodePaths = [];
        DB::beginTransaction();
        try {
            $book['title'] = $data['bookName'];
            $book['isbn_code'] = $data['isbn'];
            $book['author_id'] = $data['author'];
            $book['description'] = $data['description'];
            $book['genres_id'] = $data['genre'];
            $path = $data['avatar'] ? $data['avatar']->store('public/books') : null;
            $book['image'] = Storage::url($path);

            $result = $this->bookRepository->create($book);
            if (!$result) {
                return false;
            }
            $items = [];
            foreach ($data['book_items'] as $key => $item) {
                // dd($data['book_items']);
                $items[$key]['book_id'] = $result->id;
                $items[$key]['isbn_code'] = $data['isbn'];
                $items[$key]['book_code'] = $item['book_code'];
                $items[$key]['publiser_id'] = $item['publiser_id'];
                $items[$key]['edition'] = $item['edition'];
                $items[$key]['price'] = $item['price'];
                $items[$key]['book_language_id'] = $item['book_language_id'];
                $items[$key]['location'] = $item['location'];
                $items[$key]['status'] = BookItem::STATUS_AVAIABLE;

                $pathPreview = isset($item['preview_url']) ? $item['preview_url']->store('public/preview') : '';
                $storedFiles[$key] = $pathPreview;

                $pathBarcode = BarcodeHelper::generateBarcode($items[$key]['isbn_code'] . '-' . $items[$key]['book_code'], '/book/');
                $barcodePaths[$key] = $pathBarcode;
                $items[$key]['barcode'] = $pathBarcode;

                $items[$key]['preview_url'] = isset($item['preview_url']) ? Storage::url($pathPreview) : '';
            }

            $this->bookItemRepository->batchUpsert($items, ['id']);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("create book", [$th]);
            DB::rollBack();
            foreach ($storedFiles as $file) {
                Storage::delete($file);
            }
            foreach ($barcodePaths as $file) {
                Storage::delete($file);
            }
            return false;
        }
    }

    public function getBook($condition)
    {
        return $this->bookRepository->where($condition)->with('borrow_history')->first();;
    }

    public function updateBook($data)
    {
        DB::beginTransaction();
        try {
            // $bookItems = json_decode($data['bookItems'], true);
            $bookItems = $data['book_items'];
            $deletedItems = json_decode($data['deletedItems'], true);

            $this->bookItemRepository->deleteBookItemByCode($deletedItems, $data['isbn']);

            $book['id'] = $data['id'];
            $book['title'] = $data['bookName'];
            $book['isbn_code'] = $data['isbn'];
            $book['author_id'] = $data['author'];
            $book['description'] = $data['description'];
            $book['genres_id'] = $data['genre'];
            if ($data['avatar']) {
                $path = $data['avatar']->store('public/books');
                $book['image'] = Storage::url($path);
            }

            $result = $this->bookRepository->upsert(['id'], $book);
            if (!$result) {
                return false;
            }
            $items = [];

            $dataAdd = [];
            $dataEdit = [];
            foreach ($bookItems as $key => $item) {
                if (isset($item['id']) && !is_null($item['id'])) {
                    $dataEdit[$key]['id'] = $item['id'];
                    $dataEdit[$key]['book_id'] = $data['id'];
                    $dataEdit[$key]['isbn_code'] = $data['isbn'];
                    $dataEdit[$key]['book_code'] = $item['book_code'];
                    $dataEdit[$key]['publiser_id'] = $item['publiser_id'];
                    $dataEdit[$key]['edition'] = $item['edition'];
                    $dataEdit[$key]['book_language_id'] = $item['book_language_id'];
                    $dataEdit[$key]['location'] = $item['location'];
                    $dataEdit[$key]['status'] = $item['status'];
                    $dataEdit[$key]['price'] = $item['price'];

                    if(array_key_exists('preview_url', $item)) {
                        $pathPreview = (isset($item['preview_url']) && !is_string($item['preview_url'])) ? $item['preview_url']->store('public/preview') : '';
                        $dataEdit[$key]['preview_url'] = (array_key_exists('preview_url', $item) && isset($item['preview_url']) && !is_string($item['preview_url'])) ? Storage::url($pathPreview) : $item['preview_url'];
                    }

                    continue;
                }
                $dataAdd[$key]['book_id'] = $data['id'];
                $dataAdd[$key]['isbn_code'] = $data['isbn'];
                $dataAdd[$key]['book_code'] = $item['book_code'];
                $dataAdd[$key]['publiser_id'] = $item['publiser_id'];
                $dataAdd[$key]['edition'] = $item['edition'];
                $dataAdd[$key]['book_language_id'] = $item['book_language_id'];
                $dataAdd[$key]['location'] = $item['location'];
                $dataAdd[$key]['status'] = $item['status'];
                $dataAdd[$key]['price'] = $item['price'];
                $pathBarcode = BarcodeHelper::generateBarcode($data['isbn'] . '-' . $item['book_code'], '/book/');
                $barcodePaths[$key] = $pathBarcode;
                $dataAdd[$key]['barcode'] = $pathBarcode;
                    $pathPreview = (isset($item['preview_url']) && !is_string($item['preview_url'])) ? $item['preview_url']->store('public/preview') : '';
                    $dataAdd[$key]['preview_url'] = (isset($item['preview_url']) && !is_string($item['preview_url'])) ? Storage::url($pathPreview) : $item['preview_url'];
            }

            $this->bookItemRepository->batchUpsert($dataAdd, ['id']);

            $this->bookItemRepository->batchUpsert($dataEdit, ['id']);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error("Update book", [$th]);
            return false;
        }
    }

    public function deleteBook($id)
    {
        $this->bookItemRepository->deleteMultiBookItem($id);
        return $this->bookRepository->delete($id);
    }

    public function exportBookDetail()
    {
        return $this->bookRepository->exportBookDetail();
    }
}
