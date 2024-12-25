<?php

namespace App\Repositories;

use App\Models\Roles;

class RoleRepository extends BaseRepository
{

    function getModel()
    {
        return Roles::class;
    }
}
