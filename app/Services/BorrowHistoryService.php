<?php

namespace App\Services;

use App\Models\BookItem;
use App\Models\BorrowHistory;
use App\Models\Fines;
use App\Repositories\BookItemRepository;
use App\Repositories\BookRepository;
use App\Repositories\BorrowHistoryRepository;
use App\Repositories\FinesRepository;
use App\Repositories\MemberRepository;

class BorrowHistoryService
{
    public function __construct(
        protected BorrowHistoryRepository $borrowHistoryRepository,
        protected BookItemRepository $bookItemRepository,
        protected MemberRepository $memberRepository,
        protected FinesRepository $finesRepository,
    ) {
        //
    }


    public function createBorrow($data)
    {
        $bookItem = $this->bookItemRepository->findOne(['book_code' => $data['book_code'], 'isbn_code' => $data['isbn_code']]);

        if ($bookItem->status == BookItem::STATUS_AVAIABLE) {
            return $this->borrowHistoryRepository->create($data);
        }

        return false;
    }

    public function getBorrowHistoryList($request, $isUser)
    {
        return $this->borrowHistoryRepository->getBorrowHistoryList($request, $isUser);
    }

    public function updateBorrowStatus($id, $borrowId, $status)
    {
        $listStatusIssues = [BorrowHistory::STATUS_LOST, BorrowHistory::STATUS_DAMAGED];

        if ($status == BorrowHistory::STATUS_UNRESERVE) {
            $this->bookItemRepository->update($id, ['status' => BookItem::STATUS_AVAIABLE]);
        }

        if ($status == BorrowHistory::STATUS_RETURNED) {
            $this->bookItemRepository->update($id, ['status' => BookItem::STATUS_AVAIABLE]);
            return $this->borrowHistoryRepository->update($borrowId, ['status' => $status, 'returned_at' => now()]);
        }

        if (in_array($status, $listStatusIssues)) {
            $this->bookItemRepository->update($id, ['status' => $status == BorrowHistory::STATUS_DAMAGED ? BookItem::STATUS_DAMAGED : BookItem::STATUS_LOST]);
            $borrowData = $this->borrowHistoryRepository->findById($borrowId);
            $bookData = $this->bookItemRepository->findById($borrowData->book_item_id);
            $finesData = [
                'borrow_id' => $borrowId,
                'user_id' => $borrowData->user_id,
                'fine_type' => $status,
                'amount' => Fines::FINE_FEE + $bookData->price, // Nhan voi gia tien sach
                'status' => Fines::STATUS_PENDING,
            ];
            $this->finesRepository->create($finesData);

            return $this->borrowHistoryRepository->update($borrowId, ['status' => $status, 'returned_at' => now()]);
        }
        return $this->borrowHistoryRepository->update($borrowId, ['status' => $status]);
    }

    public function cancelBorrowStatus($bookCode, $isbnCode, $id)
    {
        $bookItem = $this->bookItemRepository->findOne(['book_code' => $bookCode, 'isbn_code' => $isbnCode]);
        if (!$bookItem) {
            return false;
        }

        $this->bookItemRepository->update($bookItem->id, ['status' => BookItem::STATUS_AVAIABLE]);

        return $this->borrowHistoryRepository->update($id, ['status' => BorrowHistory::STATUS_UNRESERVE]);
    }

    public function getInfoDashboard()
    {
        $data['overdue'] = $this->borrowHistoryRepository->countCondition(['status' => BorrowHistory::STATUS_OVERDUE]);
        $data['lost'] = $this->borrowHistoryRepository->countCondition(['status' => BorrowHistory::STATUS_LOST]);
        $data['pending'] = $this->borrowHistoryRepository->countCondition(['status' => BorrowHistory::STATUS_PENDING]);
        $data['borrowed'] = $this->borrowHistoryRepository->countCondition(['status' => BorrowHistory::STATUS_BORROWED]);

        return $data;
    }

    public function checkLimitBorrow($userId)
    {
        $member = $this->memberRepository->where(['user_id' => $userId])->with('membership_plan')->first();
        $limitBook = $member->membership_plan->limit_book;

        $countBook = $this->borrowHistoryRepository->countPendingAndBorrowedRecords($userId);

        return [
            'check' => $countBook <= $limitBook,
            'number' => $countBook . "/" . $limitBook,
        ];
    }

    public function getOverdueBorrowList()
    {
        return $this->borrowHistoryRepository->where(['status' => BorrowHistory::STATUS_OVERDUE])->with('user')->get();
    }
}
