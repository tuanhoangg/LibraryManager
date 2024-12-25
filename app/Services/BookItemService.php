<?php

namespace App\Services;

use App\Models\BookItem;
use App\Repositories\BookItemRepository;

class BookItemService
{
    public function __construct(
        protected BookItemRepository $bookItemRepository
    ) {
        //
    }

    public function getBookItemList($request)
    {
        return $this->bookItemRepository->getBookItemList($request);
    }
    public function getBookItemByIsbn($isbn)
    {
        return $this->bookItemRepository->findByConditions(['isbn_code' => $isbn]);
    }
    public function getBookItemByBookId($bookId)
    {
        return $this->bookItemRepository->findByConditions(['book_id' => $bookId]);
    }
    public function updateStatusBookItemById($id, $status)
    {
        return $this->bookItemRepository->update($id, ['status' => $status]);
    }

    public function getBookItemListByIsbn($request)
    {
        return $this->bookItemRepository->getBookItemByIsbn($request);
        // return $this->bookItemRepository->where(['isbn_code' => $isbn, 'status' => BookItem::STATUS_AVAIABLE])->with('book')->get();
    }

    public function checkBookItemBorrowStatus($isbnCode, $bookCode)
    {
        return $this->bookItemRepository->findOne(['book_code' => $bookCode, 'isbn_code' => $isbnCode]);
    }
}
