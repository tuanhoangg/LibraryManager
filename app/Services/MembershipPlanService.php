<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\MembershipPlanRepository;
use App\Repositories\MembershipRegisterRepository;
use App\Repositories\PaymentRepository;

class MembershipPlanService
{
    public function __construct(
        protected MembershipPlanRepository $membershipPlanRepository,
        protected MembershipRegisterRepository $membershipRegisterRepository,
        protected PaymentRepository $paymentRepository,
    ) {
        //
    }

    public function getMembershipPlanList($request)
    {
        return $this->membershipPlanRepository->getMembershipPlanList($request);
    }

    public function getMembershipPlan($condition)
    {
        return $this->membershipPlanRepository->where($condition)->first();
    }

    public function getMembershipPlanById($id)
    {
        return $this->membershipPlanRepository->where(['id' => $id])->first();
    }


    public function createMembershipPlan($data)
    {

        return $this->membershipPlanRepository->create($data);
    }

    public function updateMembershipPlan($data)
    {

        return $this->membershipPlanRepository->upsert(['id'], $data);
    }

    public function deleteMembershipPlan($id)
    {
        return $this->membershipPlanRepository->delete($id);
    }

    public function getAllMembershipPlan()
    {
        return $this->membershipPlanRepository->getAll();
    }

    public function registerMembership($data, $paymentMethod)
    {
        // dd($data);
        $result = $this->membershipRegisterRepository->create($data);

        if ($result) {
            // $payment = $result;
            $result['reference_type'] = Payment::MEMBER_FEE;
            $result['type'] = $paymentMethod;
            $result['status'] = Payment::STATUS_PENDING;
            $result['invoice_id'] = $result['id'];
            // dd($payment->toArray());
            $this->paymentRepository->create($result->toArray());
        }
        return $result;
    }
}
