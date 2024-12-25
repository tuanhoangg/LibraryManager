<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookLanguageController extends Controller
{
    //
    public function index()
    {
        return view('pages.book-language.index');
    }
}
