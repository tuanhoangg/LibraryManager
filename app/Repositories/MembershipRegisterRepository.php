<?php

namespace App\Repositories;

use App\Models\MembershipRegister;

class MembershipRegisterRepository extends BaseRepository
{
    function getModel()
    {
        return MembershipRegister::class;
    }
}
