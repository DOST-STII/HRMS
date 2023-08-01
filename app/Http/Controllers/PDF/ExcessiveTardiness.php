<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class ExcessiveTardiness extends Controller
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

        //GET ALL STAFF
        foreach(getStaffDivision2(request()->division) AS $divs)
        {
          $emp = App\User::where('id',$divs->id)->first();

          $mon = date('m',strtotime(request()->mon1));
          $mon2 = date('F',mktime(0, 0, 0, request()->mon1, 10));
          $yr = request()->year;
          $date = $mon2 ."-" . $yr;

          $tLates = 0;
          $tUndertime = 0;
          $tLateUndertime = 0;
          $tLateCTR = 0;
          $tDaysLeave = 0;
          $tDaysExcess = 0;

          $total = Carbon::parse($date)->daysInMonth;
          
          $total_days = 0;
          for($i = 1;$i <= $total;$i++)
          {
            $dtr_date = date("Y-m-d",strtotime($yr.'-'.request()->mon1.'-'.$i));
            $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id'],$emp['username']);
            $dayDesc = weekDesc($dtr_date);
            

            $rowsArr = explode("|",plotDate($dtr,$i,$dayDesc,$dtr_date,$emp['id']));
            $tLateCTR +=(int)$rowsArr[9];

            
                //LATES
                $tLates +=(int)$rowsArr[1];

                //UNDERTIME
                $tUndertime +=(int)$rowsArr[2];

                $tLateUndertime = $tLates + $tUndertime;

                //EXCESS
                $tDaysExcess +=(int)$rowsArr[6];

                if($tLates)
                  $t1 = "L : ".readableTime($tLates);
                else
                    $t1 = '';
                
                if($tUndertime)
                    $t2 = "UD : ".readableTime($tUndertime);
                else
                    $t2 = '';
          }

          if($tLateCTR >= 10)
            {
              $tr .= "<tr><td>".$divs->lname.", ".$divs->fname." ".$divs->mname."</td><td align='center'>".$t1." ".$t2."</td><td align='center'>".readableTime($tDaysExcess)."</td><td align='center'>".ifNull($tLateCTR)."</td></tr>";
            }

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
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                        <br>
                                        <br>
                                        <b>List of Staff with 10 or more Tardy</b>
                                        <br>
                                        '.$mon2.' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="4">
                                <tr>
                                  <td align="center" style="width:30%"><b>EMPLOYEE NAME</b></td>
                                  <td align="center"><b>LATE/UNDERTIME</b></td>
                                  <td align="center"><b>EXCESS</b></td>
                                  <td align="center"><b>NO. OF LATES</b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b>Division : '.getDivision(request()->division).'</b></td>
                                </tr>
                                '.$tr.'
                                </table>
                                <br>
                                <br>
                                <br>

                                <table width="100%" cellspacing="0" cellpadding="4" style="font-size:15px!important">
                                <tr>
                                  <td style="width:50%;border: 1px solid #FFF">
                                    Prepared by : <br/>
                                    <b>'.Auth::user()->lname.', '.Auth::user()->fname.' '.Auth::user()->mname.'</b>
                                  </td>
                                  <td style="border: 1px solid #FFF">
                                    Verified Correct : <br/>
                                    <b>'.getDirector(request()->division).'</b>
                                  </td>
                                </tr>
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    
}
