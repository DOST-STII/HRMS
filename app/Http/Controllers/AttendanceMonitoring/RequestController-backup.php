<?php

namespace App\Http\Controllers\AttendanceMonitoring;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;
use Carbon\Carbon;


class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dt() 
    {
        if($this->isWeekend("2021-02-11"))
        {
            return "YES";
        }
        else
        {
            return "NO"; 
        }
    }

    public function isWeekend($dt)
    {
        $dt1 = strtotime($dt);
        $dt2 = date("l", $dt1);
        $dt3 = strtolower($dt2);
        if(($dt3 == "saturday" )|| ($dt3 == "sunday"))
            {
                return true;
            } 
        else
            {
                return false;
            }  
    }

    public function index($type)
    {

        $err_msg = "";
    	switch ($type) {
    		case 'leave':
                $duration = explode("-", request()->leave_duration);
                $date_from = new Carbon($duration[0]);
                $date_to = new Carbon($duration[1]);

                $datediff = strtotime($date_to) - strtotime($date_from);
                $datediff = floor($datediff/(60*60*24));

                
                $err = false;
                for($i = 0; $i < $datediff + 1; $i++){

                    $dt = date("Y-m-d", strtotime($date_from . ' + ' . $i . 'day'));


                    //CHECK IF HAS LEAVE
                    $ctr = App\Request_leave::where('leave_date',$dt)->where('user_id',Auth::user()->id)->get();

                    if(count($ctr) > 0)
                    {
                        $err = true;
                        $err_msg .= "<span class='fas fa-times-circle text-danger'></span> You already filed a leave on <b>".date('M d, Y',strtotime($dt))."</b><br>";
                    }

                    //CHECK IF HOLIDAY

                    $ctr = App\Holiday::where('holiday_date',$dt)->get();

                    if(count($ctr) > 0)
                    {
                        $err = true;
                        $err_msg .= "<span class='fas fa-times-circle text-danger'></span> <b>".date('M d, Y',strtotime($dt))."</b> is a holiday, please choose another date<br>";
                    }
                }




                if(!$err)
                {
                    $diff = ($date_from->diffInDays($date_to)) + 1;

                    if($diff > 1)
                    {
                        $diff = 1;
                    }
                    else
                    {
                        if(request()->leave_time == 'wholeday')
                            {
                                $diff = 1;
                            }
                            else
                            {
                                $diff = 0.5;
                            }
                    }

                    //SAVE LEAVE
                    for($i = 0; $i < $datediff + 1; $i++){
                        $dt = date("Y-m-d", strtotime($date_from . ' + ' . $i . 'day'));

                        if(!$this->isWeekend($dt))
                        {
                            $request = new App\Request_leave;
                            $request->user_id = Auth::user()->id;
                            $request->user_div = Auth::user()->division;
                            $request->leave_date = $dt;
                            $request->leave_id = request()->leave_id;
                            $request->leave_deduction = $diff;
                            $request->save();
                        }
                    }
                    return redirect('/');
                }
                else
                {
                    return view('error-message')->with('error_message',$err_msg);
                }
    		break;
    	}
        
        
    }
}
