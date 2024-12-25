<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinesController extends Controller
{
    //

    public function index()
    {
        return view('pages.fines.index');
    }
}
