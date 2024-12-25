<?php

namespace App\Http\Controllers;

use App\Mail\SendMailFines;
use App\Mail\SendMailLateReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    //

    public function sendMailLateReturn(Request $request)
    {
        $data = $request->data;

        Mail::to($request->user())->send(new SendMailLateReturn($data));
    }

    public function sendMailFines(Request $request)
    {
        $data = $request->data;

        Mail::to($request->user())->send(new SendMailFines($data));
    }
}
