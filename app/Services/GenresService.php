<?php

namespace App\Services;

use App\Repositories\GenresRepository;

class GenresService
{
    public function __construct(
        protected GenresRepository $genresRepository
    ) {
        //
    }

    public function getGenresList($request)
    {
        return $this->genresRepository->getGenresList($request);
    }

    public function getGenres($condition)
    {
        return $this->genresRepository->where($condition)->with('books')->first();
    }

    public function createGenres($data)
    {

        return $this->genresRepository->create($data);
    }

    public function updateGenres($data)
    {

        return $this->genresRepository->upsert(['id'], $data);
    }

    public function deleteGenres($id)
    {
        return $this->genresRepository->delete($id);
    }

    public function getAllGenres()
    {
        return $this->genresRepository->getAll();
    }
}
