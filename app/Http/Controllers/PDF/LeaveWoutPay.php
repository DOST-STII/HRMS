<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class LeaveWoutPay extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $mon = date('F',mktime(0, 0, 0, request()->mon1, 10));

        $tr = "";

        foreach (getStaffDivision2(request()->division) as $value) {

            $vl = $this->countLWOP(1,$value->id);
            $sl = $this->countLWOP(2,$value->id);
            $total = $vl + $sl;

            $tr .= "<tr>
                        <td>".$value->lname.", ".$value->fname." ".$value->mname."</td>
                        <td align='center'>".formatNull($vl)."</td>
                        <td align='center'>".formatNull($sl)."</td>
                        <td align='center'>".formatNull($total)."</td>
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
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                        <br>
                                        <br>
                                        LEAVE WITHOUT PAY
                                        <br>
                                        '.$mon.' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="5">

                                <tr>
                                  <td align="center" rowspan="2"><b>EMPLOYEE</b></td>
                                  <td align="center" colspan="2" style="width:10%"><b>LEAVE WITHOUT PAY</b></td>
                                  <td align="center" rowspan="2"><b>Total</b></td>
                                </tr>
                                <tr>
                                  <td align="center" style="width:20%"><b>Vacation</b></td>
                                  <td align="center" style="width:20%"><b>Sick</b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b>Division : '.getDivision(request()->division).'</b></td>
                                </tr>
                                '.$tr.'
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'portrait');
        return $pdf->stream();
    }

    private function countLWOP($leaveid,$userid)
    {
        return App\Request_leave::where('leave_id',$leaveid)->where('lwop','YES')->where('user_id',$userid)->count();
    }
}
