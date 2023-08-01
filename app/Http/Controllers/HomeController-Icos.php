<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App;
use Carbon\Carbon;
use DB;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        if(Auth::user()->usertype == 'Administrator')
        {
            $list = DB::select("SELECT * FROM employee_icos_dtrs GROUP BY MONTH(fldEmpDTRdate),YEAR(fldEmpDTRdate)");
            $data = [
                        "list" => $list,
                        "nav" => nav("icos"),
                    ];
            return view('dtr.icos-months')->with("data",$data);
        }
        elseif(Auth::user()->usertype == 'Director')
        {
            $emp = App\View_user::where('id',Auth::user()->id)->first();
            $data = [
                        "empinfo" => $emp,
                        "nav" => nav("dashboard"),
                    ];
            return view('pis.director.index')->with("data",$data);
        }
        else
        {
            if(Auth::user()->employment_id == 1)
            {
                $list = DB::select("SELECT * FROM employee_icos_dtrs WHERE division = '".Auth::user()->division."' GROUP BY division,MONTH(fldEmpDTRdate),YEAR(fldEmpDTRdate)");
                // $list = DB::select("SELECT * FROM employee_icos_dtrs WHERE processed IS NULL GROUP BY fldEmpCode,MONTH(fldEmpDTRdate)");
                $data = [
                            "list" => $list,
                            "nav" => nav("icos"),
                        ];
                return view('dtr.icos-months')->with("data",$data);
            }
            else
            {
                $emp = App\View_users_temp::where('id',Auth::user()->id)->first();
                $data = [
                        "empinfo" => $emp,
                        "nav" => nav("dashboard"),
                    ];
                return view('pis.staff.index-cos')->with("data",$data);
            }

            
        }
        
    }

    public function changepassword()
    {
        $user = App\User::where('id',Auth::user()->id)
                        ->update([
                                    "password" => bcrypt(request()->password),
                                ]);
    }
}
