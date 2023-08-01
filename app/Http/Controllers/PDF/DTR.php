<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class DTR extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $emp = App\User::where('id',request()->userid)->first();

        $rows = "";
        
        $mon = date('m',strtotime(request()->dtr_mon));
        $mon2 = date('F',mktime(0, 0, 0, request()->dtr_mon, 10));

        $yr = request()->dtr_year;
        $date = $mon2 ."-" . request()->dtr_year;
        $month = $mon + 1;

        $tUndertime = null;

        $tLates = null;

        $totalAb = null;

        // return $mon;

        $total = Carbon::parse($date)->daysInMonth;
                      $prevweek = 1;
                      $rows .= "<tr><td colspan='8' align='center'>  <b>WEEK 1 </b> </td></tr>";
                      $week_num = 2;
                      for($i = 1;$i <= $total;$i++)
                      {

                        
                          $weeknum = weekOfMonth(date($yr.'-'.$mon.'-'.$i)) + 1;
                            if($weeknum == $prevweek)
                            {
                              
                            }
                            else
                            {
                              $prevweek = $weeknum;
                              $rows .= "<tr><td colspan='8' align='center'> <b>WEEK $week_num </b> </td></tr>";
                              $week_num++;
                            }


                            $dtr_date = date("Y-m-d",strtotime($yr.'-'.$mon.'-'.$i));

                            $dayDesc = weekDesc($dtr_date);

                            $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id']);

                            //UDERTIME
                            $under = null;
                            if(countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut']))
                            {
                                $undertime = explode("|", countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut']));
                                $under = $undertime[0];
                                $tUndertime += $undertime[1];
                            }

                            //LATES
                            $lates = null;
                            if(computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn']))
                            {
                                $late = explode("|",computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn']));
                                $lates = $late[0];
                                $tLates += $late[1];
                            }

                            switch ($dayDesc) {
                                case 'Sat':
                                case 'Sun':
                                    # code...
                                    break;
                                
                                default:
                                        if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null)
                                        {
                                            $totalAb++;
                                        }
                                    break;
                            }

                            $aIn = formatTime($dtr['fldEmpDTRamIn']);
                            $aOut = formatTime($dtr['fldEmpDTRamOut']);
                            $pIn = formatTime($dtr['fldEmpDTRpmIn']);
                            $pOut = formatTime($dtr['fldEmpDTRpmOut']);
                            $tTotal = countTotalTime($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'],$dtr_date,$dayDesc);

                            // if($dtr['dtr_remarks'] == 'Holiday')
                            //   {
                            //     $aIn = "";
                            //     $aOut = "";
                            //     $pIn = "";
                            //     $pOut = "";
                            //     $tTotal = "";
                            //   }
                             $rows .=  "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='width:8%'>".$aIn."</td><td align='center' style='width:8%'>".$aOut."</td><td align='center' style='width:8%'>".$pIn."</td><td align='center' style='width:8%'>".$pOut."</td><td align='center' style='width:15%'>".$tTotal."</td><td align='center' style='width:15%'>".$lates." ".$under."</td><td align='center'>".$dtr['dtr_remarks']."</td></tr>";

                               
                        
                      }
        //DISPLAY LATES
        $hourslate = floor($tLates / 60);
        $minuteslate = $tLates % 60;

        //DISPLAY UNDERTIME
        $hoursunder = floor($tUndertime / 60);
        $minutesunder = $tUndertime % 60;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - DTR</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
@page {
  size: 21cm 29.7cm;
  margin: 20;
}
                                body
                                {
                                    font-family:Helvetica;
                                }
                                th,td
                                {
                                    border:1px solid #555;
                                    font-size:11px;
                                }
                            </style>
                            <body>
                                <center>
                                    <h4 style="font-size:12px">
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
                                        RESEARCH AND DEVELOPMENT
                                        Los Baños, Laguna
                                    </h4>
                                </center>
                                <center><h3><b>DTR '.$mon2.'  '.request()->dtr_year.'</b></h3></center>
                                <table width="100%" cellspacing="0" cellpadding="5">
                                    <tr>
                                        <th><b>Day</b></th><th><center><b>AM In</b></center></th><th><center><b>AM Out</b></center></th><th><center><b>PM In</b></center></th><th><center><b>PM Out</b></center></th><th><center><b>Total Hours</b></center></th><th><center><b>Lates<br/>Undertime</b></center><th><center><b>Remarks</b></center></th>
                                    </tr>
                                    <tbody>
                                        '.$rows.'
                                    </tbody>
                                </table>
                                <br>
                                <br>
                                <br>
                                <table width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:12px">
                                        <b>Total Lates : </b> '.$hourslate.'h '.$minuteslate.'m<br>
                                        <b>Total Undertime : </b>'.$hoursunder.'h '.$minutesunder.'m<br>
                                        <b>Total Absences : </b>'.$totalAb.'<br><br><br>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:15px" align="center">'.$emp['fname'].' '.$emp['mname'].' '.ucwords(strtolower($emp['lname'])).'<br><small><b>Name of Employee</b></small></td>
                                  <td style="border : 1px solid #FFF;font-size:15px" align="center">'.getDirector($emp['division']).'<br><small><b>Division Director</b></small></td>
                                <tr>
                                </table>
                            </body>
                            </html>')
        ->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

}
