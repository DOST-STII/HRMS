<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;

class Library extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = 
                [
                    "nav" => nav("payrolllib"),
                ];
        return view('payroll.library')->with("data",$data);
    }

    public function json($tbl)
    {
        switch ($tbl) {
            case 'membership':
                    $t = App\Payroll\Organization::get();
                break;
            case 'loan':
                    $t = App\Payroll\OrganizationService::get();
                break;
            case 'deduction':
                    $t = App\Payroll\Deduc::get();
                break;
            case 'compensation':
                    $t = App\Payroll\Comp::get();
                break;
        }

        return json_encode($t);
    }

}
