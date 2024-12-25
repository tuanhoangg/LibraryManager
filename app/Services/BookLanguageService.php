<?php

namespace App\Services;

use App\Models\BookLanguage;
use App\Repositories\BookLanguageRepository;

class BookLanguageService
{
    public function __construct(
        protected BookLanguageRepository $bookLanguageRepository
    ) {
        //
    }

    public function getBookLanguageList($request)
    {
        return $this->bookLanguageRepository->getBookLanguageList($request);
    }

    public function getBookLanguage($condition)
    {
        return $this->bookLanguageRepository->where($condition)->with('book_item')->first();
    }

    public function createBookLanguage($data)
    {

        return $this->bookLanguageRepository->create($data);
    }

    public function updateBookLanguage($data)
    {

        return $this->bookLanguageRepository->upsert(['id'], $data);
    }

    public function deleteBookLanguage($id)
    {
        return $this->bookLanguageRepository->delete($id);
    }

    public function getAllBookLanguage()
    {
        return $this->bookLanguageRepository->getAll();
    }
}
