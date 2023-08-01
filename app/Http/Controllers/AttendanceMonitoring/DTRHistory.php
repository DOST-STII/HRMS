<?php

namespace App\Http\Controllers\AttendanceMonitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Carbon\Carbon;
use Auth;

class DTRHistory extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
    	
    }
}
