<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class DailyMonitoring extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $date = date('F, m d',strtotime(request()->calendarsingle));

        $dtr = $this->getDTRMonitoring(request()->division,request()->calendarsingle);

        $tr = "";
        foreach ($dtr as $value) {
            $tr .= "<tr>
                        <td>".$value->employee_name."</td>
                        <td align='center'>".$value->fldEmpDTRamIn."</td>
                        <td align='center'>".$value->fldEmpDTRamOut."</td>
                        <td align='center'>".$value->fldEmpDTRpmIn."</td>
                        <td align='center'>".$value->fldEmpDTRpmOut."</td>
                    </tr>";
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - REPORT</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                               @page { margin: 20px; }
                                body
                                {
                                    font-family:Helvetica;
                                    margin: 0px; 
                                }
                                th,td
                                {
                                    border:1px solid #555;
                                    font-size:11px;
                                }
                            </style>
                            <body>
                                <p align="right" border="0" style="font-size:8px">Date Printed : '.Carbon::now().'</p>
                                <center>
                                    <h4 style="font-size:15px">
                                        MONITORING OF EMPLOYEES WITH INCOMPLETE DTR
                                        <br>
                                        '.$date.'
                                        <br/>
                                        Division : '.getDivision(request()->division).'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td align="center" rowspan="2"><b>EMPLOYEE</b></td>
                                  <td align="center" colspan="2" style="width:30%"><b>AM</b></td>
                                  <td align="center" colspan="2" style="width:30%"><b>PM</b></td>
                                </tr>
                                <tr>
                                  <td align="center"><b>IN</b></td>
                                  <td align="center"><b>OUT</b></td>
                                  <td align="center"><b>IN</b></td>
                                  <td align="center"><b>OUT</b></td>
                                </tr>
                                '.$tr.'
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'portrait');
        return $pdf->stream();
    }

    private function getDTRMonitoring($div,$date)
    {
        $collection = collect(App\Employee_dtr::where('division',$div)->where('fldEmpDTRdate',$date)->get());
        return $collection->all();
    }
}
