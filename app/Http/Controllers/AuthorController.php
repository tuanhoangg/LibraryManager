<?php

namespace App\Http\Controllers;

use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    //
    public function __construct(
        protected AuthorService $authorService
    ) {
    }

    public function index()
    {
        return view('pages.author.index');
    }
}
