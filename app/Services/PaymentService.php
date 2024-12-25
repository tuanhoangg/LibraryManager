<?php

namespace App\Services;

use App\Helper\MemberHelper;
use App\Models\LateReturn;
use App\Models\Members;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\FinesRepository;
use App\Repositories\LateReturnRepository;
use App\Repositories\MemberRepository;
use App\Repositories\MembershipPlanRepository;
use App\Repositories\MembershipRegisterRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected MemberRepository $memberRepository,
        protected UserRepository $userRepository,
        protected MembershipPlanRepository $membershipPlanRepository,
        protected MembershipRegisterRepository $membershipRegisterRepository,
        protected LateReturnRepository $lateReturnRepository,
        protected FinesRepository $finesRepository,
    ) {
        //
    }
    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function momoPayment($data, $type)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        // $endpoint = "https://test-payment.momo.vn/gw_payment/transactionProcessor";

        $partnerCode = config('payment.momo_partner_code');
        $accessKey = config('payment.momo_access_key');
        $secretKey = config('payment.momo_secret_key');
        // dd($secretKey);
        $orderInfo = "Thanh toán qua MoMo";
        $orderId = $data['id'];
        $returnUrl = route('payment.callback');
        $notifyurl = route('payment.callback');
        $bankCode = "SML";
        $amount = $data['amount'];
        $requestId = $data['id'];
        $requestType = "payWithATM";
        $extraData = "";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $notifyurl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $returnUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl' => $notifyurl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
            'type' => $type,
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        // dd($result);
        $jsonResult = json_decode($result, true);
        // dd($jsonResult);
        return $jsonResult;
    }

    public function vnpayPayment()
    {
        $vnp_TmnCode = '6ECX5DGL'; // Mã website tại VNPAY 
        $vnp_HashSecret = '0U7UIA0B80D7EGDNAYPH5Y63ZZ63OBSY'; // Chuỗi bí mật
        $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

        $vnp_TxnRef = '12322aws';
        $vnp_Returnurl = route('return-payment');
        $vnp_OrderInfo = 'billpayment';
        $vnp_OrderType = 'tpye';
        $vnp_Amount = 10000000;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = request()->ip();
        // Billing
        $vnp_Bill_Mobile = '0987654321';
        $vnp_Bill_Email = 'nguy@gmail.com';
        $fullName = 'Nguyen Van A';
        if (isset($fullName) && trim($fullName) != '') {
            $name = explode(' ', $fullName);
            $vnp_Bill_FirstName = array_shift($name);
            $vnp_Bill_LastName = array_pop($name);
        }
        $vnp_Bill_Address = '123 Nguyen Trai';
        $vnp_Bill_City = 'Ho Chi Minh';
        $vnp_Bill_Country = 'VN';
        $vnp_Bill_State = ''; // Optional: State/Province
        $vnp_ExpireDate = '20240630120000';
        // Invoice
        $vnp_Inv_Phone = '0987654321';
        $vnp_Inv_Email = 'nguy@gmail.com';
        $vnp_Inv_Customer = 'Nguyen Van A';
        $vnp_Inv_Address = '123 Nguyen Trai';
        $vnp_Inv_Company = 'ABC Corp';
        $vnp_Inv_Taxcode = '0123456789';
        $vnp_Inv_Type = 'I';

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
            "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            "vnp_Bill_Email" => $vnp_Bill_Email,
            "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            "vnp_Bill_LastName" => $vnp_Bill_LastName,
            "vnp_Bill_Address" => $vnp_Bill_Address,
            "vnp_Bill_City" => $vnp_Bill_City,
            "vnp_Bill_Country" => $vnp_Bill_Country,
            "vnp_Inv_Phone" => $vnp_Inv_Phone,
            "vnp_Inv_Email" => $vnp_Inv_Email,
            "vnp_Inv_Customer" => $vnp_Inv_Customer,
            "vnp_Inv_Address" => $vnp_Inv_Address,
            "vnp_Inv_Company" => $vnp_Inv_Company,
            "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            "vnp_Inv_Type" => $vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        ksort($inputData);
        $query = "";
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata == "") ? $key . "=" . $value : '&' . $key . "=" . $value;
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        $has_data = hash('sha256', $vnp_HashSecret . $hashdata);
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        dd($vnp_Url);
        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );

        return $returnData;
    }


    public function returnVnpay()
    {
        // $url = session('url_prev','/');
        // if($request->vnp_ResponseCode == "00") {
        //     $this->apSer->thanhtoanonline(session('cost_id'));
        //     return redirect($url)->with('success' ,'Đã thanh toán phí dịch vụ');
        // }
        // session()->forget('url_prev');
        // return redirect($url)->with('errors' ,'Lỗi trong quá trình thanh toán phí dịch vụ');
    }

    public function returnMomo($data)
    {
        $payment = $this->paymentRepository->findOne(['invoice_id' => $data['orderId'] ?? '']);

        if (!$payment) {
            Log::error('Payment not found', $data);
            return false;
        }
        // dd($data);
        if ($data['resultCode'] != 0) {
            $payment->status = Payment::STATUS_FAILED;
            $payment->save();

            Log::warning('Payment failed', $data);
            return false;
        }

        $rawHash = "accessKey=" . config('payment.momo_access_key') . "&amount=" . $data['amount'] . "&extraData=" . $data['extraData'] . "&message=" . $data['message'] . "&orderId=" . $data['orderId'] . "&orderInfo=" . $data['orderInfo'] . "&orderType=" . $data['orderType'] . "&partnerCode=" . $data['partnerCode'] . "&payType=" . $data['payType'] . "&requestId=" . $data['requestId'] . "&responseTime=" . $data['responseTime'] . "&resultCode=" . $data['resultCode'] . "&transId=" . $data['transId'];
        $signature = hash_hmac("sha256", $rawHash, config('payment.momo_secret_key'));

        if ($signature == $data['signature']) {
            if ($payment->reference_type == Payment::MEMBER_FEE) {
                $plan = $this->membershipRegisterRepository->findOne(['id' => $payment->invoice_id]);
                $member = $this->memberRepository->findOne(['user_id' => Auth::user()->id]);

                $members['user_id'] = Auth::user()->id ?? null;
                $members['membership_plan_id'] = $plan->plan_id;
                $members['status'] = Members::VALID;
                $members['due_date'] = Carbon::now()->addMonth();
                $members['member_code'] = $member ? $member->member_code : MemberHelper::generateMemberCode();

                $result = Members::updateOrCreate(['user_id' => $members['user_id']], $members);
                // $user = $this->memberRepository->findOne(['member_code' => $member['member_code']]);


                $this->userRepository->update($members['user_id'], ['is_member' => User::IS_MEMBER, 'member_code' => $members['member_code'], 'member_id' => $result->id]);


                Log::info('Payment successful', $data);
            } else if ($payment->reference_type == Payment::PENALTIES_FEE) {
                $lateReturn = $this->lateReturnRepository->findOne(['id' => $payment->invoice_id]);

                $this->lateReturnRepository->update($lateReturn->id, ['status' => LateReturn::STATUS_SUCCESS]);
                $this->finesRepository->updateCondition([['reference_id', '=', $lateReturn->id]], ['status' => LateReturn::STATUS_SUCCESS]);
            } else if ($payment->reference_type == Payment::FINES_FEE) {
                $lateReturn = $this->finesRepository->findOne(['id' => $payment->invoice_id]);

                $this->finesRepository->update($lateReturn->id, ['status' => LateReturn::STATUS_SUCCESS]);
            }

            $this->paymentRepository->update($payment->id, ['status' => Payment::STATUS_SUCCESS]);
            return true;
        }
    }

    public function createPayment($data, $paymentMethod, $referenceType)
    {
        $createData['user_id'] = $data->user_id;
        $createData['reference_type'] = $referenceType;
        $createData['type'] = $paymentMethod;
        $createData['amount'] = $data->late_fee;
        $createData['status'] = Payment::STATUS_PENDING;
        $createData['invoice_id'] = $data->id;

        $this->paymentRepository->create($createData);
    }
}
