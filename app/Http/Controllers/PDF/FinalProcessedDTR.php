<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class FinalProcessedDTR extends Controller
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

        $staff = $this->getProcessDTR(request()->division,request()->mon1,request()->year);

        foreach ($staff as $key => $value) {
           $tr .= "<tr>
                    <td>".++$key."</td>
                    <td>".getStaffInfo($value->userid)."</td>
                    <td>".$value->created_at."</td>
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
                                        FINAL PROCESSED DTRs
                                        <br>
                                        '.$mon.' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td style="width:2%"><b>#</b></td>
                                    <td align="center"><b>EMPLOYEE</b></td>
                                    <td align="center"><b>DATE FINAL PROCESSED</b></td>
                                </tr>
                                '.$tr.'
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'portrait');
        return $pdf->stream();
    }

    private function getProcessDTR($div,$mon,$yr)
    {
        $collection = collect(App\DTRProcessed::where('dtr_division',$div)->where('dtr_mon',$mon)->where('dtr_year',$yr)->get());

        return $collection->all();
    }
}
