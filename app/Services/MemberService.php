<?php

namespace App\Services;

use App\Repositories\MemberRepository;

class MemberService
{
    public function __construct(
        protected MemberRepository $memberRepository,
    ) {
        //
    }

    public function getMemberList($request)
    {
        return $this->memberRepository->getMemberList($request);
    }

    public function getMemberById($id)
    {
        return $this->memberRepository->where(['member_code' => $id])->with('membership_plan', 'user', 'borrowedHistory')->first();
    }

    public function updateMemberStatusJob()
    {
        return $this->memberRepository->updateMemberStatusJob();
    }
}
