<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Roles;
use App\Services\LateReturnService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LateReturnApiController extends Controller
{
    //
    public function __construct(
        protected LateReturnService $lateReturnService,
        protected PaymentService $paymentService,
    ) {}

    public function getlateReturnList(Request $request)
    {
        $isUser = true;
        if (Auth::user()->role_id != Roles::ROLE_USER) {
            $isUser = false;
        }

        return $this->lateReturnService->getlateReturnList($request, $isUser ? Auth::user()->id : "");
    }

    public function lateReturnPaymentByMomo(Request $request)
    {
        $id = $request->id;
        $paymentMethod = $request->payment_method;
        $penalty = $this->lateReturnService->getLateReturnById($id);
        $penalty['amount'] = $penalty->late_fee;
        $this->paymentService->createPayment($penalty, $paymentMethod, Payment::PENALTIES_FEE);

        if ($penalty) {
            $result = $this->paymentService->momoPayment($penalty->toArray(), Payment::PENALTIES_FEE);
            return response()->json([
                'payUrl' => $result['payUrl'],
                // 'data' => $result,
            ]);
        }
    }
}
