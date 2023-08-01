<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    //
     //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
                    "nav" => nav("calendar")
                ];

        return view('calendar.index')->with("data",$data);
    }
}
