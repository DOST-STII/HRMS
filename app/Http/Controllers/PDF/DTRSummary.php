<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class DTRSummary extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexback()
    {
        $mon = date('F',mktime(0, 0, 0, request()->mon1, 10));
        $tr = "";

        foreach (getStaffDivision2(request()->division) as $value) {

        $emp = App\User::where('id',request()->id)->first();

        $mon2 = date('F',mktime(0, 0, 0, request()->mon2, 10));
        $yr = request()->year;
        $date = $mon2 ."-" . request()->year;

        $tLates = 0;
        $tUndertime = 0;
        $tLatesWeeks = 0;
        $tUndertimeWeeks = 0;
        $tUndertimeWeeks2 = "";
        $tLastLatesWeeks = 0;
        $tLastUndertimeWeeks = 0;

        $tLateCTR = 0;
        $tUndertimeCTR = 0;

        $grandTotalHrs = 0;
        $grandTotalHrsRendered = 0;


        $tUndertime = 0;
        $totalDeficit = 0;
        $tDaysExcess = 0;
        $tDaysDeficit = 0;
        $tLDaysExcess = 0;
        $tLDaysDeficit = 0;

        $tDeficit = 0;
        $tDaysLeave = 0;
        $tTimeWeek = 0;
        $lastTimeWeek = 0;
        $lastWeekLeaves = 0;

        $week1Time = 1920;

        $totalabsent = 0;

        $rows = "";

        $total = Carbon::parse($date)->daysInMonth;

                      $prevweek = 1;
                      //$rows .= "<tr><td></td><td colspan='8' align='center'>  <b>WEEK 1 </b> </td><td></td></tr>";
                      $week_num = 2;
                      $total_days = 0;
                      for($i = 1;$i <= $total;$i++)
                      {
                          $weeknum = weekOfMonth(date($yr.'-'.request()->mon1.'-'.$i));
                            if($weeknum == $prevweek)
                            {
                              
                            }
                            else
                            {
                              
                            $totalhrsweek = readableTime($tTimeWeek);
                            //$tTotalDays = readableTime($total_days - $tDaysLeave);

                            if($total_days > $tDaysLeave)
                            {
                                $tTotalDays = readableTime($total_days - $tDaysLeave);
                                $grandTotalHrs += $total_days - $tDaysLeave;
                            } 
                            else
                            {
                                $tTotalDays = readableTime(0);
                                $grandTotalHrs += 0;
                            }
                                
                              
                              $grandTotalHrsRendered += $tTimeWeek;
                            
                              //DEFICIT
                              if($tDaysDeficit > $tDaysExcess)
                              {
                                $tDeficit = readableTime($tDaysDeficit - $tDaysExcess);
                                $totalDeficit += $tDaysDeficit - $tDaysExcess;
                              }
                              else
                              {
                                $tDeficit = readableTime(0);
                                $totalDeficit += 0;
                              }


                              $tLatesWeeks = readableTime($tLatesWeeks);
                              $tUndertimeWeeks = readableTime($tUndertimeWeeks);

                              
                                
                              
                              //$rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.")</b> </td><td align='center'><b>".$totalhrsweek."</b></td><td align='center'><b>".$tLatesWeeks."</b></td><td align='center'><b>".$tUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";

                              $prevweek = $weeknum;
                              //$rows .= "<tr><td></td><td colspan='8' align='center'> <b>WEEK $week_num </b> </td><td></td></tr>";
                              $week_num++;
                              $total_days = 0;
                              $tTimeWeek = 0;
                              $tDaysLeave = 0;
                              $tDeficit = 0;
                              $tLatesWeeks = 0;
                              $tUndertimeWeeks = 0;

                              $tDaysExcess = 0;
                              $tDaysDeficit = 0;
                              
                            }
                            $dtr_date = date("Y-m-d",strtotime($yr.'-'.request()->mon1.'-'.$i));

                            //echo $dtr_date."<br/>";

                            $dayDesc = weekDesc($dtr_date);
                            $dtr = getDTRemp($dtr_date,$value->id,1,$value->username);
                            if(!$dtr)
                            {
                                $dtr = array();
                            }

                                switch ($dayDesc) {
                                    case 'Sat':
                                    case 'Sun':
                                        # code...
                                        break;
                                    
                                    default:
                                        if(!checkIfHoliday($dtr_date))
	                                        {
                                                $total_days += 480;
                                            }
                                        break;
                                }

                                
                                if($dtr_date <= '2022-03-06')
                                {
                                    $rows .= showDate($dtr,$dtr_date,$i,$dayDesc,$value->id,1,$value->username,null,null);
                                    
                                    if(checkIfHasLeave($dtr_date,$value->id))
		                            {
                                        $leave = getLeaveDetails($dtr_date,$value->id);
                                        if($leave['leave_deduction_time'] == 'wholeday')
                                        {
                                            $week1Time -= 480;
                                            $tTimeWeek = $week1Time;
                                            $total_days -= 480;
                                        }
                                        else
                                        {
                                            $week1Time -= 240;
                                            $tTimeWeek = $week1Time;
                                            $total_days -= 240;
                                        }
                                    }
                                    else
                                    {
                                        $tTimeWeek = $week1Time;
                                    }
                                }
                                else
                                {
                                    $rowsArr = explode("|",plotDate($dtr,$i,$dayDesc,$dtr_date,$value->id));
                                    $rows .= $rowsArr[0];
                                    $tLates +=(int)$rowsArr[1];
                                    //$tUndertime +=(int)$rowsArr[5];
                                    $tTimeWeek +=(int)$rowsArr[3];
                                    $tDaysLeave +=(int)$rowsArr[4];
                                    $tDaysExcess +=(int)$rowsArr[6];
                                    $tDaysDeficit +=(int)$rowsArr[7];


                                    //$tLatesWeeks += $tLates;
                                    if((int)$rowsArr[1] > 0)
                                    {
                                        $tUndertimeWeeks2 .= $rowsArr[5]."|".(int)$rowsArr[1]."<br/>";
                                        $tLatesWeeks += (int)$rowsArr[1];
                                        $tLateCTR++;
                                    }

                                    if((int)$rowsArr[2] > 0)
                                    {
                                        $tUndertime +=(int)$rowsArr[2];
                                        $tUndertimeWeeks2 .= $rowsArr[5]."|".(int)$rowsArr[2]."<br/>";
                                        $tUndertimeWeeks += (int)$rowsArr[2];
                                        $tUndertimeCTR++;
                                    }

                                    // return $rowsArr;
                                }
                                
                                

                               if($i == $total)
                                {
                                    $lastTimeWeek = $tTimeWeek ;
                                    $lastWeekLeaves = $tDaysLeave;
                                    $tLastLatesWeeks = $tLatesWeeks;
                                    $tLastUndertimeWeeks = $tUndertimeWeeks;
                                    
                                    $tLDaysExcess += $tDaysExcess;
                                    $tLDaysDeficit += $tDaysDeficit;
                                }
                                
                            
                      }

                      //LAST WEEK
                      $totalhrsweek = readableTime($lastTimeWeek);
                      $tTotalDays = readableTime($total_days);
                      $tTotalDays = readableTime($total_days - $lastWeekLeaves);

                    //DEFICIT
                    if($tLDaysDeficit > $tLDaysExcess)
                    {
                      $tDeficit = readableTime($tLDaysDeficit - $tLDaysExcess);
                      $totalDeficit += $tLDaysDeficit - $tLDaysExcess;
                    }
                    else
                    {
                      $tDeficit = readableTime(0);
                      $totalDeficit += 0;
                    }
                        


                      $tLastLatesWeeks = readableTime($tLatesWeeks);
                      $tLastUndertimeWeeks = readableTime($tUndertimeWeeks);


                      //$rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.")</b> </td><td align='center'><b>".$totalhrsweek."</b></td><td align='center'><b>".$tLastLatesWeeks."</b></td><td align='center'><b>".$tLastUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";


        //COUNT NO. LEAVES
        $leaves_total = 0;
        $l1_total = App\Request_leave::where('user_id',$value->id)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',$yr)->get();

        $dtss = "";

        foreach ($l1_total as $key => $value_lv) {
            $dayDesc2 = weekDesc($value_lv->leave_date_from);
            if(!checkIfHoliday($value_lv->leave_date_from))
			{
                if($dayDesc2 != 'Sat' && $dayDesc2 != 'Sun')
                {
                    
                    $leaves_total++;
                    $dtss .= $value_lv->leave_date_from." -- ";
                }
            }
            
        }

        //SINGLE DATE
        $l2_total = App\Request_leave::where('user_id',$value->id)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',request()->year)->sum('leave_deduction');

        $l_total = $leaves_total + $l2_total;

        if($tLates)
            $t1 = "L : ".readableTime($tLates);
        else
            $t1 = '';
        
        if($tUndertime)
            $t2 = "UD : ".readableTime($tUndertime);
        else
            $t2 = '';

        
        

            $tr .= "<tr>
                        <td>".$value->lname.", ".$value->fname." ".substr($value->mname,0,1)."</td>
                        <td align='center'>$t1  $t2</td>
                        <td></td>
                        <td align='center'>".$tLateCTR."</td>
                        <td align='center'>".$l_total."</td>
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
                               @page { margin: 10px; }
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
                                    <h4 style="font-size:12px">
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES 
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                        <br>
                                        <br>
                                        <b>DTR Summary Report</b> 
                                        '.$mon.' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="3">
                                <tr>
                                  <td align="center" style="width:30%"><b>EMPLOYEE NAME</b></td>
                                  <td align="center"><b>LATE/UNDERTIME</b></td>
                                  <td align="center"><b>EXCESS</b></td>
                                  <td align="center"><b>NO. OF LATES</b></td>
                                  <td align="center"><b>NO. OF ABSENCES</b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>Division : '.getDivision(request()->division).'</b></td>
                                </tr>
                                '.$tr.'
                                </table>
                                <br>
                                <br>
                                <br>

                                <table width="100%" cellspacing="0" cellpadding="5" style="font-size:15px!important">
                                <tr>
                                  <td style="width:50%;border: 1px solid #FFF">
                                    Prepared by : <br/>
                                    <b>'.getMarshal(request()->division).'</b>
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
                    

                //COUNT NO. LEAVES
                $leaves_total = 0;
                $l1_total = App\Request_leave::where('user_id',$divs->id)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',$yr)->get();

                $dtss = "";

                foreach ($l1_total as $key => $value_lv) {
                    $dayDesc2 = weekDesc($value_lv->leave_date_from);
                    if(!checkIfHoliday($value_lv->leave_date_from))
                {
                        if($dayDesc2 != 'Sat' && $dayDesc2 != 'Sun')
                        {
                            
                            $leaves_total++;
                            $dtss .= $value_lv->leave_date_from." -- ";
                        }
                    }
                    
                }

                //SINGLE DATE
                $l2_total = App\Request_leave::where('user_id',$divs->id)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',request()->year)->sum('leave_deduction');

                $l_total = $leaves_total + $l2_total;
          }


        $tr .= "<tr><td>".$divs->lname.", ".$divs->fname." ".$divs->mname."</td><td align='center'>".$t1." ".$t2."</td><td align='center'>".readableTime($tDaysExcess)."</td><td align='center'>".ifNull($tLateCTR)."</td><td align='center'>".ifNull($l_total)."</td></tr>";

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
                                    Republic of the Philippines <br>
                                    SCIENCE AND TECHNOLOGY INFORMATION INSTITUTE  <br>
                                    STII Building,DOST Complex, Bicutan, Taguig City
                                        <br>
                                        <br>
                                        <b>DTR SUMMARY</b>
                                        <br>
                                        '.' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td align="center" style="width:30%"><b>EMPLOYEE NAME</b></td>
                                  <td align="center"><b>LATE/UNDERTIME</b></td>
                                  <td align="center"><b>EXCESS</b></td>
                                  <td align="center"><b>NO. OF LATES</b></td>
                                  <td align="center"><b>NO. OF LEAVES</b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>Division : '.getDivision(request()->division).'</b></td>
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
