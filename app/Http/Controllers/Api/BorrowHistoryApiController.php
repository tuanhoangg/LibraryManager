<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendMailLateReturn;
use App\Models\BookItem;
use App\Models\BorrowHistory;
use App\Models\Roles;
use App\Services\BookItemService;
use App\Services\BorrowHistoryService;
use App\Services\LateReturnService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BorrowHistoryApiController extends Controller
{
    //
    public function __construct(
        protected BorrowHistoryService $borrowHistoryService,
        protected BookItemService $bookItemService,
        protected UserService $userService,
        protected LateReturnService $lateReturnService,
    ) {}

    public function borrowBook(Request $request)
    {
        $data = $request->all();
        $dataCreate['book_item_id'] = $data['bookDetail']['id'];
        $dataCreate['book_code'] = $data['bookDetail']['book_code'];
        $dataCreate['isbn_code'] = $data['bookDetail']['isbn_code'];
        $dataCreate['user_id'] = $data['userId'];
        $dataCreate['status'] = BorrowHistory::STATUS_PENDING;
        $dataCreate['borrow_date'] = date('Y-m-d H:i:s', strtotime($data['date']['borrow_date']));
        $dataCreate['due_date'] = date('Y-m-d H:i:s', strtotime($data['date']['return_date']));

        $user = $this->userService->getUser(['id' => $data['userId']]);
        if (!$user->is_member && !$user->member_code) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Tài khoản không phải là hội viên',
            ]);
        }

        if (!$user->is_member && $user->member_code) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Tài khoản hết hạn hội viên',
            ]);
        }
        $checkLimitBorrowed = $this->borrowHistoryService->checkLimitBorrow($data['userId']);

        if (!$checkLimitBorrowed['check']) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Bạn đã mượn tối đa số sách trong gói hội viên của mình. Số lượng sách mượn đã đạt đến số lượng tối đa.(' . $checkLimitBorrowed['number'] . ')',
            ]);
        }
        $userCountLateReturn = $this->lateReturnService->checkOverdueUser($data['userId']);

        if ($userCountLateReturn > 0) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Bạn không thể mượn tiếp khi chưa trả số sách trước đó.',
            ]);
        }
        try {
            //code...
            $bookItem = $this->bookItemService->checkBookItemBorrowStatus($dataCreate['isbn_code'], $dataCreate['book_code']);
            if ($bookItem && $bookItem->status != BookItem::STATUS_AVAIABLE) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Mượn thất bại',
                ]);
            }
            $result = $this->borrowHistoryService->createBorrow($dataCreate);
            if ($result) {
                $this->bookItemService->updateStatusBookItemById($dataCreate['book_item_id'], BookItem::STATUS_UNAVAIABLE);
            }
            return response()->json([
                'status' => 'success',
                'msg' => 'Mượn thành công',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            //throw $th;
            Log::error("Borrow book", [$th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'msg' => 'Mượn thất bại',
            ]);
        }
    }

    public function getBorrowHistoryList(Request $request)
    {
        $isUser = true;
        if (Auth::user()->role_id == Roles::ROLE_ADMIN ||
        Auth::user()->role_id == Roles::ROLE_LIBRARIAN    
        ) {
            $isUser = false;
        }

        return $this->borrowHistoryService->getBorrowHistoryList($request, $isUser ? Auth::user()->id : "");
    }

    // public function getBorrowHistoryListByUserId(Request $request)
    // {
    //     return $this->borrowHistoryService->getBorrowHistoryList($request);
    // }
    public function updateBorrowStatus(Request $request)
    {
        $id = $request->id;
        $borrowId = $request->borrow_id;
        $status = $request->status;

        try {
            //code...
            $result = $this->borrowHistoryService->updateBorrowStatus($id, $borrowId, $status);

            return response()->json([
                'status' => 'success',
                'msg' => 'Cập nhật thành công',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("Update status", [$th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'msg' => 'Cập nhật thất bại',
            ]);
        }
    }


    public function cancelBorrowStatus(Request $request)
    {
        $bookCode = $request->bookCode;
        $id = $request->id;
        $isbnCode = $request->isbnCode;
        try {
            $result = $this->borrowHistoryService->cancelBorrowStatus($bookCode, $isbnCode, $id);


            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Cập nhật thành công',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'msg' => 'Không tìm thấy sách',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("Update status", [$th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'msg' => 'Cập nhật thất bại',
            ]);
        }
    }

    public function sendMailLateReturns()
    {
        $borrowList = $this->borrowHistoryService->getOverdueBorrowList();
        Log::info('send email overdue info');

        try {
            //code...
            foreach ($borrowList as $key => $borrow) {
                # code...
                Mail::to($borrow->user->email)->send(new SendMailLateReturn($borrow));
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('send email overdue error', [$th]);
        }
    }
}
