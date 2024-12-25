<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function __construct(protected PaymentService $paymentService)
    {
    }
    public function momoPayment(Request $request)
    {
        // $result = $this->paymentService->momoPayment();
        // return redirect($result['payUrl']);
        // return response()->json($result);
    }

    public function vnpayPayment()
    {
        $result = $this->paymentService->vnpayPayment();
        return response()->json($result);
    }


    public function returnPayment(Request $request)
    {
        dd($request);
    }
    public function return(Request $request)
    {
        $url = session('url_prev', '/');
        if ($request->vnp_ResponseCode == "00") {
            // $this->apSer->thanhtoanonline(session('cost_id'));
            // return redirect($url)->with('success', 'Đã thanh toán phí dịch vụ');
            return 'success';
        }
        session()->forget('url_prev');
        return 'fail';
        // return redirect($url)->with('errors', 'Lỗi trong quá trình thanh toán phí dịch vụ');
    }

    public function callback(Request $request)
    {

        $result = $this->paymentService->returnMomo($request->all());

        if ($result) {
            return redirect()->route('payment.success')->with(['message' => 'Xử lý thành công', 'alert-type' => 'success']);
        }

        return redirect()->route('payment.failure')->with(['message' => 'Có lỗi trong quá trình xử lý', 'alert-type' => 'error']);
    }

    public function paymentSuccess()
    {
        return view('pages.payments.success');
    }

    public function paymentFailure()
    {
        return view('pages.payments.failure');
    }
}
