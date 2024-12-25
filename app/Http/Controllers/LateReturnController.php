<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LateReturnController extends Controller
{
    //

    public function index()
    {
        return view('pages.late_return.index');
    }
}
