<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\MemberService;
use App\Services\MembershipPlanService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    //
    public function __construct(
        protected MembershipPlanService $membershipPlanService,
        protected PaymentService $paymentService,
        protected MemberService $memberService,
    ) {
    }
    public function index()
    {
        return view('pages.membership-plan.index');
    }

    public function membershipPlanOption()
    {
        $memberId = auth()->user()->member_code;
        $plans = $this->membershipPlanService->getAllMembershipPlan();
        $member = $this->memberService->getMemberById($memberId);
        return view('pages.membership-plan.select-plan', compact('plans', 'member'));
    }

    public function membershipRegister(Request $request)
    {
        $data = $request->except('_token', 'payment_method');
        $paymentMethod = $request->payment_method;
        $plan = $this->membershipPlanService->registerMembership($data, $paymentMethod);

        if ($plan) {
            $result = $this->paymentService->momoPayment($plan, Payment::MEMBER_FEE);
            return redirect($result['payUrl']);
        }
        // return view('pages.membership-plan.select-plan', compact('plans'));
    }
}
