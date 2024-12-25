<?php

namespace App\Services;

use App\Models\BorrowHistory;
use App\Models\LateReturn;
use App\Repositories\BorrowHistoryRepository;
use App\Repositories\FinesRepository;
use App\Repositories\LateReturnRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FinesService
{
    public function __construct(
        protected LateReturnRepository $lateReturnRepository,
        protected BorrowHistoryRepository $borrowHistoryRepository,
        protected UserService $userService,
        protected FinesRepository $finesRepository,
    ) {
        //
    }

    public function getFinesList($request, $idUser)
    {
        return $this->finesRepository->getFinesList($request, $idUser);
    }

    public function updateLateReturn()
    {
        try {
            $borrowList = $this->borrowHistoryRepository->getHistoryStatusOverdue();
            $arr = [];
            $borrow = [];
            //update status borrow to overdue
            foreach ($borrowList as $key => $borrow) {
                $user = $this->userService->getUser(['id' => $borrow->user_id]);
                $penalty = $this->lateReturnRepository->findOne(['borrow_id' => $borrow->id]);

                $arr[$key]['late_days'] = Carbon::now()->diffInDays($borrow->due_date);
                $discountMember = $user->is_member ? round($user->members->membership_plan->late_fee_discount / 100, 1) : 0;
                $originalFee = ($penalty ? ($arr[$key]['late_days'] - $penalty->late_days) : $arr[$key]['late_days']) * 10000;
                $late_fee = $penalty
                    ? $penalty->late_fee + ($originalFee - ($originalFee * $discountMember))
                    : $originalFee - ($originalFee * $discountMember);

                $arr[$key]['borrow_id'] = $borrow->id;
                $arr[$key]['user_id'] = $borrow->user_id;
                $arr[$key]['late_fee'] = $late_fee;
                $arr[$key]['status'] = LateReturn::STATUS_PENDING;

                $borrow['status'] = BorrowHistory::STATUS_OVERDUE;
                unset($borrow['created_at']);
                unset($borrow['updated_at']);
            }
            Log::info('Start batch update late return', [$borrowList]);
            $this->borrowHistoryRepository->batchUpsert($borrowList->toArray(), ['id']);

            $this->lateReturnRepository->batchUpsert($arr, ['borrow_id', 'user_id']);
        } catch (\Throwable $th) {
            //throw $th;

            Log::error('Job update late return', [$th]);
        }
    }


    public function getFinesById($id)
    {
        return $this->finesRepository->findOne(['id' => $id]);
    }
}
