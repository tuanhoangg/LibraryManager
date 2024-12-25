<?php

namespace App\Services;

use App\Repositories\AuthorRepository;

class AuthorService
{
    public function __construct(
        protected AuthorRepository $authorRepository
    ) {
        //
    }

    public function getAuthorList($request)
    {
        return $this->authorRepository->getAuthorList($request);
    }

    public function getAuthor($condition)
    {
        return $this->authorRepository->where($condition)->with('book')->first();
    }

    public function createAuthor($data)
    {

        return $this->authorRepository->create($data);
    }

    public function updateAuthor($data)
    {

        return $this->authorRepository->upsert(['id'], $data);
    }

    public function deleteAuthor($id)
    {
        return $this->authorRepository->delete($id);
    }

    public function getAllAuthor()
    {
        return $this->authorRepository->getAll();
    }
}
