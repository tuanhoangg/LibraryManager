<?php

namespace App\Http\Controllers;

use App\Services\GenresService;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    //

    public function __construct(
        protected GenresService $genresService
    ) {
    }

    public function index()
    {
        return view('pages.genres.index');
    }
}
