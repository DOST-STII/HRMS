<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShortlistMail;

class MailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function notshortlisted()
    {
    	Mail::to('diaz.mark.anthony@gmail.com')->send(new ShortlistMail());
    }
}
