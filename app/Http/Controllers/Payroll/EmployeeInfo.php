<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;

class EmployeeInfo extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $emp = App\User::where('usertype','!=','Administrator')->orderBy('lname','ASC')->first();
        $empid = $emp['id'];

            $data = 
                [
                    "nav" => nav("payrollemp"),
                ];

            return redirect('payroll/emp/'.$empid)->with("data",$data);
    }

    public function index2($empid)
    {
        if(isset($empid))
        {
            $data = 
                [
                    "nav" => nav("payrollemp"),
                    'empid' => $empid,
                ];

            return view('payroll.index')->with("data",$data);
        }
        else
        {
            // return view('payroll.index')->with("data",$data);
        }

    }
}
