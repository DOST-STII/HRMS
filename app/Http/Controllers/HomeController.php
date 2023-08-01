<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App;
use Carbon\Carbon;
use lluminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function down()
    // {
    //     return view('welcome');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        if(Auth::user()->usertype == 'Administrator')
        {
            return redirect('admin/dashboard/ALL');    
        }
        // elseif(Auth::user()->usertype == 'Director')
        // {
        //     $emp = App\View_user::where('id',Auth::user()->id)->first();
        //     $data = [
        //                 "empinfo" => $emp,
        //                 "nav" => nav("dashboard"),
        //             ];
        //     return view('pis.director.index')->with("data",$data);
        // }
        else
        {
            $mon = date('m');
            $yr = date('Y');
            $weeknum= weekOfMonth(date('Y-m-d'));
            $empid = Auth::user()->id;

            if(isset(request()->frm_year))
               {
                    $mon = request()->frm_mon;
                    $yr = request()->frm_year;
                    $empid = request()->empid;
               } 

            if(Auth::user()->employment_id == 1 || Auth::user()->employment_id == 13 || Auth::user()->employment_id == 14 || Auth::user()->employment_id == 15)
            {
                $emp = App\View_user::where('id',$empid)->first();
            }
            elseif(Auth::user()->employment_id == 8 || Auth::user()->employment_id == 5)
            {
                $emp = App\User::where('id',$empid)->first();
            }

            $data = [
                        "mon" => $mon,
                        "yr" => $yr,
                        "weeknum" => $weeknum,
                        "empinfo" => $emp,
                        "nav" => nav("dashboard")
                    ];
            
            
            return view('pis.staff.index')->with("data",$data);
        }
        
    }


    public function index2($month,$year,$staffid,$weeknum)
    {
        
        if(Auth::user()->usertype == 'Administrator')
        {
            return redirect('admin/dashboard/ALL');    
        }
        // elseif(Auth::user()->usertype == 'Director')
        // {
        //     $emp = App\View_user::where('id',Auth::user()->id)->first();
        //     $data = [
        //                 "empinfo" => $emp,
        //                 "nav" => nav("dashboard"),
        //             ];
        //     return view('pis.director.index')->with("data",$data);
        // }
        else
        {
            $mon = date('m');
            $yr = date('Y');
            $empid = Auth::user()->id;

            if(isset($mon))
               {
                    $mon = $month;
                    $yr = $year;
                    $empid = $staffid;
                    $weeknum = $weeknum;
               } 

            if(Auth::user()->employment_id == 1)
            {
                $emp = App\View_user::where('id',$empid)->first();
            }
            else
            {
                $emp = App\View_users_temp::where('id',$empid)->first();
            }

            

            $data = [
                        "mon" => $mon,
                        "yr" => $yr,
                        "weeknum" => $weeknum,
                        "empinfo" => $emp,
                        "nav" => nav("dashboard")
                    ];
            return view('pis.staff.index')->with("data",$data);
        }
        
    }

    public function index3($month,$year,$staffid,$weeknum)
    {
        
        if(Auth::user()->usertype == 'Administrator')
        {
            return redirect('admin/dashboard/ALL');    
        }
        // elseif(Auth::user()->usertype == 'Director')
        // {
        //     $emp = App\View_user::where('id',Auth::user()->id)->first();
        //     $data = [
        //                 "empinfo" => $emp,
        //                 "nav" => nav("dashboard"),
        //             ];
        //     return view('pis.director.index')->with("data",$data);
        // }
        else
        {
            $mon = date('m');
            $yr = date('Y');
            $empid = Auth::user()->id;

            if(isset($mon))
               {
                    $mon = $month;
                    $yr = $year;
                    $empid = $staffid;
                    $weeknum = $weeknum;
               } 

            if(Auth::user()->employment_id == 1)
            {
                $emp = App\View_user::where('id',$empid)->first();
            }
            else
            {
                $emp = App\View_users_temp::where('id',$empid)->first();
            }

            

            $data = [
                        "mon" => $mon,
                        "yr" => $yr,
                        "weeknum" => $weeknum,
                        "empinfo" => $emp,
                        "nav" => nav("dashboard")
                    ];
            return view('pis.staff.index-marshal3')->with("data",$data);
        }
        
    }

    private function getDTR($userid,$mon,$yr)
    {
        $dtr = App\Employee_dtr::where('user_id',$userid)->whereMonth('fldEmpDTRdate',$mon)->whereYear('fldEmpDTRdate',$yr)->get();
        // $dtr = collect($dtr);
        // return $dtr->all();

        return $dtr;
    }

    public function changepassword()
    {
        $user = App\User::where('id',Auth::user()->id)
                        ->update([
                                    "password" => bcrypt(request()->password),
                                ]);
        return redirect('change-password');
    }
}
