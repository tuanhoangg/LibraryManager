<?php

namespace App\Http\Controllers;

use App\Services\PubliserService;
use Illuminate\Http\Request;

class PubliserController extends Controller
{
    //
    public function __construct(
        protected PubliserService $publiserService
    ) {
    }


    public function index()
    {
        return view('pages.publiser.index');
    }
}
