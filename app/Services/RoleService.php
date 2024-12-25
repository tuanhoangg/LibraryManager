<?php

namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService
{
    public function __construct(
        protected RoleRepository $roleRepository
    ) {
        //
    }

    public function getRoles()
    {
        return $this->roleRepository->getAll();
    }
}
