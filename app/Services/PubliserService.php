<?php

namespace App\Services;

use App\Models\Publiser;
use App\Repositories\PubliserRepository;

class PubliserService
{
    public function __construct(
        protected PubliserRepository $publiserRepository
    ) {
        //
    }

    public function getPubliserList($request)
    {
        return $this->publiserRepository->getPubliserList($request);
    }

    public function getPubliser($condition)
    {
        return $this->publiserRepository->where($condition)->with('book')->first();
    }

    public function createPubliser($data)
    {

        return $this->publiserRepository->create($data);
    }

    public function updatePubliser($data)
    {

        return $this->publiserRepository->upsert(['id'], $data);
    }

    public function deletePubliser($id)
    {
        return $this->publiserRepository->delete($id);
    }
    public function getAllPubliser()
    {
        return $this->publiserRepository->getAll();
    }
}
