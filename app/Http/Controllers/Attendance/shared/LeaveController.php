<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class LeaveController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function apply()
    {
        $leave = new App\Employee_leave_apply;
        $leave->user_id = Auth::user()->id;
        $leave->save();
    }
}
