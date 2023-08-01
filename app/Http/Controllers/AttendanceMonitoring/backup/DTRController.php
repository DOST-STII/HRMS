<?php

namespace App\Http\Controllers\AttendanceMonitoring;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class DTRController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function icos($mon,$year)
    {
        // if(Auth::user()->usertype == 'Administrator')
        // {
        //     $list = DB::select("SELECT * FROM employee_icos_dtrs WHERE MONTH(fldEmpDTRdate) = $mon AND YEAR(fldEmpDTRdate) = $year GROUP BY fldEmpCode,MONTH(fldEmpDTRdate),YEAR(fldEmpDTRdate)");
        // }
        // else
        // {
        //     $list = DB::select("SELECT * FROM employee_icos_dtrs WHERE division = '".Auth::user()->division."' AND MONTH(fldEmpDTRdate) = $mon AND YEAR(fldEmpDTRdate) = $year GROUP BY division,fldEmpCode,MONTH(fldEmpDTRdate),YEAR(fldEmpDTRdate)");
        // }
        
        $list = App\User::where('division',Auth::user()->division)->where('employment_id',8)->get();
        
        $data = [
                    "list" => $list,
                    "nav" => nav("icos"),
                    "mon" => $mon,
                    "year" => $year,
                ];
        return view('dtr.icos')->with("data",$data);
    }
    
    public function icosmonth()
    {
        $list = DB::select("SELECT * FROM employee_icos_dtrs WHERE division = '".Auth::user()->division."' GROUP BY division,MONTH(fldEmpDTRdate),YEAR(fldEmpDTRdate)");
        // $list = DB::select("SELECT * FROM employee_icos_dtrs WHERE processed IS NULL GROUP BY fldEmpCode,MONTH(fldEmpDTRdate)");
        $data = [
                    "list" => $list,
                    "nav" => nav("icos"),
                ];
        return view('dtr.icos-months')->with("data",$data);
    }

    public function terminal()
    {
        return view('dtr.terminal');
    }
    
    public function password()
    {
        $data = [
                    "nav" => nav("icos"),
                ];
        return view('dtr.change-password')->with("data",$data);
    }

    public function edit()
    {

        if(request()->action == 'view')
        {
           $emp = App\User::where('id',request()->userid2)->first();
           $fullname = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
           $mon = request()->mon2;
           $yr = request()->yr2;
        }
        elseif(request()->action == 'new')
        {
            $emp = App\User::where('id',request()->userid)->first();

            //CHECK IF MAY TIME NA
            $dtr_check = App\Employee_dtr::whereDate('fldEmpDTRdate',request()->wfh_date)->where('user_id',$emp['id'])->first();


            if($dtr_check)
            {
                 switch (request()->dtrStatusTime) {
                    case 'AM':
                        $dtr_edit = App\Employee_dtr::where('id',$dtr_check['id'])
                                         ->update([
                                                    "fldEmpDTRamIn" => "8:00:00",
                                                    "fldEmpDTRamOut" => "12:00:00",
                                                    "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                                 ]);
                    break;

                    case 'PM':
                        $dtr_edit = App\Employee_dtr::where('id',$dtr_check['id'])
                                         ->update([
                                                    "fldEmpDTRpmIn" => "13:00:00",
                                                    "fldEmpDTRpmOut" => "17:00:00",
                                                    "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                                 ]);
                    break;

                    default:
                        $dtr_edit = App\Employee_dtr::where('id',$dtr_check['id'])
                                         ->update([
                                                    "fldEmpDTRamIn" => "8:00:00",
                                                    "fldEmpDTRamOut" => "12:00:00",
                                                    "fldEmpDTRpmIn" => "13:00:00",
                                                    "fldEmpDTRpmOut" => "17:00:00",
                                                    "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                                 ]);
                    break;
                }
            }
            else
            {
                $wfh = 0;
                if(request()->dtrStatus == 'WFH')
                {
                    $wfh = 1;
                }

                switch (request()->dtrStatusTime) {
                    case 'AM':
                            //ADD
                            $dtr = new App\Employee_dtr;
                            $dtr->user_id = $emp['id'];
                            $dtr->fldEmpCode = $emp['username'];
                            $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                            $dtr->division = $emp['division'];
                            $dtr->fldEmpDTRdate = request()->wfh_date;
                            $dtr->fldEmpDTRamIn = "8:00:00";
                            $dtr->fldEmpDTRamOut = "12:00:00";
                            $dtr->wfh = $wfh;
                            $dtr->dtr_remarks = request()->dtrStatus . " - ". request()->wfh_reason;
                            $dtr->save();
                        break;

                    case 'PM':
                            //ADD
                            $dtr = new App\Employee_dtr;
                            $dtr->user_id = $emp['id'];
                            $dtr->fldEmpCode = $emp['username'];
                            $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                            $dtr->division = $emp['division'];
                            $dtr->fldEmpDTRdate = request()->wfh_date;
                            $dtr->fldEmpDTRpmIn = "13:00:00";
                            $dtr->fldEmpDTRpmOut = "17:00:00";
                            $dtr->wfh = $wfh;
                            $dtr->dtr_remarks = request()->dtrStatus . " - ". request()->wfh_reason;
                            $dtr->save();
                        break;
                    
                    default:
                            //ADD
                            $dtr = new App\Employee_dtr;
                            $dtr->user_id = $emp['id'];
                            $dtr->fldEmpCode = $emp['username'];
                            $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                            $dtr->division = $emp['division'];
                            $dtr->fldEmpDTRdate = request()->wfh_date;
                            $dtr->fldEmpDTRamIn = "8:00:00";
                            $dtr->fldEmpDTRamOut = "12:00:00";
                            $dtr->fldEmpDTRpmIn = "13:00:00";
                            $dtr->fldEmpDTRpmOut = "17:00:00";
                            $dtr->wfh = $wfh;
                            $dtr->dtr_remarks = request()->wfh_reason;
                            $dtr->save();
                        break;
                }
                
            }

            $fullname = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
            $mon = request()->wfh_mon;
            $yr = request()->wfh_yr;
        }
        elseif(request()->action == 'edit')
        {
            $dtr = App\Employee_dtr::where('id',request()->dtr_id)
                                     ->update([
                                                request()->dtr_col => request()->dtr_val
                                             ]);

            $emp = App\User::where('id',request()->userid2)->first();
            $fullname = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
            $mon = request()->edit_mon;
            $yr = request()->edit_yr;

            //ADD HISTORY
            $this->addHistory();
        }

        $data = [
                    "emp" => $emp,
                    "fullname" => $fullname,
                    "yr" => $yr,
                    "mon" => $mon,
                ];

        return view('dtr.dtr-emp-edit')->with('data',$data);
    }

    public function edit2(Request $request)
    {
        $userid = $request->userid2;
        $mon = $request->mon2;
        $yr = $request->yr2;
        $emp = App\User::where('id',$userid)->first();

        $data = [
                    "emp" => $emp,
                    "fullname" => $emp['lname'].', '.$emp['fname'].' '.$emp['mname'],
                    "yr" => $yr,
                    "mon" => $mon,
                ];
        return view('dtr.dtr-edit')->with('data',$data);
    }

    public function monitor()
    {
        $user = App\User::where('id',request()->userid2)->first();

        // return request()->yr2;

        if(checkifIcos(request()->userid2))
        {
            $dtr = App\Employee_icos_dtr::whereYear('fldEmpDTRdate',request()->yr2)->whereMonth('fldEmpDTRdate',request()->mon2)->where('user_id',request()->userid2)->get();
        }
        else
        {
            $dtr = App\Employee_dtr::whereYear('fldEmpDTRdate',request()->yr2)->whereMonth('fldEmpDTRdate',request()->mon2)->where('user_id',request()->userid2)->get();
        }

        // return $dtr;

        $data = [
                    'date' => date('F',mktime(0, 0, 0, request()->mon2, 10)).' '.request()->yr2,
                    'user' => $user['lname'] . ', ' . $user['fname'],
                    'dtr' => $dtr,
                ];
        return view('dtr.dtr-monitoring')->with('data',$data);
    }

    public function pdf()
    {
        // return request()->mon2;
        $worksched = getDTROption();

        $emp = App\User::where('id',request()->userid2)->first();

        $rows = "";
        
        $mon = date('m',strtotime(request()->mon2));
        $mon2 = date('F',mktime(0, 0, 0, request()->mon2, 10));
        $yr = request()->yr2;
        $date = $mon2 ."-" . request()->yr2;
        $month = ++$mon;

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

        $total = Carbon::parse($date)->daysInMonth;
                     
                      $prevweek = 1;
                      $rows .= "<tr><td></td><td colspan='8' align='center'>  <b>WEEK 1 </b> </td><td></td></tr>";
                      $week_num = 2;
                      $total_days = 0;
                      for($i = 1;$i <= $total;$i++)
                      {
                          $weeknum = weekOfMonth(date($yr.'-'.request()->mon2.'-'.$i));
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
                                

                            //$tTotalDays = $total_days." - ".$tDaysLeave;
                              
                              $grandTotalHrsRendered += $tTimeWeek;

                            //   if((($total_days - $tDaysLeave) - $tTimeWeek) <= 0)
                            //   {
                            //     $tDeficit = readableTime(0);
                            //     $totalDeficit += 0;
                            //   }
                            //   else
                            //   {
                            //       $tDeficit = readableTime(($total_days - $tDaysLeave) - $tTimeWeek)." ";
                            //       $totalDeficit += ($total_days - $tDaysLeave) - $tTimeWeek;
                            //   }
                            
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

                              
                                
                              
                              $rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.")</b> </td><td align='center'><b>".$totalhrsweek."</b></td><td align='center'><b>".$tLatesWeeks."</b></td><td align='center'><b>".$tUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";

                              $prevweek = $weeknum;
                              $rows .= "<tr><td></td><td colspan='8' align='center'> <b>WEEK $week_num </b> </td><td></td></tr>";
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

                            

                            $dtr_date = date("Y-m-d",strtotime($yr.'-'.request()->mon2.'-'.$i));

                            //echo $dtr_date."<br/>";

                            $dayDesc = weekDesc($dtr_date);
                            $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id'],$emp['username']);
                            if(!$dtr)
                            {
                                $dtr = array();
                            }

                            
                                // //UDERTIME
                                // $under = null;
                                // if(countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],6))
                                // {
                                //     $undertime = explode("|", countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut']));
                                    
                                //     $under = $undertime[0];
                                //     $tUndertime += $undertime[1];
                                // }

                                // //LATES
                                // $lates = null;
                                // if(computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],6))
                                // {
                                //     $late = explode("|",computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn']));
                                //     $lates = $late[0];
                                //     $tLates += $late[1];
                                // }

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
                                    $rows .= showDate($dtr,$dtr_date,$i,$dayDesc,$emp['id'],$emp['employment_id'],$emp['username'],null,null);
                                    
                                    if(checkIfHasLeave($dtr_date,$emp['id']))
		                            {
                                        $leave = getLeaveDetails($dtr_date,$emp['id']);
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
                                    $rowsArr = explode("|",plotDate($dtr,$i,$dayDesc,$dtr_date,$emp['id']));
                                    $rows .= $rowsArr[0];
                                    $tLates +=(int)$rowsArr[1];
                                    //$tUndertime +=(int)$rowsArr[5];
                                    $tTimeWeek +=(int)$rowsArr[3];
                                    $tDaysLeave +=(int)$rowsArr[4];
                                    $tDaysExcess +=(int)$rowsArr[6];
                                    $tDaysDeficit +=(int)$rowsArr[7];
                                    $tLateCTR +=(int)$rowsArr[9];


                                    //$tLatesWeeks += $tLates;
                                    if((int)$rowsArr[1] > 0)
                                    {
                                        $tUndertimeWeeks2 .= $rowsArr[5]."|".(int)$rowsArr[1]."<br/>";
                                        $tLatesWeeks += (int)$rowsArr[1];
                                        //$tLateCTR++;
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

                    //   if((($total_days - $lastWeekLeaves) - $lastTimeWeek) <= 0)
                    //   {
                    //     $tDeficit = readableTime(0);
                    //   }
                    //   else
                    //   {
                    //     $tDeficit = readableTime(($total_days - $lastWeekLeaves) - $lastTimeWeek)." ";


                    //     if($totalDeficit < 0)
                    //     {
                    //         $totalDeficit = 0;
                    //     }
                    //     else
                    //     {
                    //         if((($total_days - $lastWeekLeaves) - $lastTimeWeek) > 0)
                    //         {
                    //             $totalDeficit = $totalDeficit + (($total_days - $lastWeekLeaves) - $lastTimeWeek);
                    //         }
                                
                    //     }
                    //   }

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


                      $rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.")</b> </td><td align='center'><b>".$totalhrsweek."</b></td><td align='center'><b>".$tLastLatesWeeks."</b></td><td align='center'><b>".$tLastUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";
        //DISPLAY LATES
        // $hourslate = floor($tLates / 60);
        // $minuteslate = $tLates % 60;

        // //DISPLAY UNDERTIME
        // $hoursunder = floor($tUndertime / 60);
        // $minutesunder = $tUndertime % 60;

    //    if(getDTROption() == 6)
    //    {
    //          $hoursunder = 0;
    //          $minutesunder = 0;
    //    }

    //     if($emp['employment_id'] != 8)
    //     {
    //         $hourslate = 0;
    //         $minuteslate = 0;
    //     }

        //COUNT NO. LEAVES
        $leaves_total = 0;
        $l1_total = App\Request_leave::where('user_id',$emp['id'])->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon2)->whereYear('leave_date_from',request()->yr2)->get();

        $dtss = "";

        foreach ($l1_total as $key => $value) {
            $dayDesc2 = weekDesc($value->leave_date_from);
            if(!checkIfHoliday($value->leave_date_from))
			{
                if($dayDesc2 != 'Sat' && $dayDesc2 != 'Sun')
                {
                    
                    $leaves_total++;
                    $dtss .= $value->leave_date_from." -- ";
                }
            }
            
        }

        //SINGLE DATE
        $l2_total = App\Request_leave::where('user_id',$emp['id'])->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon2)->whereYear('leave_date_from',request()->yr2)->sum('leave_deduction');

        $l_total = $leaves_total + $l2_total;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - DTR</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                    @page {
                                      margin: 20;
                                    }
                                body
                                {
                                    font-family:Helvetica;
                                }
                                th,td
                                {
                                    border:1px solid #555;
                                    font-size:13px;
                                }
                            </style>
                            <body>
                            <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                <tr>
                                  <td style="border : 1px solid #FFF;width:20%" align="right">
                                  <img src="'.asset('img/DOST.png').'" style="width:100px">
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;width:20%" >

                                  </td>
                                </tr>
                            </table>
                                <center><h3><b>Daily Time Record (DTR)<br/>'.$mon2.'  '.$yr.'</b></h3></center>
                                <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                    <tr>
                                        <td style="width:90px"><b>Day</b></td><td style="width60px"><center><b>AM In</b></center></td><td style="width:65px"><center><b>AM Out</b></center></td><td style="width:60px"><center><b>PM In</b></center></td><td style="width:65px"><center><b>PM Out</b></center></td><td style="width:90px"><center><b>Total Hours</b></center></td><td><center><b>Lates</b></center></td><td><center><b>Undertime</b></center></td><td><center><b>Deficit</b></center></td><td style="width:150px"><center><b>Remarks</b></center></td>
                                    </tr>
                                    <tbody>
                                        '.$rows.'
                                        <tr><td></td><td colspan="4" align="right" style="padding-right:5px"> <b>TOTAL </b></td><td align="center"><b></b></td><td align="center"><b>'.readableTime($tLates).'</b></td><td align="center"><b>'.readableTime($tUndertime).'</b></td><td align="center"><b>'.readableTime($totalDeficit).'</b></td><td></td></tr>
                                    </tbody>
                                </table>
                                <br>
                                <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:12px">
                                        <b>Total no. of lates : </b> '.$tLateCTR.'<br>
                                        <b>Total no. of undertime : </b>'.$tUndertimeCTR.'<br>
                                        <b>Total hours deficit : </b>'.readableTime($totalDeficit).'<br>
                                        <b>Total no. of leave days : </b>'.$l_total.'d<br>
                                        <b>Total no. of unauthorized absences : </b><br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px" valign="top">
                                        <b>Total late hours : </b> '.readableTime($tLates).'<br>
                                        <b>Total undertime hours : </b>'.readableTime($tUndertime).'<br>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:15px" align="center">'.mb_strtoupper(strtolower($emp['fname'].' '.substr($emp['mname'],0,1).'. '.$emp['lname'])).'<br><small><b>Name of Employee</b></small></td>
                                  <td style="border : 1px solid #FFF;font-size:15px" align="center">'.getDirector($emp['division'],$emp['id']).'</td>
                                <tr>
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'portrait');
        return $pdf->stream();
    }

    private function getDirector($div)
    {
        $division = App\Division::where('division_id',$div)->first();
        return $division['director'];
    }

    public function icosupdate()
    {
        $dtr = App\Employee_icos_dtr::where('id',request()->dtr_id)
                                     ->update([
                                                request()->dtr_col => request()->dtr_val 
       
                                             ]);

        $emp = App\User::where('id',request()->userid)->first();

        $data = [
                    "emp" => $emp,
                    "fullname" => $emp['lname'].', '.$emp['fname'].' '.$emp['mname'],
                    "yr" => request()->yr,
                    "mon" => request()->mon,
                ];

        //ADD HISTORY
        $this->addHistory();


        return redirect('dtr/edit/'.request()->userid.'/'.request()->mon.'/'.request()->yr);
    }

    public function icosadd()
    {
        $dtr = App\Employee_icos_dtr::where('id',request()->dtr_id)
                                     ->update([
                                                request()->dtr_col => request()->dtr_val 
                                             ]);

        $emp = App\User::where('id',request()->userid)->first();

        $data = [
                    "emp" => $emp,
                    "fullname" => $emp['lname'].', '.$emp['fname'].' '.$emp['mname'],
                    "yr" => request()->yr,
                    "mon" => request()->mon,
                ];

        //ADD HISTORY
        $this->addHistory();


        return redirect('dtr/edit/'.request()->userid.'/'.request()->mon.'/'.request()->yr);
    }

    public function icoswfh()
    {

        $emp = App\User::where('id',request()->user_id_wfh)->first();


        //CHECK IF MAY TIME NA
        $dtr_check = App\Employee_icos_dtr::whereDate('fldEmpDTRdate',request()->wfh_date)->where('user_id',$emp['id'])->first();

        if($dtr_check)
        {
             switch (request()->dtrStatusTime) {
                case 'AM':
                    $dtr_edit = App\Employee_icos_dtr::where('id',$dtr_check['id'])
                                     ->update([
                                                "fldEmpDTRamIn" => "8:00:00",
                                                "fldEmpDTRamOut" => "12:00:00",
                                                "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                             ]);
                break;

                case 'PM':
                    $dtr_edit = App\Employee_icos_dtr::where('id',$dtr_check['id'])
                                     ->update([
                                                "fldEmpDTRpmIn" => "13:00:00",
                                                "fldEmpDTRpmOut" => "17:00:00",
                                                "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                             ]);
                break;

                default:
                    $dtr_edit = App\Employee_icos_dtr::where('id',$dtr_check['id'])
                                     ->update([
                                                "fldEmpDTRamIn" => "8:00:00",
                                                "fldEmpDTRamOut" => "12:00:00",
                                                "fldEmpDTRpmIn" => "13:00:00",
                                                "fldEmpDTRpmOut" => "17:00:00",
                                                "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                             ]);
                break;
            }
        }
        else
        {

            switch (request()->dtrStatusTime) {
                case 'AM':
                        //ADD
                        $dtr = new App\Employee_icos_dtr;
                        $dtr->user_id = $emp['id'];
                        $dtr->fldEmpCode = $emp['username'];
                        $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                        $dtr->division = $emp['division'];
                        $dtr->fldEmpDTRdate = request()->wfh_date;
                        $dtr->fldEmpDTRamIn = "8:00:00";
                        $dtr->fldEmpDTRamOut = "12:00:00";
                        $dtr->dtr_remarks = request()->dtrStatus . " - ". request()->wfh_reason;
                        $dtr->save();
                    break;

                case 'PM':
                        //ADD
                        $dtr = new App\Employee_icos_dtr;
                        $dtr->user_id = $emp['id'];
                        $dtr->fldEmpCode = $emp['username'];
                        $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                        $dtr->division = $emp['division'];
                        $dtr->fldEmpDTRdate = request()->wfh_date;
                        $dtr->fldEmpDTRpmIn = "13:00:00";
                        $dtr->fldEmpDTRpmOut = "17:00:00";
                        $dtr->dtr_remarks = request()->dtrStatus . " - ". request()->wfh_reason;;
                        $dtr->save();
                    break;
                
                default:
                        //ADD
                        $dtr = new App\Employee_icos_dtr;
                        $dtr->user_id = $emp['id'];
                        $dtr->fldEmpCode = $emp['username'];
                        $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                        $dtr->division = $emp['division'];
                        $dtr->fldEmpDTRdate = request()->wfh_date;
                        $dtr->fldEmpDTRamIn = "8:00:00";
                        $dtr->fldEmpDTRamOut = "12:00:00";
                        $dtr->fldEmpDTRpmIn = "13:00:00";
                        $dtr->fldEmpDTRpmOut = "17:00:00";
                        $dtr->dtr_remarks = request()->dtrStatus;
                        $dtr->save();
                    break;
            }
            
        }

        

        $data = [
                    "emp" => $emp,
                    "fullname" => $emp['lname'].', '.$emp['fname'].' '.$emp['mname'],
                    "yr" => request()->yr,
                    "mon" => request()->mon,
                ];

        //ADD HISTORY

        return redirect('dtr/edit/'.request()->user_id_wfh.'/'.request()->wfh_mon.'/'.request()->wfh_yr);
    }

    public function editdtr()
    {

        $emp = App\User::where('id',request()->user_id_wfh)->first();


        //CHECK IF MAY TIME NA
        $dtr_check = App\Employee_dtrs::whereDate('fldEmpDTRdate',request()->wfh_date)->where('user_id',$emp['id'])->first();

        if($dtr_check)
        {
             switch (request()->dtrStatusTime) {
                case 'AM':
                    $dtr_edit = App\Employee_dtrs::where('id',$dtr_check['id'])
                                     ->update([
                                                "fldEmpDTRamIn" => "8:00:00",
                                                "fldEmpDTRamOut" => "12:00:00",
                                                "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                             ]);
                break;

                case 'PM':
                    $dtr_edit = App\Employee_dtrs::where('id',$dtr_check['id'])
                                     ->update([
                                                "fldEmpDTRpmIn" => "13:00:00",
                                                "fldEmpDTRpmOut" => "17:00:00",
                                                "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                             ]);
                break;

                default:
                    $dtr_edit = App\Employee_dtrs::where('id',$dtr_check['id'])
                                     ->update([
                                                "fldEmpDTRamIn" => "8:00:00",
                                                "fldEmpDTRamOut" => "12:00:00",
                                                "fldEmpDTRpmIn" => "13:00:00",
                                                "fldEmpDTRpmOut" => "17:00:00",
                                                "dtr_remarks" => request()->dtrStatus . " - ". request()->wfh_reason
                                             ]);
                break;
            }
        }
        else
        {
            $dtroption = showActiveWS();
            switch (request()->dtrStatusTime) {
                case 'AM':
                        //ADD
                        $dtr = new App\Employee_dtrs;
                        $dtr->user_id = $emp['id'];
                        $dtr->fldEmpCode = $emp['username'];
                        $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                        $dtr->division = $emp['division'];
                        $dtr->fldEmpDTRdate = request()->wfh_date;
                        $dtr->fldEmpDTRamIn = "8:00:00";
                        $dtr->fldEmpDTRamOut = "12:00:00";
                        $dtr->dtr_option_id = $dtroption;
                        $dtr->dtr_remarks = request()->dtrStatus . " - ". request()->wfh_reason;
                        $dtr->save();
                    break;

                case 'PM':
                        //ADD
                        $dtr = new App\Employee_dtrs;
                        $dtr->user_id = $emp['id'];
                        $dtr->fldEmpCode = $emp['username'];
                        $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                        $dtr->division = $emp['division'];
                        $dtr->fldEmpDTRdate = request()->wfh_date;
                        $dtr->fldEmpDTRpmIn = "13:00:00";
                        $dtr->fldEmpDTRpmOut = "17:00:00";
                        $dtr->dtr_option_id = $dtroption;
                        $dtr->dtr_remarks = request()->dtrStatus . " - ". request()->wfh_reason;;
                        $dtr->save();
                    break;
                
                default:
                        //ADD
                        $dtr = new App\Employee_dtrs;
                        $dtr->user_id = $emp['id'];
                        $dtr->fldEmpCode = $emp['username'];
                        $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                        $dtr->division = $emp['division'];
                        $dtr->fldEmpDTRdate = request()->wfh_date;
                        $dtr->fldEmpDTRamIn = "8:00:00";
                        $dtr->fldEmpDTRamOut = "12:00:00";
                        $dtr->fldEmpDTRpmIn = "13:00:00";
                        $dtr->fldEmpDTRpmOut = "17:00:00";
                        $dtr->dtr_option_id = $dtroption;
                        $dtr->dtr_remarks = request()->dtrStatus;
                        $dtr->save();
                    break;
            }
            
        }

        

        $data = [
                    "emp" => $emp,
                    "fullname" => $emp['lname'].', '.$emp['fname'].' '.$emp['mname'],
                    "yr" => request()->yr,
                    "mon" => request()->mon,
                ];

        //ADD HISTORY

        return redirect('dtr/emp/'.request()->wfh_mon.'/'.request()->wfh_yr);
    }

    public function update()
    {
        $userid = request()->userid;
        $yr = request()->yr;
        $mon = request()->mon;
        $day = request()->day;
        $dtr_colid = request()->dtr_colid;
        $dtr_col = request()->dtr_col;
        $dtr_orig = request()->dtr_orig;
        $dtr_new = request()->dtr_val;
        $dtr_col_desc = "";

        $emp = App\User::where('id',$userid)->first();

        if($dtr_colid == 0)
        {
            //ZERO MEANS NEW ENTRY, INSERT
            if($emp['employment_id'] == 8)
            {
                $dtr = new App\Employee_icos_dtr;
                $dtr->user_id = $userid;
                $dtr->fldEmpCode = $emp['username'];
                $dtr->employee_name = $emp['lname'].", ".$emp['fname']." ".$emp['mname'];
                $dtr->division = $emp['division'];
                $dtr->fldEmpDTRdate = $yr."-".$mon."-".$day;

                switch ($dtr_col) {
                    case 1:
                            $dtr->fldEmpDTRamIn = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRamIn';
                        break;
                    case 2:
                            $dtr->fldEmpDTRamOut = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRamOut';
                        break;
                    case 3:
                            $dtr->fldEmpDTRpmIn = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRpmIn';
                        break;
                    case 4:
                            $dtr->fldEmpDTRpmOut = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRpmOut';
                        break;
                    case 5:
                            $dtr->fldEmpDTRotIn = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRotIn';
                        break;
                    case 6:
                            $dtr->fldEmpDTRotOut = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRotOut';
                        break;
                    case 7:
                            $dtr->dtr_remarks = request()->dtr_remarks;
                        break;
                    
                }
                $dtr->save();
                $dtrid = $dtr->id;
            }
            else
            {
                $dtr = new App\Employee_dtr;
                $dtr->user_id = $userid;
                $dtr->fldEmpCode = $emp['username'];
                $dtr->employee_name = $emp['lname'].", ".$emp['fname']." ".$emp['mname'];
                $dtr->division = $emp['division'];
                $dtr->fldEmpDTRdate = $yr."-".$mon."-".$day;

                switch ($dtr_col) {
                    case 1:
                            $dtr->fldEmpDTRamIn = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRamIn';
                        break;
                    case 2:
                            $dtr->fldEmpDTRamOut = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRamOut';
                        break;
                    case 3:
                            $dtr->fldEmpDTRpmIn = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRpmIn';
                        break;
                    case 4:
                            $dtr->fldEmpDTRpmOut = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRpmOut';
                        break;
                    case 5:
                            $dtr->fldEmpDTRotIn = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRotIn';
                        break;
                    case 6:
                            $dtr->fldEmpDTRotOut = $dtr_new;
                            $dtr_col_desc = 'fldEmpDTRotOut';
                        break;
                    
                }
                $dtr->dtr_option_id = getDTROption();
                $dtr->save();
                $dtrid = $dtr->id;
            }
            

            //ADD TO HISTORY
            $history = new App\DTRHistory;
            $history->user_id = $userid;
            $history->dtr_id = $dtrid;

            if($emp['employment_id'] == 8)
            {
                $history->dtr_tbl = 'employee_icos_dtrs';
            }
            else
            {
                $history->dtr_tbl = 'employee_dtrs';
            }

            $history->dtr_col = $dtr_col_desc;
            $history->time_orig = $dtr_orig;
            $history->time_new = $dtr_new;
            $history->acted_by = Auth::user()->lname.", ".Auth::user()->fname." ".Auth::user()->mname;
            $history->remarks = "NEW ENTRY";
            $history->save();
            
        }
        else
        {
            //UPDATE
            if($emp['employment_id'] == 8)
            {
                switch ($dtr_col) {
                    case 1:
                            $dtr_col_desc = 'fldEmpDTRamIn';
                        break;
                    case 2:
                            $dtr_col_desc = 'fldEmpDTRamOut';
                        break;
                    case 3:
                            $dtr_col_desc = 'fldEmpDTRpmIn';
                        break;
                    case 4:
                            $dtr_col_desc = 'fldEmpDTRpmOut';
                        break;
                    case 5:
                            $dtr_col_desc = 'fldEmpDTRotIn';
                        break;
                    case 6:
                            $dtr_col_desc = 'fldEmpDTRotOut';
                        break;
                    case 7:
                            $dtr_col_desc = 'dtr_remarks';
                            $dtr_new = request()->dtr_remarks;
                        break;
                    
                }

                $dtr = App\Employee_icos_dtr::where('id',$dtr_colid)->update([
                    $dtr_col_desc => $dtr_new 
                ]);
            }
            else
            {
                switch ($dtr_col) {
                    case 1:
                            $dtr_col_desc = 'fldEmpDTRamIn';
                        break;
                    case 2:
                            $dtr_col_desc = 'fldEmpDTRamOut';
                        break;
                    case 3:
                            $dtr_col_desc = 'fldEmpDTRpmIn';
                        break;
                    case 4:
                            $dtr_col_desc = 'fldEmpDTRpmOut';
                        break;
                    
                }

                $dtr = App\Employee_dtr::where('id',$dtr_colid)->update([
                    $dtr_col_desc => $dtr_new 
                ]);
            }
            

            //ADD TO HISTORY
            $history = new App\DTRHistory;
            $history->user_id = $userid;
            $history->dtr_id = $dtr_colid;

            if($emp['employment_id'] == 8)
            {
                $history->dtr_tbl = 'employee_icos_dtrs';
            }
            else
            {
                $history->dtr_tbl = 'employee_dtrs';
            }

            $history->dtr_col = $dtr_col_desc;
            $history->time_orig = $dtr_orig;
            $history->time_new = $dtr_new;
            $history->acted_by = Auth::user()->lname.", ".Auth::user()->fname." ".Auth::user()->mname;
            $history->remarks = "EDIT ENTRY";
            $history->save();
        }

        
        $data = [
            "emp" => $emp,
            "fullname" => $emp['lname'].', '.$emp['fname'].' '.$emp['mname'],
            "yr" => $yr,
            "mon" => $mon,
        ];


        if(isset(request()->dtr_url))
        {
            return redirect(request()->dtr_url);
        }
        else
        {
           return view('dtr.dtr-edit')->with('data',$data); 
        }               
    }

    public function icoswfhto()
    {
        $emp = App\User::where('id',request()->add_wfhto_userid)->first();


        switch (request()->icos_dtr_type) {
            case 'WFH':
                    $dtr = new App\Employee_icos_dtr;
                    $dtr->user_id = $emp['id'];
                    $dtr->fldEmpCode = $emp['username'];
                    $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                    $dtr->division = $emp['division'];
                    $dtr->fldEmpDTRdate = request()->icos_dtr_date;
                    $dtr->dtr_remarks = request()->icos_remarks;

                    switch (request()->icos_dtr_time) {
                        case 'PM':
                                $dtr->fldEmpDTRamIn = "8:00:00";
                                $dtr->fldEmpDTRamOut = "12:00:00";
                                $dtr->wfh = "AM";
                                $dtr->save();
                            break;
                        case 'AM':
                                $dtr->fldEmpDTRpmIn = "13:00:00";
                                $dtr->fldEmpDTRpmOut = "17:00:00";
                                $dtr->wfh = "PM";
                                $dtr->save();
                            break;
                        
                        default:
                                $dtr->fldEmpDTRamIn = "8:00:00";
                                $dtr->fldEmpDTRamOut = "12:00:00";
                                $dtr->fldEmpDTRpmIn = "13:00:00";
                                $dtr->fldEmpDTRpmOut = "17:00:00";
                                $dtr->wfh = "Wholeday";
                                $dtr->save();
                            break;

                        
                    }
                break;
            case 'TIME':
                $dtr = new App\Employee_icos_dtr;
                $dtr->user_id = $emp['id'];
                $dtr->fldEmpCode = $emp['username'];
                $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                $dtr->division = $emp['division'];
                $dtr->fldEmpDTRdate = request()->icos_dtr_date;
                $dtr->dtr_remarks = request()->icos_remarks;

                switch (request()->icos_dtr_time) {
                    case 'AM':
                            $dtr->fldEmpDTRamIn = "8:00:00";
                            $dtr->fldEmpDTRamOut = "12:00:00";
                            $dtr->save();
                        break;
                    case 'PM':
                            $dtr->fldEmpDTRpmIn = "13:00:00";
                            $dtr->fldEmpDTRpmOut = "17:00:00";
                            $dtr->save();
                        break;
                    
                    default:
                            $dtr->fldEmpDTRamIn = "8:00:00";
                            $dtr->fldEmpDTRamOut = "12:00:00";
                            $dtr->fldEmpDTRpmIn = "13:00:00";
                            $dtr->fldEmpDTRpmOut = "17:00:00";
                            $dtr->save();
                        break;

                }
                break;
            case 'LEAVE':

                $dtr = new App\Employee_icos_dtr;
                $dtr->user_id = $emp['id'];
                $dtr->fldEmpCode = $emp['username'];
                $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                $dtr->division = $emp['division'];
                $dtr->fldEmpDTRdate = request()->icos_dtr_date;
                $dtr->leave_id = 1;

                switch (request()->icos_dtr_time) {
                    case 'AM':
                            $dtr->fldEmpDTRpmIn = "13:00:00";
                            $dtr->fldEmpDTRpmOut = "15:00:00";
                            $dtr->dtr_remarks = "On Leave (AM)";
                            $dtr->save();
                        break;
                    case 'PM':
                            $dtr->fldEmpDTRamIn = "8:00:00";
                            $dtr->fldEmpDTRamOut = "12:00:00";
                            $dtr->dtr_remarks = "On Leave (PM)";
                            $dtr->save();
                        break;
                    
                    default:
                            $dtr->dtr_remarks = "On Leave";
                            $dtr->save();
                        break;

                }
                break;
            default:
                $dtr = new App\Employee_icos_dtr;
                $dtr->user_id = $emp['id'];
                $dtr->fldEmpCode = $emp['username'];
                $dtr->employee_name = $emp['lname'].', '.$emp['fname'].' '.$emp['mname'];
                $dtr->division = $emp['division'];
                $dtr->fldEmpDTRdate = request()->icos_dtr_date;
                $dtr->dtr_remarks = request()->icos_remarks;

                switch (request()->icos_dtr_time) {
                    case 'AM':
                            $dtr->fldEmpDTRamIn = "8:00:00";
                            $dtr->fldEmpDTRamOut = "12:00:00";
                            $dtr->dtr_to = "AM";
                            $dtr->save();
                        break;
                    case 'PM':
                            $dtr->fldEmpDTRpmIn = "13:00:00";
                            $dtr->fldEmpDTRpmOut = "17:00:00";
                            $dtr->dtr_to = "PM";
                            $dtr->save();
                        break;
                    
                    default:
                            $dtr->fldEmpDTRamIn = "8:00:00";
                            $dtr->fldEmpDTRamOut = "12:00:00";
                            $dtr->fldEmpDTRpmIn = "13:00:00";
                            $dtr->fldEmpDTRpmOut = "17:00:00";
                            $dtr->dtr_to = "Wholeday";
                            $dtr->save();
                        break;

                }
                break;
        }
        $mon = date('m',strtotime(request()->icos_dtr_date));
        $yr = date('Y',strtotime(request()->icos_dtr_date));
        return redirect('dtr/icos/'.$mon.'/'.$yr.'/'.$emp['id']);
    }

    private function addHistory()
    {
        $history = new App\DTRHistory;
        $history->user_id = request()->userid;
        $history->dtr_id = request()->dtr_id;
        $history->dtr_tbl = request()->dtr_tbl;
        $history->dtr_col = request()->dtr_col;
        $history->time_orig = request()->dtr_orig;
        $history->time_new = request()->dtr_val;
        $history->acted_by = Auth::user()->lname . ', '. Auth::user()->fname;
        $history->save();
    }

    public function icosindex($mon,$yr,$id)
    {
        
        // // $list = DB::select("SELECT * FROM employee_icos_dtrs WHERE processed IS NULL GROUP BY fldEmpCode,MONTH(fldEmpDTRdate)");
        // $data = [
        //             "list" => $list,
        //             "nav" => nav("icos"),
        //         ];
        // return view('dtr.icos-months')->with("data",$data);

        $list =App\User::where('id',$id)->where('division',Auth::user()->division)->where('employment_id',8)->first();

        $data = [
                    "emp" => $list,
                    "yr" => $yr,
                    "mon" => $mon,
                    "nav" => nav("icos"),
                ];

        return view('staff.attendance-icos')->with("data",$data);
    }


    public function checkDayForPrinting($day)
    {
        if(date('d') >= 1 && date('d') <= 15)
        {
            if($day >= 1 && $day <= 15)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            if($day >= 16 && $day <= 31)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    //EMPLOYEE DTR
    public function empdtr()
    {
        $list = DB::select("SELECT * FROM employee_dtrs WHERE YEAR(fldEmpDTRdate) = ".date('Y')." AND division = '".Auth::user()->division."' GROUP BY division,MONTH(fldEmpDTRdate),YEAR(fldEmpDTRdate)");
        // $list = DB::select("SELECT * FROM employee_icos_dtrs WHERE processed IS NULL GROUP BY fldEmpCode,MONTH(fldEmpDTRdate)");
        $data = [
                    "list" => $list,
                    "nav" => nav("empdtr"),
                ];
        return view('dtr.emp')->with("data",$data);
    }

    public function empdtrmonth()
    {
        $data = [
                    "nav" => nav("empdtr"),
                ];
        return view('dtr.emp-months')->with("data",$data);
    }

    public function emp($mon,$year)
    {
        if(Auth::user()->usertype == 'Administrator')
        {
            //$list = DB::select("SELECT * FROM employee_dtrs WHERE MONTH(fldEmpDTRdate) = $mon AND YEAR(fldEmpDTRdate) = $year GROUP BY fldEmpCode,MONTH(fldEmpDTRdate),YEAR(fldEmpDTRdate)");
            $list = collect(App\User::whereIn('employment_id',[1,15])->orderBy('lname')->orderBy('fname')->get());
            $list = $list->all();
        }
        else
        {
            // $list = DB::select("SELECT * FROM employee_dtrs WHERE division = '".Auth::user()->division."' AND MONTH(fldEmpDTRdate) = $mon AND YEAR(fldEmpDTRdate) = $year GROUP BY division,fldEmpCode,MONTH(fldEmpDTRdate),YEAR(fldEmpDTRdate)");
            $list = collect(App\User::where('division',Auth::user()->division)->whereIn('employment_id',[1,15])->orderBy('lname')->orderBy('fname')->get());
            $list = $list->all();
        }
        
        $data = [
                    "list" => $list,
                    "nav" => nav("empdtr"),
                    "mon" => $mon,
                    "year" => $year,
                ];

        return view('dtr.emp')->with("data",$data);
    }

    public function processDTR()
    {
        $data = [
                    "nav" => nav("empdtr"),
                    "mon" => request()->mon,
                    "year" => request()->year,
                ];
        return view('dtr.dtr-process-message')->with("data",$data);
    }

    public function finalprocessDTR2()
    {
        foreach (request()->check_request as $key => $value) {
            # code...
            echo $value."<br>";
        }

    }

    public function finalprocessDTR()
    {
        
        //GET ALL STAFF
        $staff = collect(App\View_user::select('id','lname','fname','username')->whereIn('id',request()->check_request)->where('usertype','!=','Administrator')->orderBy('lname')->orderBy('fname')->get());

        foreach ($staff->all() as $staffs) {
            $deficit = 0.000;

            $totaldecuction = 0.000;

            $vl = 0.000;
            $sl = 0.000;
            $pl = 0.000;
            $spl = 0.000;
            $cto = 0.000;
            $fl = 0.000;
            $MatL = 0.000;
            $PatL = 0.000;
            $StL = 0.000;
            $ReL = 0.000;
            $el = 0.000;
            $spec = 0.000;
            $ml = 0.000;
            $ua = 0.000;
            $slwop = 0.000;

            $remarks = "";

            $remarks .= "<b>".$staffs->lname.", ".$staffs->fname."</b><br/>---Current Leave Balances---<br/>";


            $collecleave = collect([]);

            //GET STAFF CURRENT LEAVE BALANCES
            foreach(showLeaves() AS $leaves){
                $lv = getLeaves($staffs->id,$leaves->id);

                $remarks .= $leaves->leave_desc." : <b>$lv</b><br>";

                $collecleave->put($leaves->leave_desc, $lv);
            }
            
            $remarks .= "<br/><br/>";


            //GET L.W.P
            $lwp = getLWP($staffs->id,request()->mon,request()->yr);

            $lwp = explode("|", $lwp);

            // return $lwp[0];

            $remarks .= "---Leave Without Pay---<br/>";

            if($lwp[0] >= 0)
            {
                //EARNED VL/SL
                $lwps = getLWPCount($lwp[0]);

                $remarks .= "LWP total : <b>".$lwp[0]."</b><br>";
            }

            $remarks .= "<br/>";

            $remarks .= "VL/SL earn : <b>".$lwps."</b><br><br>";


            $remarks .= "---Deduction---<br/>";
            //GET LATES/UNDERTIME
            $remarks .= $lwp[2]."<br/>Lates/Undertime Deduction : <b>".number_format((float)$lwp[1], 3, '.', '')."</b><br><br>";

            $totaldecuction += number_format((float)$lwp[1], 3, '.', '');


            //GET REQUEST
            $remarks .= "---Request---<br/>";
            $leave_req = collect(App\Request_leave::where('user_id',$staffs->id)->where('leave_action_status','Approved')->whereMonth('leave_date',request()->mon)->whereYear('leave_date',request()->yr)->whereNull('leave_processed')->get());
            if($leave_req)
            {
                $leave_req = $leave_req->all();

                foreach ($leave_req as $leaves) {
                    $remarks .= getLeaveInfo($leaves->leave_id)." - Date : ".$leaves->leave_date." - Deduction : ".$leaves->leave_deduction."<br>";
                    switch ($leaves->leave_id) {
                        case 1:
                        # code...
                                $vl += $leaves->leave_deduction;
                                $totaldecuction = $totaldecuction + $leaves->leave_deduction;
                            break;
                        case 2:
                            # code...
                                $sl += $leaves->leave_deduction;
                            break;
                        case 3:
                            # code...
                                $pl += $leaves->leave_deduction;
                            break;
                        case 4:
                            # code...
                                $spl += $leaves->leave_deduction;
                            break;
                        case 5:
                            # code...
                                $cto += $leaves->leave_deduction;
                            break;
                        case 6:
                            # code...
                                $fl += $leaves->leave_deduction;
                            break;
                        case 7:
                            # code...
                                $MatL += $leaves->leave_deduction;
                            break;
                        case 8:
                            # code...
                                $PatL += $leaves->leave_deduction;
                            break;
                        case 9:
                            # code...
                                $StL += $leaves->leave_deduction;
                            break;
                        case 10:
                            # code...
                                $ReL += $leaves->leave_deduction;
                            break;
                        case 11:
                            # code...
                                $el += $leaves->leave_deduction;
                            break;
                        case 12:
                            # code...
                                $spec += $leaves->leave_deduction;
                            break;
                        case 13:
                            # code...
                                $el += $leaves->leave_deduction;
                            break;
                        case 14:
                            # code...
                                $ua += $leaves->leave_deduction;
                            break;
                        case 15:
                            # code...
                                $slwop += $leaves->leave_deduction;
                            break;
                    }
                }
            }
            

            $remarks .= "<br/>---Updated Leave Balances---<br/>";

            foreach ($collecleave as $keybal => $valuebal) {
                
                switch ($keybal) {
                    case 'Vacation Leave':
                        # code...
                            $val = (($valuebal + $lwps) - ($vl + $totaldecuction));

                            if(($vl + $totaldecuction) > ($valuebal + $lwps))
                            {
                                $deficit = ($vl + $totaldecuction) - ($valuebal + $lwps);
                            }
                        break;
                    case 'Sick Leave':
                        # code...
                            $val = $valuebal - $sl;
                            $val = (($valuebal + $lwps) - $sl);
                        break;
                    case 'Privilege Leave':
                        # code...
                            $val = $valuebal - $pl;
                        break;
                    case 'Solo Parent Leave':
                        # code...
                            $val = $valuebal - $spl;
                        break;
                    case 'Compensatory Time-Off':
                        # code...
                            $val = $valuebal - $cto;
                        break;
                    case 'Force Leave':
                        # code...
                            $val = $valuebal - $fl;
                        break;
                    case 'Maternity Leave':
                        # code...
                            $val = $valuebal - $MatL;
                        break;
                    case 'Paternity Leave':
                        # code...
                            $val = $valuebal - $PatL;
                        break;
                    case 'Study Leave':
                        # code...
                            $val = $valuebal - $StL;
                        break;
                    case 'Rehabilitation Leave':
                        # code...
                            $val = $valuebal - $ReL;
                        break;
                    case 'Emergency Leave':
                        # code...
                            $val = $valuebal - $el;
                        break;
                    case 'Monetize Leave':
                        # code...
                            $val = $valuebal - $ml;
                        break;
                    case 'Special Leave (Magna Carta of Women)':
                        # code...
                            $val = $valuebal - $spec;
                        break;
                    case 'Unauthorized Absence':
                        # code...
                            $val = $valuebal - $ua;
                        break;
                    case 'Sick Leave Without Pay':
                        # code...
                            $val = $valuebal - $slwop;
                        break;
                }

                $remarks .= $keybal." : <b>".$val."</b><br/>";
            }
            $remarks .= "<br/>---Deficit---<br/>$deficit";

            //NO MORE VL
            // if($vl <= 0)
            // {

            // }
            // else
            // {

            // }

            echo $remarks."<hr/>";
        }

        // foreach (request()->check_request as $key => $value) {
        //     # code...
        //     echo $value."<br/>";
        // }
    }


    public function weeksched()
    {
        // return request()["dt_300"];
            if(Auth::user()->division == 'q' && Auth::user()->usertype == 'Marshal')
            {
                //CHECK IF HAS ENTRY
                $weekschedcheck = App\WeekSchedule::where('userid',141)->where('sched_date',request()->weekdate)->count();
                if($weekschedcheck == 0)
                {
                    // $dtr = checkIfWFH('check',request()->weekdate,141,1);
                    $dtr = checkIfDTR('check',request()->weekdate,141,1);
                    $dtr_new = new App\Employee_dtr;
                    
                    if($dtr == 0)
                    {
                        $weeksched = new App\WeekSchedule;
                        $weeksched->userid = 141;
                        $weeksched->sched_date = request()->weekdate;
                        $weeksched->sched_status = request()["dt_141"];
                        $weeksched->created_by = Auth::user()->fname." ".Auth::user()->lname;
                        $weeksched->save();

                        //INSERT SA DTR
                        if(request()["dt_141"] == 'WFH')
                        {
                            $dtr_new->user_id = 141;
                            $dtr_new->fldEmpCode = 'MOL001';
                            $dtr_new->division = 'x';
                            $dtr_new->fldEmpDTRdate = request()->weekdate;
                            $dtr_new->employee_name = "Molina, Susan S.";
                            $dtr_new->fldEmpDTRamIn = '8:00:00';
                            $dtr_new->fldEmpDTRamOut = '12:30:00';
                            $dtr_new->fldEmpDTRpmIn = '13:00:00';
                            $dtr_new->fldEmpDTRpmOut = '17:00:00';
                            $dtr_new->wfh = 'Wholeday';
                            $dtr_new->request_id = 16;
                            $dtr_new->save();
                        }
                    }
                    
                }
            }

        foreach(getAllStaffDivision4() AS $lists)
        {
            if(request()["dt_".$lists->id] != "")
            {
                //CHECK IF HAS ENTRY
                $weekschedcheck = App\WeekSchedule::where('userid',$lists->id)->where('sched_date',request()->weekdate)->count();

                if($weekschedcheck == 0)
                {
                    //CHECK IF ICOS/REGULAR
                    $dtr = checkIfDTR('check',request()->weekdate,$lists->id,$lists->employment_id);

                    if($lists->employment_id == 8 || $lists->employment_id == 5)
                    {
                        $dtr_new = new App\Employee_icos_dtr;
                    }
                    else
                    {
                        $dtr_new = new App\Employee_dtr;
                    }
                    
                    if($dtr == 0)
                    {
                        $weeksched = new App\WeekSchedule;
                        $weeksched->userid = $lists->id;
                        $weeksched->sched_date = request()->weekdate;
                        $weeksched->sched_status = request()["dt_".$lists->id];
                        $weeksched->created_by = Auth::user()->fname." ".Auth::user()->lname;
                        $weeksched->save();

                        //INSERT SA DTR
                        if(request()["dt_".$lists->id] == 'WFH')
                        {
                            $dtr_new->user_id = $lists->id;
                            $dtr_new->fldEmpCode = $lists->username;
                            $dtr_new->division = $lists->division;
                            $dtr_new->fldEmpDTRdate = request()->weekdate;
                            $dtr_new->employee_name = $lists->lname.", ".$lists->fname." ".$lists->nname;
                            $dtr_new->fldEmpDTRamIn = '8:00:00';
                            $dtr_new->fldEmpDTRamOut = '12:30:00';
                            $dtr_new->fldEmpDTRpmIn = '13:00:00';
                            $dtr_new->fldEmpDTRpmOut = '17:00:00';
                            $dtr_new->wfh = 'Wholeday';
                            $dtr_new->request_id = 16;
                            $dtr_new->dtr_option_id = getDTROption();
                            $dtr_new->save();
                        }
                        

                    }
                    
                }
                else
                {
                    $weekschedcheck = App\WeekSchedule::where('userid',$lists->id)->where('sched_date',request()->weekdate)->delete();

                    if($lists->employment_id == 8 || $lists->employment_id == 5)
                    {
                        $dtr_new = new App\Employee_icos_dtr;
                    }
                    else
                    {
                        $dtr_new = new App\Employee_dtr;
                    }


                    $weeksched = new App\WeekSchedule;
                    $weeksched->userid = $lists->id;
                    $weeksched->sched_date = request()->weekdate;
                    $weeksched->sched_status = request()["dt_".$lists->id];
                    $weeksched->created_by = Auth::user()->fname." ".Auth::user()->lname;
                    $weeksched->save();

                    switch (request()["dt_".$lists->id] == 'WFH') {
                        case 'WFH':
                                $dtr_new->user_id = $lists->id;
                                $dtr_new->fldEmpCode = $lists->username;
                                $dtr_new->division = $lists->division;
                                $dtr_new->fldEmpDTRdate = request()->weekdate;
                                $dtr_new->employee_name = $lists->lname.", ".$lists->fname." ".$lists->nname;
                                $dtr_new->fldEmpDTRamIn = '8:00:00';
                                $dtr_new->fldEmpDTRamOut = '12:30:00';
                                $dtr_new->fldEmpDTRpmIn = '13:00:00';
                                $dtr_new->fldEmpDTRpmOut = '17:00:00';
                                $dtr_new->wfh = 'Wholeday';
                                $dtr_new->request_id = 16;
                                $dtr_new->dtr_option_id = getDTROption();
                                $dtr_new->save();
                            break;
                        
                        default:
                               $dtr_new->where('fldEmpCode',$lists->username)->where('fldEmpDTRdate',request()->weekdate)->delete();
                            break;
                    }

                }
            }  
        }

        // if(Auth::user()->employment_id == 1)
        //     {
        //         $emp = App\View_user::where('id',request()->weeksched_emp)->first();
        //     }
        //     else
        //     {
        //         $emp = App\View_users_temp::where('id',request()->weeksched_emp)->first();
        //     }
            
        // $data = [
        //     "mon" => request()->weeksched_mon,
        //     "yr" => request()->weeksched_yr,
        //     "weeknum" => request()->weeksched_weeknum,
        //     "empinfo" => $emp,
        //     "nav" => nav("dashboard")
        // ];
        
        // return view('pis.staff.index')->with("data",$data); 

        //return redirect('/');
        $dt = explode('-',request()->weekdate);

        return redirect('/home3/'.$dt[1].'/'.$dt[0].'/'.Auth::user()->id.'/'.request()->weeksched_weeknum);
    }

    public function weekschededit()
    {
        $schedule = App\WeekSchedule::where('id',request()->schedid)->first();
        
        //GET USER
        $user = App\User::where('id',$schedule['userid'])->first();

        $dtr = checkIfDTR('check',$schedule['sched_date'],$user['id'],$user['employment_id']);

        if($user['employment_id'] == 8 || $user['employment_id'] == 5)
            {
                $dtr_new = new App\Employee_icos_dtr;
            }
            else
            {
                $dtr_new = new App\Employee_dtr;
            }
        
        if(request()->sched_edit_status == 'WFH')
        {
            if($dtr == 0)
                    {
                        $dtr_new->user_id = $user['id'];
                        $dtr_new->fldEmpCode = $user['username'];
                        $dtr_new->division = $user['division'];
                        $dtr_new->fldEmpDTRdate = $schedule['sched_date'];
                        $dtr_new->employee_name = $user['lname'].", ".$user['fname']." ".$user['mname'];
                        $dtr_new->fldEmpDTRamIn = '8:00:00';
                        $dtr_new->fldEmpDTRamOut = '12:30:00';
                        $dtr_new->fldEmpDTRpmIn = '13:00:00';
                        $dtr_new->fldEmpDTRpmOut = '17:00:00';
                        $dtr_new->wfh = 'Wholeday';
                        $dtr_new->request_id = 16;
                        $dtr_new->save();
                    }
        }
        else
        {
            if($dtr > 0)
            {   
                $dtr_new->where('user_id',$user['id'])->where('fldEmpDTRdate',$schedule['sched_date'])->delete();
            }
        }

        //UPDATE DTR

        App\WeekSchedule::where('id',request()->schedid)
                ->update([
                            "sched_status" => request()->sched_edit_status
                        ]);
        

        if(isset(request()->schedmon))
        {
            return redirect('/home/'.request()->schedmon.'/'.request()->schedyear.'/'.request()->scheduserid.'/'.request()->schedweek);
        }
        else
        {
            return redirect('/');
        }
        // return redirect('/');
    }

    public function weekschedadd()
    {
        
        //GET USER
        $user = App\User::where('id',request()->sked_userid)->first();

        if($user['employment_id'] == 8 || $user['employment_id'] == 5)
            {
                $dtr_new = new App\Employee_icos_dtr;
            }
            else
            {
                $dtr_new = new App\Employee_dtr;
            }
        
        if(request()->sched_add_status == 'WFH')
        {
                        $dtr_new->user_id = $user['id'];
                        $dtr_new->fldEmpCode = $user['username'];
                        $dtr_new->division = $user['division'];
                        $dtr_new->fldEmpDTRdate = request()->sked_date;
                        $dtr_new->employee_name = $user['lname'].", ".$user['fname']." ".$user['mname'];
                        $dtr_new->fldEmpDTRamIn = '8:00:00';
                        $dtr_new->fldEmpDTRamOut = '12:30:00';
                        $dtr_new->fldEmpDTRpmIn = '13:00:00';
                        $dtr_new->fldEmpDTRpmOut = '17:00:00';
                        $dtr_new->wfh = 'Wholeday';
                        $dtr_new->request_id = 16;
                        $dtr_new->save();
        }
        else
        {

            $dtr_new->where('user_id',$user['id'])->where('fldEmpDTRdate',request()->sked_date)->delete();
            
        }

        $weeksched = new App\WeekSchedule;
        $weeksched->userid = request()->sked_userid;
        $weeksched->sched_date = request()->sked_date;
        $weeksched->sched_status = request()->sched_add_status;
        $weeksched->created_by = Auth::user()->fname." ".Auth::user()->lname;
        $weeksched->save();

        return redirect('/home3/'.request()->sked_mon.'/'.request()->sked_year.'/'.Auth::user()->id.'/'.request()->sked_weeknum);
    }

    public function schedule($id)
    {
        $sched = App\WeekSchedule::where('id',$id)->first();

        return json_encode($sched);
    }


    public function deleteDir()
    {

    }

    public function pdficos()
    {
        // return request()->mon2;

        

        $lastdays = "";
        $worksched = getDTROption();

        $emp = App\User::where('id',request()->userid2)->first();

        $rows = "";
        
        $mon = date('m',strtotime(request()->mon2));
        $mon2 = date('F',mktime(0, 0, 0, request()->mon2, 10));
        $yr = request()->yr2;
        $date = $mon2 ."-" . request()->yr2;
        $month = ++$mon;

    
        $tLates = 0;
        $tUndertime = 0;
        $tLatesWeeks = 0;
        $tUndertimeWeeks = 0;
        $tLastLatesWeeks = 0;
        $tLastUndertimeWeeks = 0;

        $totalDeficitText = "";
        $datetxt = "";


        $tUndertime = 0;
        $totalDeficit = 0;
        $tDaysExcess = 0;
        $tDaysDeficit = 0;

        $tDeficit = 0;
        $tDaysLeave = 0;
        $tTimeWeek = 0;
        $lastTimeWeek = 0;
        $lastWeekLeaves = 0;

        $tDaysDeficitTxt = "";

        $week1Time = 1920;

        $totalabsent = 0;

        $total = Carbon::parse($date)->daysInMonth;
                     
                      $prevweek = 1;
                      $rows .= "<tr><td></td><td colspan='8' align='center'>  <b>WEEK 1 </b> </td><td></td></tr>";
                      $week_num = 2;
                      $total_days = 0;
                      for($i = 1;$i <= $total;$i++)
                      {
                          
                        if(request()->deadline == 1)
                        {
                            $d1 = 1;
                            $d2 = 15;
                        }
                        else
                        {
                            $d1 = 16;

                            $lastDayofMonth = \Carbon\Carbon::parse($date.'-01')->endOfMonth()->toDateString();
                            $d2 = date('d',strtotime($lastDayofMonth));
                        }

                        if($i >= $d1 && $i <= $d2)
                        {
                          $weeknum = weekOfMonth(date($yr.'-'.request()->mon2.'-'.$i));
                          $dtr_date = date("Y-m-d",strtotime($yr.'-'.request()->mon2.'-'.$i));
                            if($weeknum == $prevweek)
                            {
                              
                            }
                            else
                            {
                              
                              $totalhrsweek = readableTime($tTimeWeek);
                              $tTotalDays = readableTime($total_days - $tDaysLeave);

                            //   if((($total_days - $tDaysLeave) - $tTimeWeek) <= 0)
                            //   {
                            //     $tDeficit = readableTime(0);
                            //     $totalDeficit += 0;
                            //   }
                            //   else
                            //   {
                            //       $tDeficit = readableTime(($total_days - $tDaysLeave) - $tTimeWeek)." ";
                            //       //$totalDeficit += ($total_days - $tDaysLeave) - $tTimeWeek;
                            //       $totalDeficit += $tDaysDeficit - $tDaysExcess;
                            //   }

                               //DEFICIT

                              if($tTotalDays <= 0)
                              {
                                $tDeficit = "";
                                $tDaysDeficit = 0;
                              }


                               if($tDaysDeficit > $tDaysExcess)
                               {
                                 $tDeficit = readableTime($tDaysDeficit - $tDaysExcess);
                                 $totalDeficit += $tDaysDeficit - $tDaysExcess;

                                //  $tDaysDeficitTxt .= $dtr_date." - ".$tDeficit.",";
                               }
                               else
                               {
                                 $tDaysExcess = 0;
                                 $tDeficit = "";
                                 $totalDeficit += 0;
                               }

                              

                              //$totalDeficitText .= $dtr_date.":".$tDaysDeficit.":".$tDeficit;
                                

                              $tLatesWeeks = readableTime($tLatesWeeks);
                              $tUndertimeWeeks = readableTime($tUndertimeWeeks);
                                
                              
                              $rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS(".$tTotalDays.") </b> </td><td align='center'><b>".$totalhrsweek." </b></td><td align='center'><b>".$tLatesWeeks."</b></td><td align='center'><b>".$tUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";

                              $prevweek = $weeknum;
                              $rows .= "<tr><td></td><td colspan='8' align='center'> <b>WEEK $week_num </b> </td><td></td></tr>";
                              $week_num++;
                              $total_days = 0;
                              $tTimeWeek = 0;
                              $tDaysLeave = 0;
                              $tDeficit = 0;
                              $tLatesWeeks = 0;
                              $tUndertimeWeeks = 0;
                              $tDaysDeficit = 0;
                              $tDaysExcess = 0;
                            }

                            

                            

                            //echo $dtr_date."<br/>";

                            $dayDesc = weekDesc($dtr_date);
                            $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id'],$emp['username']);
                            if(!$dtr)
                            {
                                $dtr = array();
                            }

                            
                                // //UDERTIME
                                // $under = null;
                                // if(countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],6))
                                // {
                                //     $undertime = explode("|", countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut']));
                                    
                                //     $under = $undertime[0];
                                //     $tUndertime += $undertime[1];
                                // }

                                // //LATES
                                // $lates = null;
                                // if(computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],6))
                                // {
                                //     $late = explode("|",computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn']));
                                //     $lates = $late[0];
                                //     $tLates += $late[1];
                                // }

                                switch ($dayDesc) {
                                    case 'Sat':
                                    case 'Sun':
                                        # code...
                                        break;
                                    
                                    default:
                                        if(!checkIfHoliday($dtr_date))
	                                        {
                                                //$datetxt .= $dtr_date."";
                                                $total_days += 480;

                                                $sus = App\Suspension::where('fldSuspensionDate',$dtr_date)->first();

                                                //CHECK IF ABSENT
                                                if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null)
				                                    {
                                                        if(!$sus)
					                                    {
                                                            if(checkIfHalfHoliday($dtr_date))
                                                            {
                                                                $totalabsent += 0.5;
                                                                $total_days -= 240;
                                                            }
                                                            else
                                                            {
                                                                $totalabsent += 1;
                                                                $total_days -= 480;
                                                            }
                                                            
                                                        }
                                                        else
                                                        {
                                                            if($sus['suspension_time_desc'] == 'AM' || $sus['suspension_time_desc'] == 'PM')
                                                            {
                                                                $totalabsent += 0.5;
                                                                $total_days -= 240;
                                                            }
                                                            
                                                        }
                                                    }

                                                if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] != null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null)
				                                    {
                                                        if(!$sus)
					                                    {
                                                            // if(checkIfHalfHoliday($dtr_date))
                                                            // {
                                                            //     $totalabsent += 0.5;
                                                            //     $total_days -= 240;
                                                            // }
                                                            //$totalabsent += 0.5;
                                                            //$total_days -= 240;
                                                        }
                                                    }
                                                
                                                if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] != null && $dtr['fldEmpDTRpmIn'] != null && $dtr['fldEmpDTRpmOut'] == null)
				                                    {
                                                        if(!$sus)
					                                    {
                                                            // if(checkIfHalfHoliday($dtr_date))
                                                            // {
                                                            //     $totalabsent += 0.5;
                                                            //     $total_days -= 240;
                                                            // }
                                                            //$totalabsent += 0.5;
                                                            //$total_days -= 240;
                                                        }
                                                    }
                                                
                                                if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] != null && $dtr['fldEmpDTRpmOut'] != null)
				                                    {
                                                        if(!$sus)
					                                    {
                                                            // if(checkIfHalfHoliday($dtr_date))
                                                            // {
                                                            //     $totalabsent += 0.5;
                                                            //     $total_days -= 240;
                                                            // }
                                                            //$totalabsent += 0.5;
                                                            //$total_days -= 240;
                                                        }
                                                    }
                                            }
                                        break;
                                }

                                
                                if($dtr_date <= '2022-03-06')
                                {
                                    $rows .= showDate($dtr,$dtr_date,$i,$dayDesc,$emp['id'],$emp['employment_id'],$emp['username'],null,null);
                                    
                                    if(checkIfHasLeave($dtr_date,$emp['id']))
		                            {
                                        $leave = getLeaveDetails($dtr_date,$emp['id']);
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
                                    $rowsArr = explode("|",plotDate($dtr,$i,$dayDesc,$dtr_date,$emp['id']));
                                    $rows .= $rowsArr[0];
                                    $tLates +=(int)$rowsArr[1];
                                    $tTimeWeek +=(int)$rowsArr[3];
                                    $tDaysLeave +=(int)$rowsArr[4];
                                    $tDaysExcess +=(int)$rowsArr[6];
                                    $tDaysDeficit +=(int)$rowsArr[7];
                                    //$tDaysDeficitTxt .= $dtr_date."(".(int)$rowsArr[7]."),";

                                    //$datetxt .= $dtr_date."|".readableTime($tTimeWeek)."--";

                                    if((int)$rowsArr[1] != 0)
                                    {
                                        //$tUndertimeWeeks .= $rowsArr[5]."|".(int)$rowsArr[2]."<br/>";
                                        $tLatesWeeks += (int)$rowsArr[1];
                                    }

                                    $tUndertime +=(int)$rowsArr[2];
                                    if((int)$rowsArr[2] != 0)
                                    {
                                        //$tUndertimeWeeks .= $rowsArr[5]."|".(int)$rowsArr[2]."<br/>";
                                        $tUndertimeWeeks += (int)$rowsArr[2];
                                    }

                                    

                                }
                                
                                

                               if($i == $d2)
                                {
                                    if(!checkIfWeekend($dtr_date))
				                    {
                                        $lastTimeWeek = $tTimeWeek;
                                        $lastWeekLeaves = $tDaysLeave;
                                        $tLastLatesWeeks = $tLatesWeeks;
                                        $tLastUndertimeWeeks = $tUndertimeWeeks;
                                        
                                        
                                        //LAST WEEK
                                        $totalhrsweek = readableTime($lastTimeWeek);
                                        //$totalhrsweek = $lastTimeWeek;
                                        $tTotalDays = readableTime($total_days);
                                        $tTotalDays = readableTime($total_days - $lastWeekLeaves);

                                        if((($total_days - $lastWeekLeaves) - $lastTimeWeek) <= 0)
                                        {
                                            $tDeficit = "";
                                        }
                                        else
                                        {
                                            // $tDeficit = "";
                                            //$tDeficit = ($total_days - $lastWeekLeaves) - $lastTimeWeek;
                                            // $tDaysDeficitTxt .= $tDeficit.",";

                                            if($tDaysDeficit > $tDaysExcess)
                                            {
                                                $tDeficit = readableTime($tDaysDeficit - $tDaysExcess);
                                                //$totalDeficit += $tDaysDeficit - $tDaysExcess;

                                                $tDaysDeficitTxt .= $dtr_date." - ".$tDeficit.",";
                                            }
                                            else
                                            {
                                                //$tDaysExcess = 0;
                                                $tDeficit = "";
                                                $totalDeficit = 0;
                                            }

                                            // if($tDeficit <= 0)
                                            // {
                                            //     $tDeficit = "";
                                            // }

                                            if($totalDeficit < 0)
                                            {
                                                $totalDeficit = 0;
                                            }
                                            else
                                            {
                                                if((($total_days - $lastWeekLeaves) - $lastTimeWeek) > 0)
                                                {
                                                    $totalDeficit = $totalDeficit + ($tDaysDeficit - $tDaysExcess);

                                                    //$tDaysDeficitTxt = $tDaysExcess;

                                                    //$tDaysDeficitTxt .= "totalDeficit : ".$totalDeficit."+ ((total_days : ".$total_days." - ".$lastWeekLeaves.") - lastTimeWeek : ".$lastTimeWeek.")";
                                                }
                                                    
                                            }
                                        }

                                    }
                                }

                                $totalhrsweek = readableTime($tTimeWeek);
                    }
                        
                 }
                    
                      $tLastLatesWeeks = readableTime($tLatesWeeks);
                      $tLastUndertimeWeeks = readableTime($tUndertimeWeeks);

                      $tTotalDays = readableTime($total_days);
                      
                      if($tDeficit <= 0)
                        $tDeficit = "";

                      $rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.") </b> </td><td align='center'><b>".$totalhrsweek." </b></td><td align='center'><b>".$tLastLatesWeeks."</b></td><td align='center'><b>".$tLastUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - DTR</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                    @page {
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
                            <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                <tr>
                                  <td style="border : 1px solid #FFF;width:20%" align="right">
                                  <img src="'.asset('img/DOST.png').'" style="width:100px">
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;width:20%" >

                                  </td>
                                </tr>
                            </table>
                                <center><h3><b>Daily Time Record (DTR)<br/>'.$mon2.'  '.$yr.'</b></h3></center>
                                <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                    <tr>
                                        <td style="width:90px"><b>Day</b></td><td style="width60px"><center><b>AM In</b></center></td><td style="width:65px"><center><b>AM Out</b></center></td><td style="width:60px"><center><b>PM In</b></center></td><td style="width:65px"><center><b>PM Out</b></center></td><td style="width:90px"><center><b>Total Hours</b></center></td><td><center><b>Lates</b></center></td><td><center><b>Undertime</b></center></td><td><center><b>Deficit</b></center></td><td style="width:150px"><center><b>Remarks</b></center></td>
                                    </tr>
                                    <tbody>
                                        '.$rows.'
                                    </tbody>
                                </table>
                                <br>
                                <br>
                                <br>
                                <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:12px">
                                        <b>Total Lates : </b> '.readableTime($tLates).'<br>
                                        <b>Total Undertime : </b>'.readableTime($tUndertime).'<br>
                                        <b>Total Deficit : </b>'.readableTime($totalDeficit).''.$tDaysDeficitTxt.'<br>
                                        <b>Total Absences : </b>'.$totalabsent.' d<br>'.$datetxt.'<br><br>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:15px" align="center">'.mb_strtoupper(strtolower($emp['fname'].' '.substr($emp['mname'],0,1).'. '.$emp['lname'])).'<br><small><b>Name of Employee</b></small></td>
                                  <td style="border : 1px solid #FFF;font-size:15px" align="center">'.getDirector($emp['division'],$emp['id']).'</td>
                                <tr>
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'portrait');
        return $pdf->stream();
    }

    public function pdf22()
    {
        // return request()->mon2;
        $worksched = getDTROption();

        $emp = App\User::where('id',request()->userid2)->first();

        $rows = "";
        
        $mon = date('m',strtotime(request()->mon2));
        $mon2 = date('F',mktime(0, 0, 0, request()->mon2, 10));
        $yr = request()->yr2;
        $date = $mon2 ."-" . request()->yr2;
        $month = ++$mon;

        $tLates = 0;
        $tUndertime = 0;
        $tLatesWeeks = 0;
        $tUndertimeWeeks = 0;
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

        $total = Carbon::parse($date)->daysInMonth;
                     
                      $prevweek = 1;
                      $rows .= "<tr><td></td><td colspan='8' align='center'>  <b>WEEK 1 </b> </td><td></td></tr>";
                      $week_num = 2;
                      $total_days = 0;
                      for($i = 1;$i <= $total;$i++)
                      {
                          $weeknum = weekOfMonth(date($yr.'-'.request()->mon2.'-'.$i));
                            if($weeknum == $prevweek)
                            {
                              
                            }
                            else
                            {
                              
                              $totalhrsweek = readableTime($tTimeWeek);
                              $tTotalDays = readableTime($total_days - $tDaysLeave);
                              $grandTotalHrs += $total_days - $tDaysLeave;
                              $grandTotalHrsRendered += $tTimeWeek;

                            //   if((($total_days - $tDaysLeave) - $tTimeWeek) <= 0)
                            //   {
                            //     $tDeficit = readableTime(0);
                            //     $totalDeficit += 0;
                            //   }
                            //   else
                            //   {
                            //       $tDeficit = readableTime(($total_days - $tDaysLeave) - $tTimeWeek)." ";
                            //       $totalDeficit += ($total_days - $tDaysLeave) - $tTimeWeek;
                            //   }
                            
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

                              
                                
                              
                              $rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.")</b> </td><td align='center'><b>".$totalhrsweek."</b></td><td align='center'><b>".$tLatesWeeks."</b></td><td align='center'><b>".$tUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";

                              $prevweek = $weeknum;
                              $rows .= "<tr><td></td><td colspan='8' align='center'> <b>WEEK $week_num </b> </td><td></td></tr>";
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

                            

                            $dtr_date = date("Y-m-d",strtotime($yr.'-'.request()->mon2.'-'.$i));

                            //echo $dtr_date."<br/>";

                            $dayDesc = weekDesc($dtr_date);
                            $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id'],$emp['username']);
                            if(!$dtr)
                            {
                                $dtr = array();
                            }

                            
                                // //UDERTIME
                                // $under = null;
                                // if(countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],6))
                                // {
                                //     $undertime = explode("|", countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut']));
                                    
                                //     $under = $undertime[0];
                                //     $tUndertime += $undertime[1];
                                // }

                                // //LATES
                                // $lates = null;
                                // if(computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],6))
                                // {
                                //     $late = explode("|",computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn']));
                                //     $lates = $late[0];
                                //     $tLates += $late[1];
                                // }

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
                                    $rows .= showDate($dtr,$dtr_date,$i,$dayDesc,$emp['id'],$emp['employment_id'],$emp['username'],null,null);
                                    
                                    if(checkIfHasLeave($dtr_date,$emp['id']))
		                            {
                                        $leave = getLeaveDetails($dtr_date,$emp['id']);
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
                                    $rowsArr = explode("|",plotDate($dtr,$i,$dayDesc,$dtr_date,$emp['id']));
                                    $rows .= $rowsArr[0];
                                    $tLates +=(int)$rowsArr[1];
                                    //$tUndertime .=(int)$rowsArr[5]."-";
                                    $tTimeWeek +=(int)$rowsArr[3];
                                    $tDaysLeave +=(int)$rowsArr[4];
                                    $tDaysExcess +=(int)$rowsArr[6];
                                    $tDaysDeficit +=(int)$rowsArr[7];


                                    //$tLatesWeeks += $tLates;
                                    if((int)$rowsArr[1] > 0)
                                    {
                                        //$tUndertimeWeeks .= $rowsArr[5]."|".(int)$rowsArr[2]."<br/>";
                                        $tLatesWeeks += (int)$rowsArr[1];
                                        $tLateCTR++;
                                    }

                                    $tUndertime +=(int)$rowsArr[2];
                                    if((int)$rowsArr[2] > 0)
                                    {
                                        //$tUndertimeWeeks .= $rowsArr[5]."|".(int)$rowsArr[2]."<br/>";
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

                    //   if((($total_days - $lastWeekLeaves) - $lastTimeWeek) <= 0)
                    //   {
                    //     $tDeficit = readableTime(0);
                    //   }
                    //   else
                    //   {
                    //     $tDeficit = readableTime(($total_days - $lastWeekLeaves) - $lastTimeWeek)." ";


                    //     if($totalDeficit < 0)
                    //     {
                    //         $totalDeficit = 0;
                    //     }
                    //     else
                    //     {
                    //         if((($total_days - $lastWeekLeaves) - $lastTimeWeek) > 0)
                    //         {
                    //             $totalDeficit = $totalDeficit + (($total_days - $lastWeekLeaves) - $lastTimeWeek);
                    //         }
                                
                    //     }
                    //   }

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


                      $rows .= "<tr><td></td><td colspan='4' align='right' style='padding-right:5px'> <b>TOTAL HRS (".$tTotalDays.")</b> </td><td align='center'><b>".$totalhrsweek."</b></td><td align='center'><b>".$tLastLatesWeeks."</b></td><td align='center'><b>".$tLastUndertimeWeeks."</b></td><td align='center'><b>".$tDeficit."</b></td><td></td></tr>";
        //DISPLAY LATES
        // $hourslate = floor($tLates / 60);
        // $minuteslate = $tLates % 60;

        // //DISPLAY UNDERTIME
        // $hoursunder = floor($tUndertime / 60);
        // $minutesunder = $tUndertime % 60;

    //    if(getDTROption() == 6)
    //    {
    //          $hoursunder = 0;
    //          $minutesunder = 0;
    //    }

    //     if($emp['employment_id'] != 8)
    //     {
    //         $hourslate = 0;
    //         $minuteslate = 0;
    //     }

        //COUNT NO. LEAVES
        $leaves_total = 0;
        $l1_total = App\Request_leave::where('user_id',$emp['id'])->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon2)->whereYear('leave_date_from',request()->yr2)->get();

        $dtss = "";

        foreach ($l1_total as $key => $value) {
            $dayDesc2 = weekDesc($value->leave_date_from);
            if(!checkIfHoliday($value->leave_date_from))
			{
                if($dayDesc2 != 'Sat' && $dayDesc2 != 'Sun')
                {
                    
                    $leaves_total++;
                    $dtss .= $value->leave_date_from." -- ";
                }
            }
            
        }

        //SINGLE DATE
        $l2_total = App\Request_leave::where('user_id',$emp['id'])->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon2)->whereYear('leave_date_from',request()->yr2)->sum('leave_deduction');

        $l_total = $leaves_total + $l2_total;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - DTR</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                    @page {
                                      margin: 20;
                                    }
                                body
                                {
                                    font-family:Helvetica;
                                }
                                th,td
                                {
                                    border:1px solid #555;
                                    font-size:13px;
                                }
                            </style>
                            <body>
                            <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                <tr>
                                  <td style="border : 1px solid #FFF;width:20%" align="right">
                                  <img src="https://photos.app.goo.gl/1wU4DyN4RDk15BN16" style="width:100px">
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;width:20%" >

                                  </td>
                                </tr>
                            </table>
                                <center><h3><b>Daily Time Record (DTR)<br/>'.$mon2.'  '.$yr.'</b></h3></center>
                                <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                    <tr>
                                        <td style="width:90px"><b>Day</b></td><td style="width60px"><center><b>AM In</b></center></td><td style="width:65px"><center><b>AM Out</b></center></td><td style="width:60px"><center><b>PM In</b></center></td><td style="width:65px"><center><b>PM Out</b></center></td><td style="width:90px"><center><b>Total Hours</b></center></td><td><center><b>Lates</b></center></td><td><center><b>Undertime</b></center></td><td><center><b>Deficit</b></center></td><td style="width:150px"><center><b>Remarks</b></center></td>
                                    </tr>
                                    <tbody>
                                        '.$rows.'
                                        <tr><td></td><td colspan="4" align="right" style="padding-right:5px"> <b>TOTAL </b></td><td align="center"><b></b></td><td align="center"><b>'.readableTime($tLates).'</b></td><td align="center"><b>'.readableTime($tUndertime).'</b></td><td align="center"><b>'.readableTime($totalDeficit).'</b></td><td></td></tr>
                                    </tbody>
                                </table>
                                <br>
                                <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:12px">
                                        <b>Total no. of lates : </b> '.$tLateCTR.'<br>
                                        <b>Total no. of undertime : </b>'.$tUndertimeCTR.'<br>
                                        <b>Total hours deficit : </b>'.readableTime($totalDeficit).'<br>
                                        <b>Total no. of leave days : </b>'.$l_total.'d<br>
                                        <b>Total no. of unauthorized absences : </b><br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px" valign="top">
                                        <b>Total late hours : </b> '.readableTime($tLates).'<br>
                                        <b>Total undertime hours : </b>'.readableTime($tUndertime).'<br>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="border : 1px solid #FFF;font-size:15px" align="center">'.mb_strtoupper(strtolower($emp['fname'].' '.substr($emp['mname'],0,1).'. '.$emp['lname'])).'<br><small><b>Name of Employee</b></small></td>
                                  <td style="border : 1px solid #FFF;font-size:15px" align="center">'.getDirector($emp['division'],$emp['id']).'</td>
                                <tr>
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'portrait');
        return $pdf->stream();
    }

    public function icospayroll($mon,$yr,$period)
    {
        $data = 
                [
                    "nav" => nav("icospayroll"),
                    "mon" => $mon,
                    "yr" => $yr,
                    "period" => $period,
                ];

        return view('payroll.icospayroll')->with("data",$data);
    }

    public function icospayrollupdate()
    {
       
        switch (request()->deduc_type) {
            case 'charging':
            case 'salary':
            case 'atm':
            case 'tax_rate':
                    $info = App\Payroll\SalaryCOS::where('user_id',request()->deduc_userid)->first();
                    App\Payroll\SalaryCOS::where('user_id',request()->deduc_userid)
                            ->update([
                                request()->deduc_type => request()->deduc_val,
                            ]);
                break;
            
            default:
                    //CHECK IF MERON
                    $ded = App\Payroll\DeductionCOS::where('user_id',request()->deduc_userid)->where('deduction',request()->deduc_type)->count();

                    if($ded > 0)
                    {
                        App\Payroll\DeductionCOS::where('user_id',request()->deduc_userid)->where('deduction',request()->deduc_type)
                            ->update([
                                'amt' => request()->deduc_val,
                                'period' => request()->period,
                                'updated_by' => Auth::user()->id,
                            ]);
                    }
                    else
                    {
                        
                        $d = new App\Payroll\DeductionCOS;
                        $d->user_id = request()->deduc_userid;
                        $d->deduction = request()->deduc_type;
                        $d->amt = request()->deduc_val;
                        $d->period = request()->period;
                        $d->created_by = Auth::user()->id;
                        $d->save();
                    }
                break;
        }
        

        $data = 
                [
                    "nav" => nav("icospayroll"),
                    "mon" => request()->mon,
                    "yr" => request()->yr,
                    "period" => request()->period,
                ];

            return redirect('icos/payroll/'.request()->mon."/".request()->yr."/".request()->period)->with("data",$data);
    }

    public function updateORS()
    {

        App\Payroll\ProcessCOS::where('id',request()->process_id)
                ->update([
                    'ors' => request()->ors_num
                ]);

        App\Payroll\SalaryCOS::where('user_id',request()->deduc_userid)
                ->update([
                    'ors' => request()->ors_num
                ]);
        

        $data = 
                [
                    "nav" => nav("icospayroll"),
                    "mon" => request()->mon,
                    "yr" => request()->yr,
                    "period" => request()->period,
                ];

        return redirect('icos/payroll/'.request()->mon."/".request()->yr."/".request()->period)->with("data",$data);
    }

    public function icosprocess()
    {
        $data = 
                [
                    "nav" => nav("icosprocess"),
                ];

        return view('dtr.icos-process-dtr')->with("data",$data);;
    }

    public function cosemp($mon,$yr,$period)
    {
        
        $data = [
                    "nav" => nav("icosprocess"),
                    "mon" => $mon,
                    "year" => $yr,
                    "period" => $period,
                ];

        return view('dtr.cos-emp')->with("data",$data);
    }

    public function cofinalprocess(Request $request)
    {
        $i = 0;
        foreach (request()->check_request as $key => $value) {

            //return "No days ".$request["numdays_".$value];

            //GET SALARY
            $salary = getInfoCOS($value,'salary');
            $ors = getInfoCOS($value,'ors');

            //GET CHARGING
            $user = App\Payroll\SalaryCOS::where('user_id',$value)->first();
            $charging = null;
            if(isset($user))
                $charging = $user['charging'];

            //GET DAYS IN MONTH
            $daysinmonth = getDaysInMonth(request()->yr.'-'.request()->mon.'-01');
            switch ($daysinmonth) {
                case 28:
                    $period2 = 13;
                break;
                case 29:
                    $period2 = 14;
                break;
                case 30:
                    $period2 = 15;
                break;
                case 31:
                    $period2 = 16;
                break;
            }
            $period = 15;
            if(request()->period == 2)
                $period = $period2;

            
            
            //DAYS IN MONTH
            $daysinmonth = getDaysInMonth(request()->yr.'-'.request()->mon.'-01');


            $p = new App\Payroll\ProcessCOS;
            $p->user_id = $value;
            $p->division = Auth::user()->division;
            $p->ors = $ors;
            $p->salary = $salary;
            $p->charging = $charging;
            $p->mon = request()->mon;
            $p->yr = request()->yr;
            $p->daysmonth = $daysinmonth;
            $p->daysperiod = $period;
            $p->period = request()->period;
            $p->nodays = $request["numdays_".$value];
            $p->absent = $request["absent_".$value];
            $p->late = $request["late_".$value];
            $p->undertime = $request["undertime_".$value];
            $p->deficit = $request["deficit_".$value];
            $p->save();

            $i++;
        }

        $data = 
                [
                    "nav" => nav("icosprocess")
                ];

        return redirect('icos/payroll/'.request()->mon.'/'.request()->yr.'/'.request()->period);
    }

    public function cofinalprocesspayroll()
    {
        //UPDATE DEDUCTION
        // foreach(getICOSDivision() AS $divs)
        // {
        //     $hdmf = getDeductionCOS($divs->id,'HDMF',request()->process_mon,request()->process_yr,request()->process_period,'num');
        //     $pmpc = getDeductionCOS($divs->id,'PMPC',request()->process_mon,request()->process_yr,request()->process_period,'num');

        //     App\Payroll\ProcessCOS::where('user_id',$divs->id)->where('mon',request()->process_mon)->where('yr',request()->process_yr)->where('period',request()->process_period)
        //                     ->update([
        //                         'hdmf' => $hdmf,
        //                         'pmpc' => $pmpc,
        //                         'process_date' => date('Y-m-d H:i:s'),
        //                         'process_by' => Auth::user()->id,
        //                     ]);
        // }
        $proc = App\Payroll\ProcessCOS::whereNull('process_date')->where('mon',request()->process_mon)->where('yr',request()->process_yr)->where('period',request()->process_period)->get();
        foreach ($proc as $key => $procs) {
            
            //$itw = getDeductionCOS($procs->user_id,'ITW',request()->process_mon,request()->process_yr,request()->process_period,'num');

            $tax = App\Payroll\SalaryCOS::where('user_id',$procs->user_id)->first();

            $itw = $tax['tax_rate'];

            $hdmf = getDeductionCOS($procs->user_id,'HDMF',request()->process_mon,request()->process_yr,request()->process_period,'num');

            $pmpc = getDeductionCOS($procs->user_id,'PMPC',request()->process_mon,request()->process_yr,request()->process_period,'num');


            App\Payroll\ProcessCOS::where('id',$procs->id)
                            ->update([
                                'itw' => $itw,
                                'hdmf' => $hdmf,
                                'pmpc' => $pmpc,
                                'process_date' => date('Y-m-d H:i:s'),
                                'process_by' => Auth::user()->id,
                            ]);
        }

        //LOCK PAYROLL
        $lockpayroll = new App\Payroll\ProcessCOSLock;
        $lockpayroll->mon = request()->process_mon;
        $lockpayroll->yr = request()->process_yr;
        $lockpayroll->period = request()->process_period;
        $lockpayroll->save();

    }

    public function printpayrollcos($mon,$yr,$period,$dt,$type = null,$charging = null)
    {
        $text101 = ""; 
        $text184 = ""; 
        $text184C = "";        
        //PERIOD
        $per = getPeriodCOS($period,$mon,$yr);
        $mon2 = date('F',mktime(0, 0, 0, $mon, 10));

        $row = "";
        $row2 = "";
        $row3 = "";
        $total_salary = 0;
        $total_earned = 0; 
        $total_wp = 0; 
        $total_earned = 0; 
        $total_itw = 0; 
        $total_hdmf = 0; 
        $total_pmpc = 0;
        $total_deductions = 0; 
        $total_net = 0; 
        $header_ctr = 0;
        //GET STAFF 101
        //return getICOSProcess("101",$mon,$yr,$period);
        foreach(getICOSProcess("101",$mon,$yr,$period,$dt) AS $divs)
        {
            //ORS
            $ors = getProcessInfoCOS($divs->user_id,$mon,$yr,$period,'ors');

            //DIVISION
            $div = getDivision($divs->division);

            //SALARY
            $salary = $divs->salary;
            $total_salary += $salary;

            //POSITION
            $pos = getInfoCOS($divs->user_id,'position');
            //$pos = getPosition($pos);

            //TOTAL DAYS
            $totaldays = $divs->daysmonth;

            //PERIOD DAYS
            $daysperiod = $divs->daysperiod;

            //NO OF DAYS
            $nodays = $divs->nodays;

            //EARNED FOR THE PERIOD
            $earnedperiod = ($salary / $totaldays) * ($daysperiod - $nodays);
            //$earnedperiod = ceil($earnedperiod * 100) / 100;
            $earnedperiod = round($earnedperiod,2);
            $total_earned += $earnedperiod;

            //ITW
            $itw = $divs->itw;
            if($itw == 0 || $itw == null)
                $itw = 0;
            else
            {
                $itw = $earnedperiod * $divs->itw;;
                //$itw = ceil($itw * 100) / 100;
                $itw = round($itw,2);
            }

            $total_itw += $itw;

            //HDMF
            $hdmf = $divs->hdmf;
            $total_hdmf += $hdmf;

            //PMPC
            $pmpc = $divs->pmpc;
            $total_pmpc += $pmpc;

            //DEDUCTIONS
            $deductions = $itw + $hdmf + $pmpc;
            $total_deductions += $deductions;

            //WITHOUT PAY
            $withoutpay = ($salary / $totaldays) * $nodays;
            //$withoutpay = ceil($withoutpay * 100) / 100;
            $withoutpay = round($withoutpay,2);
            $total_wp += $withoutpay;

            

            //NET
            $net = $earnedperiod - $deductions;
            $total_net += $net;

            $staff = App\User::where('id',$divs->user_id)->first();

            if(!$staff)
            {
                $staff = App\Payroll\DeletedCOS::where('id',$divs->user_id)->first();
                //return $staff['lname']." ".$staff['fname']." ".$staff['mname']." ".$net." ".$period;
                //return $staff;
            }
                
                

            $text101 .= convertTextCOSPayroll($divs->user_id,$staff['lname'],$staff['fname'],$staff['mname'],formatCash($net),$period);

            if($header_ctr == 15)
            {
                $hdead = '</table><div class="page-break"></div><center><h4><b>PAYMENT OF SALARIES OF 101 PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center><table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed"><tr>
                <td style="width:15%"><b>Personnel</b></td>
                <td ><center><b>Division</b></center></td>
                <td style="width:10%"><center><b>Designation</b></center></td>
                <td><center><b>ORS No.</b></center></td>
                <td><center><b>Monthly Rate</b></center></td>
                <td><center><b>No of Days in the Month</b></center></td>
                <td><center><b>No of Days in the Period</b></center></td>
                <td colspan="2"><center><b>Days Without Pay</b></center></td>
                <td><center><b>Earned for the Period</b></center></td>
                <td><center><b>ITW</b></center></td>
                <td><center><b>HDMF</b></center></td>
                <td><center><b>PMPC</b></center></td>
                <td><center><b>Total Deductions</b></center></td>
                <td><center><b>Net</b></center></td>
            </tr> <div class="page-break"></div>';

            $header_ctr = 0;
            }
            else
            {
                $hdead = '';
                
            }

            $row .= $hdead.'<tr>
                    <td style="width:15%">'.$staff['lname'].', '.ucwords(strtolower($staff['fname'].' '.substr($staff['mname'],0,1).'.')).'</td>
                    <td align="center">'.$div.'</td>
                    <td align="center">'.$pos.'</td>
                    <td align="center">'.$ors.'</td>
                    <td align="right">'.formatCash($salary).'</td>
                    <td align="center">'.$totaldays.'</td>
                    <td align="center">'.$daysperiod.'</td>
                    <td align="right">'.ifNull($nodays).'</td>
                    <td align="right">'.formatCash($withoutpay).'</td>
                    <td align="right">'.formatCash($earnedperiod).'</td>
                    <td align="right">'.formatCash($itw).'</td>
                    <td align="right">'.formatCash($hdmf).'</td>
                    <td align="right">'.formatCash($pmpc).'</td>
                    <td align="right">'.formatCash($deductions).'</td>
                    <td align="right">'.formatCash($net).'</td>
                    </tr>';

                    $header_ctr++;
        }

        //return $row;

        $tbl1 = '<center><h4><b>PAYMENT OF SALARIES OF 101 PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center>
        <table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed">
            <tr>
                <td style="width:15%"><b>Personnel</b></td>
                <td ><center><b>Division</b></center></td>
                <td style="width:10%"><center><b>Designation</b></center></td>
                <td><center><b>ORS No.</b></center></td>
                <td><center><b>Monthly Rate</b></center></td>
                <td><center><b>No of Days in the Month</b></center></td>
                <td><center><b>No of Days in the Period</b></center></td>
                <td colspan="2"><center><b>Days Without Pay</b></center></td>
                <td><center><b>Earned for the Period</b></center></td>
                <td><center><b>ITW</b></center></td>
                <td><center><b>HDMF</b></center></td>
                <td><center><b>PMPC</b></center></td>
                <td><center><b>Total Deductions</b></center></td>
                <td><center><b>Net</b></center></td>
            </tr>
            '.$row.'
        </table>';
        
        //GET STAFF
        $total_salary2 = 0;
        $total_earned2 = 0; 
        $total_wp2 = 0; 
        $total_earned2 = 0; 
        $total_itw2 = 0; 
        $total_hdmf2 = 0; 
        $total_pmpc2 = 0;
        $total_deductions2 = 0; 
        $total_net2 = 0; 
        $header_ctr = 0;

        //GET STAFF 184
        //return getICOSProcess("101",$mon,$yr,$period);
        foreach(getICOSProcess("184",$mon,$yr,$period,$dt) AS $divs)
        {
            //ORS
            $ors = getProcessInfoCOS($divs->user_id,$mon,$yr,$period,'ors');

            //DIVISION
            $div = getDivision($divs->division);

            //SALARY
            $salary = $divs->salary;
            $total_salary2 += $salary;

            //POSITION
            $pos = getInfoCOS($divs->user_id,'position');
            //$pos = getPosition($pos);

            //TOTAL DAYS
            $totaldays = $divs->daysmonth;

            //PERIOD DAYS
            $daysperiod = $divs->daysperiod;

            //NO OF DAYS
            $nodays = $divs->nodays;

            //EARNED FOR THE PERIOD
            $earnedperiod = ($salary / $totaldays) * ($daysperiod - $nodays);
            //$earnedperiod = ceil($earnedperiod * 100) / 100;
            $earnedperiod = round($earnedperiod,2);
            $total_earned2 += $earnedperiod;

            //ITW
            $itw = $divs->itw;
            if($itw == 0 || $itw == null)
                $itw = 0;
            else
            {
                $itw = $earnedperiod * $divs->itw;;
                //$itw = ceil($itw * 100) / 100;
                $itw = round($itw,2);
            }

            $total_itw2 += $itw;
            //HDMF
            $hdmf = $divs->hdmf;
            $total_hdmf2 += $hdmf;

            //PMPC
            $pmpc = $divs->pmpc;
            $total_pmpc2 += $pmpc;

            //DEDUCTIONS
            $deductions = $itw + $hdmf + $pmpc;
            $total_deductions2 += $deductions;

            //WITHOUT PAY
            $withoutpay = ($salary / $totaldays) * $nodays;
            //$withoutpay = ceil($withoutpay * 100) / 100;
            $withoutpay = round($withoutpay,2);
            $total_wp2 += $withoutpay;

            //NET
            $net = $earnedperiod - $deductions;
            $total_net2 += $net;

            $staff = App\User::where('id',$divs->user_id)->first();

            if(!$staff)
            {
                $staff = App\Payroll\DeletedCOS::where('id',$divs->user_id)->first();
                //return $staff['lname']." ".$staff['fname']." ".$staff['mname']." ".$net." ".$period;
                //return $staff;
            }

            $text184 .= convertTextCOSPayroll($divs->user_id,$staff['lname'],$staff['fname'],$staff['mname'],formatCash($net),$period);

            if($header_ctr == 15)
            {
                $hdead = '</table><div class="page-break"></div><center><h4><b>PAYMENT OF SALARIES OF 184 PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center><table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed"><tr>
                <td style="width:15%"><b>Personnel</b></td>
                <td ><center><b>Division</b></center></td>
                <td style="width:10%"><center><b>Designation</b></center></td>
                <td><center><b>ORS No.</b></center></td>
                <td><center><b>Monthly Rate</b></center></td>
                <td><center><b>No of Days in the Month</b></center></td>
                <td><center><b>No of Days in the Period</b></center></td>
                <td colspan="2"><center><b>Days Without Pay</b></center></td>
                <td><center><b>Earned for the Period</b></center></td>
                <td><center><b>ITW</b></center></td>
                <td><center><b>HDMF</b></center></td>
                <td><center><b>PMPC</b></center></td>
                <td><center><b>Total Deductions</b></center></td>
                <td><center><b>Net</b></center></td>
            </tr> <div class="page-break"></div>';

            $header_ctr = 0;
            }
            else
            {
                $hdead = '';
                
            }

            $row2 .= $hdead.'<tr>
                    <td style="width:15%">'.ucwords(strtolower($staff['lname'].', '.$staff['fname'].' '.substr($staff['mname'],0,1).'.')).'</td>
                    <td align="center">'.$div.'</td>
                    <td align="center">'.$pos.'</td>
                    <td align="center">'.$ors.'</td>
                    <td align="right">'.formatCash($salary).'</td>
                    <td align="center">'.$totaldays.'</td>
                    <td align="center">'.$daysperiod.'</td>
                    <td align="right">'.ifNull($nodays).'</td>
                    <td align="right">'.formatCash($withoutpay).'</td>
                    <td align="right">'.formatCash($earnedperiod).'</td>
                    <td align="right">'.formatCash($itw).'</td>
                    <td align="right">'.formatCash($hdmf).'</td>
                    <td align="right">'.formatCash($pmpc).'</td>
                    <td align="right">'.formatCash($deductions).'</td>
                    <td align="right">'.formatCash($net).'</td>
                    </tr>';

                    $header_ctr++;
        }

        //return $row;

        $tbl2 = '<center><h4><b>PAYMENT OF SALARIES OF 184 PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center>
        <table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed">
            <tr>
                <td style="width:15%"><b>Personnel</b></td>
                <td ><center><b>Division</b></center></td>
                <td style="width:10%"><center><b>Designation</b></center></td>
                <td><center><b>ORS No.</b></center></td>
                <td><center><b>Monthly Rate</b></center></td>
                <td><center><b>No of Days in the Month</b></center></td>
                <td><center><b>No of Days in the Period</b></center></td>
                <td colspan="2"><center><b>Days Without Pay</b></center></td>
                <td><center><b>Earned for the Period</b></center></td>
                <td><center><b>ITW</b></center></td>
                <td><center><b>HDMF</b></center></td>
                <td><center><b>PMPC</b></center></td>
                <td><center><b>Total Deductions</b></center></td>
                <td><center><b>Net</b></center></td>
            </tr>
            '.$row2.'
        </table>';

                //GET STAFF Coconut
                $total_salary3 = 0;
                $total_earned3 = 0; 
                $total_wp3 = 0; 
                $total_earned3 = 0; 
                $total_itw3 = 0; 
                $total_hdmf3 = 0; 
                $total_pmpc3 = 0;
                $total_deductions3 = 0; 
                $total_net3 = 0; 
                $header_ctr = 0;


                //GET STAFF 184 COCONUT
                foreach(getICOSProcess("184C",$mon,$yr,$period,$dt) AS $divs)
                {
                    //ORS
                    $ors = getProcessInfoCOS($divs->user_id,$mon,$yr,$period,'ors');
        
                    //DIVISION
                    $div = getDivision($divs->division);
        
                    //SALARY
                    $salary = $divs->salary;
                    $total_salary3 += $salary;
        
                    //POSITION
                    $pos = getInfoCOS($divs->user_id,'position');
                    //$pos = getPosition($pos);
        
                    //TOTAL DAYS
                    $totaldays = $divs->daysmonth;
        
                    //PERIOD DAYS
                    $daysperiod = $divs->daysperiod;
        
                    //NO OF DAYS
                    $nodays = $divs->nodays;
        
                    //EARNED FOR THE PERIOD
                    $earnedperiod = ($salary / $totaldays) * ($daysperiod - $nodays);
                    //$earnedperiod = ceil($earnedperiod * 100) / 100;
                    $earnedperiod = round($earnedperiod,2);
                    $total_earned3 += $earnedperiod;

                    //ITW
                    $itw = $divs->itw;
                    if($itw == 0 || $itw == null)
                        $itw = 0;
                    else
                    {
                        $itw = $earnedperiod * $divs->itw;;
                        //$itw = ceil($itw * 100) / 100;
                        $itw = round($itw,2);
                    }
        
                    $total_itw3 += $itw;
                    //HDMF
                    $hdmf = $divs->hdmf;
                    $total_hdmf3 += $hdmf;
        
                    //PMPC
                    $pmpc = $divs->pmpc;
                    $total_pmpc3 += $pmpc;
        
                    //DEDUCTIONS
                    $deductions = $itw + $hdmf + $pmpc;
                    $total_deductions3 += $deductions;
        
                    //WITHOUT PAY
                    $withoutpay = ($salary / $totaldays) * $nodays;
                    //$withoutpay = ceil($withoutpay * 100) / 100;
                    $withoutpay = round($withoutpay,2);
                    $total_wp3 += $withoutpay;

                    //NET
                    $net = $earnedperiod - $deductions;
                    $total_net3 += $net;

                    $staff = App\User::where('id',$divs->user_id)->first();
        
                    if(!$staff)
                    {
                        $staff = App\Payroll\DeletedCOS::where('id',$divs->user_id)->first();
                        //return $staff['lname']." ".$staff['fname']." ".$staff['mname']." ".$net." ".$period;
                        //return $staff;
                    }

                    $text184C .= convertTextCOSPayroll($divs->user_id,$staff['lname'],$staff['fname'],$staff['mname'],formatCash($net),$period);

                    if($header_ctr == 15)
                        {
                            $hdead = '</table><div class="page-break"></div><center><h4><b>PAYMENT OF SALARIES OF 184(Coco Levy Fund) PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center><table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed"><tr>
                            <td style="width:15%"><b>Personnel</b></td>
                            <td ><center><b>Division</b></center></td>
                            <td style="width:10%"><center><b>Designation</b></center></td>
                            <td><center><b>ORS No.</b></center></td>
                            <td><center><b>Monthly Rate</b></center></td>
                            <td><center><b>No of Days in the Month</b></center></td>
                            <td><center><b>No of Days in the Period</b></center></td>
                            <td colspan="2"><center><b>Days Without Pay</b></center></td>
                            <td><center><b>Earned for the Period</b></center></td>
                            <td><center><b>ITW</b></center></td>
                            <td><center><b>HDMF</b></center></td>
                            <td><center><b>PMPC</b></center></td>
                            <td><center><b>Total Deductions</b></center></td>
                            <td><center><b>Net</b></center></td>
                        </tr> <div class="page-break"></div>';

                        $header_ctr = 0;
                        }
                        else
                        {
                            $hdead = '';
                            
                        }
        
                    $row3 .= $hdead.'<tr>
                            <td style="width:15%">'.$staff['lname'].', '.$staff['fname'].' '.substr($staff['mname'],0,1).'.'.'</td>
                            <td align="center">'.$div.'</td>
                            <td align="center">'.$pos.'</td>
                            <td align="center">'.$ors.'</td>
                            <td align="right">'.formatCash($salary).'</td>
                            <td align="center">'.$totaldays.'</td>
                            <td align="center">'.$daysperiod.'</td>
                            <td align="right">'.ifNull($nodays).'</td>
                            <td align="right">'.formatCash($withoutpay).'</td>
                            <td align="right">'.formatCash($earnedperiod).'</td>
                            <td align="right">'.formatCash($itw).'</td>
                            <td align="right">'.formatCash($hdmf).'</td>
                            <td align="right">'.formatCash($pmpc).'</td>
                            <td align="right">'.formatCash($deductions).'</td>
                            <td align="right">'.formatCash($net).'</td>
                            </tr>';
                    $header_ctr++;
                }
        
                //return $row;
        
                $tbl3 = '<center><h4><b>PAYMENT OF SALARIES OF 184(Coco Levy Fund) PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center>
                <table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed">
                    <tr>
                        <td style="width:15%"><b>Personnel</b></td>
                        <td ><center><b>Division</b></center></td>
                        <td style="width:10%"><center><b>Designation</b></center></td>
                        <td><center><b>ORS No.</b></center></td>
                        <td><center><b>Monthly Rate</b></center></td>
                        <td><center><b>No of Days in the Month</b></center></td>
                        <td><center><b>No of Days in the Period</b></center></td>
                        <td colspan="2"><center><b>Days Without Pay</b></center></td>
                        <td><center><b>Earned for the Period</b></center></td>
                        <td><center><b>ITW</b></center></td>
                        <td><center><b>HDMF</b></center></td>
                        <td><center><b>PMPC</b></center></td>
                        <td><center><b>Total Deductions</b></center></td>
                        <td><center><b>Net</b></center></td>
                    </tr>
                    '.$row3.'
                </table>';

        //PREPARED BY POSITION
        $plantilla = getPlantillaInfo(Auth::user()->username);
        //$post =  $plantilla['position_desc'];

        if($type == 'print')
        {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML('<!DOCTYPE html>
                                <html>
                                <head>
                                <title>HRMIS - COS PAYROLL</title>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                                </head>
                                <style type="text/css">
                                        @page {
                                        margin: 10;
                                        }
                                    body
                                    {
                                        font-family:Helvetica;
                                    }
                                    td
                                    {
                                        border:1px solid #555;
                                        font-size:11px;
                                    }
                                    .page-break {
                                        page-break-after: always;
                                    }
                                </style>
                                <body>
                                    '.$tbl1.'
                                    <div class="page-break"></div>
                                    <center><h4><b>PAYMENT OF SALARIES OF 101 PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center>
                                    <table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed">
                                    <tr>
                                        <td style="width:15%"><b>Personnel</b></td>
                                        <td ><center><b>Division</b></center></td>
                                        <td style="width:10%"><center><b>Designation</b></center></td>
                                        <td><center><b>ORS No.</b></center></td>
                                        <td><center><b>Monthly Rate</b></center></td>
                                        <td><center><b>No of Days in the Month</b></center></td>
                                        <td><center><b>No of Days in the Period</b></center></td>
                                        <td colspan="2"><center><b>Days Without Pay</b></center></td>
                                        <td><center><b>Earned for the Period</b></center></td>
                                        <td><center><b>ITW</b></center></td>
                                        <td><center><b>HDMF</b></center></td>
                                        <td><center><b>PMPC</b></center></td>
                                        <td><center><b>Total Deductions</b></center></td>
                                        <td><center><b>Net</b></center></td>
                                    </tr>
                                    <tr style="font-weight:bold">
                                        <td style="width:15%">GRAND TOTAL</td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="right">'.formatCash($total_salary).'</td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="right">'.formatCash($total_wp).'</td>
                                        <td align="right">'.formatCash($total_earned).'</td>
                                        <td align="right">'.formatCash($total_itw).'</td>
                                        <td align="right">'.formatCash($total_hdmf).'</td>
                                        <td align="right">'.formatCash($total_pmpc).'</td>
                                        <td align="right">'.formatCash($total_deductions).'</td>
                                        <td align="right">'.formatCash($total_net).'</td>
                                    </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size : 9px">
                                    <tr valign="top">
                                        <td style="border:0px solid #FFF">
                                            A. PREPARED BY: <br/><br/><br/><br/>
                                            <b>ROMMEL V. VISPERAS</b><br>Administrative Assistant III<br/><br/><br/><br/>
                                            C. CERTIFIED: Supporting documents complete<br/>and cash available in the amount of ________________________<br/><br/><br/><br/>
                                            <b>ABEGAIL GRACE M. MARALIT</b><br>Accountant III<br/><br/><br/><br/><br/>
                                            OBR No. ________________________<br/>
                                            DATE ________________________<br/>
                                            JEV No. ________________________<br/>
                                            DATE    ________________________<br/>
                                        </td>
                            
                                        <td style="border:0px solid #FFF">
                                            B. CERTIFIED CORRECT: COS positions exist with fixed compensation<br/><br/><br/><br/>
                                            <b>GEORGIA M. LAWAS</b><br>Administrative Officer V-HRMO<br/><br/><br/><br/>
                                            D. Approved for payment:____________________________<br/><br/><br/><br/>
                                            <b>REYNALDO V. EBORA</b><br>Executive Director<br/><br/><br/><br/>
                                            E. CERTIFIED CORRECT Each employee whose name appears above has been paid the<br/>amount indicated opposite on his/her name:<br/><br/><br/><br/>
                                            <b>HEIDELITA A. RAMOS</b><br>Cashier<br/><br/><br/><br/>
                                            
                                        </td>
                            
                                    </tr>
                                    </table>
                                    <div class="page-break"></div>
                                    '.$tbl2.'
                                    <div class="page-break"></div>
                                    <center><h4><b>PAYMENT OF SALARIES OF 184 PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center>
                                    <table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed">
                                    <tr>
                                        <td style="width:15%"><b>Personnel</b></td>
                                        <td ><center><b>Division</b></center></td>
                                        <td style="width:10%"><center><b>Designation</b></center></td>
                                        <td><center><b>ORS No.</b></center></td>
                                        <td><center><b>Monthly Rate</b></center></td>
                                        <td><center><b>No of Days in the Month</b></center></td>
                                        <td><center><b>No of Days in the Period</b></center></td>
                                        <td colspan="2"><center><b>Days Without Pay</b></center></td>
                                        <td><center><b>Earned for the Period</b></center></td>
                                        <td><center><b>ITW</b></center></td>
                                        <td><center><b>HDMF</b></center></td>
                                        <td><center><b>PMPC</b></center></td>
                                        <td><center><b>Total Deductions</b></center></td>
                                        <td><center><b>Net</b></center></td>
                                    </tr>
                                    <tr style="font-weight:bold">
                                        <td style="width:15%">GRAND TOTAL</td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="right">'.formatCash($total_salary2).'</td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="right">'.formatCash($total_wp2).'</td>
                                        <td align="right">'.formatCash($total_earned2).'</td>
                                        <td align="right">'.formatCash($total_itw2).'</td>
                                        <td align="right">'.formatCash($total_hdmf2).'</td>
                                        <td align="right">'.formatCash($total_pmpc2).'</td>
                                        <td align="right">'.formatCash($total_deductions2).'</td>
                                        <td align="right">'.formatCash($total_net2).'</td>
                                    </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size : 9px">
                                    <tr valign="top">
                                        <td style="border:0px solid #FFF">
                                            A. PREPARED BY: <br/><br/><br/><br/>
                                            <b>ROMMEL V. VISPERAS</b><br>Administrative Assistant III<br/><br/><br/><br/>
                                            C. CERTIFIED: Supporting documents complete<br/>and cash available in the amount of ________________________<br/><br/><br/><br/>
                                            <b>ABEGAIL GRACE M. MARALIT</b><br>Accountant III<br/><br/><br/><br/><br/>
                                            OBR No. ________________________<br/>
                                            DATE ________________________<br/>
                                            JEV No. ________________________<br/>
                                            DATE    ________________________<br/>
                                        </td>
                            
                                        <td style="border:0px solid #FFF">
                                            B. CERTIFIED CORRECT: COS positions exist with fixed compensation<br/><br/><br/><br/>
                                            <b>GEORGIA M. LAWAS</b><br>Administrative Officer V-HRMO<br/><br/><br/><br/>
                                            D. Approved for payment:____________________________<br/><br/><br/><br/>
                                            <b>REYNALDO V. EBORA</b><br>Executive Director<br/><br/><br/><br/>
                                            E. CERTIFIED CORRECT Each employee whose name appears above has been paid the<br/>amount indicated opposite on his/her name:<br/><br/><br/><br/>
                                            <b>HEIDELITA A. RAMOS</b><br>Cashier<br/><br/><br/><br/>
                                            
                                        </td>
                            
                                    </tr>
                                    </table>
                                    <div class="page-break"></div>
                                    '.$tbl3.'
                                    <div class="page-break"></div>
                                    <center><h4><b>PAYMENT OF SALARIES OF 184(Coco Levy Fund) PROJECT STAFF<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center>
                                    <table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed">
                                    <tr>
                                        <td style="width:15%"><b>Personnel</b></td>
                                        <td ><center><b>Division</b></center></td>
                                        <td style="width:10%"><center><b>Designation</b></center></td>
                                        <td><center><b>ORS No.</b></center></td>
                                        <td><center><b>Monthly Rate</b></center></td>
                                        <td><center><b>No of Days in the Month</b></center></td>
                                        <td><center><b>No of Days in the Period</b></center></td>
                                        <td colspan="2"><center><b>Days Without Pay</b></center></td>
                                        <td><center><b>Earned for the Period</b></center></td>
                                        <td><center><b>ITW</b></center></td>
                                        <td><center><b>HDMF</b></center></td>
                                        <td><center><b>PMPC</b></center></td>
                                        <td><center><b>Total Deductions</b></center></td>
                                        <td><center><b>Net</b></center></td>
                                    </tr>
                                    <tr style="font-weight:bold">
                                        <td style="width:15%">GRAND TOTAL</td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="right">'.formatCash($total_salary3).'</td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="right">'.formatCash($total_wp3).'</td>
                                        <td align="right">'.formatCash($total_earned3).'</td>
                                        <td align="right">'.formatCash($total_itw3).'</td>
                                        <td align="right">'.formatCash($total_hdmf3).'</td>
                                        <td align="right">'.formatCash($total_pmpc3).'</td>
                                        <td align="right">'.formatCash($total_deductions3).'</td>
                                        <td align="right">'.formatCash($total_net3).'</td>
                                    </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size : 9px">
                                    <tr valign="top">
                                        <td style="border:0px solid #FFF">
                                            A. PREPARED BY: <br/><br/><br/><br/>
                                            <b>ROMMEL V. VISPERAS</b><br>Administrative Assistant III<br/><br/><br/><br/>
                                            C. CERTIFIED: Supporting documents complete<br/>and cash available in the amount of ________________________<br/><br/><br/><br/>
                                            <b>ABEGAIL GRACE M. MARALIT</b><br>Accountant III<br/><br/><br/><br/><br/>
                                            OBR No. ________________________<br/>
                                            DATE ________________________<br/>
                                            JEV No. ________________________<br/>
                                            DATE    ________________________<br/>
                                        </td>
                            
                                        <td style="border:0px solid #FFF">
                                            B. CERTIFIED CORRECT: COS positions exist with fixed compensation<br/><br/><br/><br/>
                                            <b>GEORGIA M. LAWAS</b><br>Administrative Officer V-HRMO<br/><br/><br/><br/>
                                            D. Approved for payment:____________________________<br/><br/><br/><br/>
                                            <b>REYNALDO V. EBORA</b><br>Executive Director<br/><br/><br/><br/>
                                            E. CERTIFIED CORRECT Each employee whose name appears above has been paid the<br/>amount indicated opposite on his/her name:<br/><br/><br/><br/>
                                            <b>HEIDELITA A. RAMOS</b><br>Cashier<br/><br/><br/><br/>
                                            
                                        </td>
                            
                                    </tr>
                                    </table>
                                </body>
                                </html>')
            ->setPaper('legal', 'landscape');
            return $pdf->stream();
        }
        else
        {
            switch($charging)
            {
                case "101":
                    $text = $text101;
                break;
                case "184":
                    $text = $text184;
                break;
                case "184C":
                    $text = $text184C;
                break;
            }
            $folder = "COS-SALARY-".$yr."-".date('m',strtotime($mon2))."_".$period;

            Storage::disk('payroll')->makeDirectory($folder);

            $fsMgr = new FilesystemManager(app());
            // local disk
            $localDisk = $fsMgr->createLocalDriver(['root' => storage_path('app/payroll/'.$folder)]);

            $filename = 'PC-COS'.strtoupper(date('M',strtotime($mon2))).$period.time().'.txt';
            $localDisk->put($filename, $text);
          
            $myFile = storage_path('app/payroll/'.$folder."/".$filename);
            $headers = ['Content-Type: text/plain'];
            $newName = $filename;
              
            return response()->download($myFile, $newName, $headers);

            echo "Your download will start shortly..";
        }

        
    }

    public function printpcocdtrsummary($mon,$yr,$period)
    {

        //PERIOD
        $per = getPeriodCOS($period,$mon,$yr);
        $mon2 = date('F',mktime(0, 0, 0, $mon, 10));

        $row = "";

        foreach(getCOSProcessList($mon,$yr,$period) AS $lists)
        {
            $row .= '<tr>
                <td>'.$lists->lname.", ".$lists->fname." ".substr($lists->mname,0,1).'.</td>
                <td align="center">'.ifNull($lists->absent,true).'</td>
                <td align="center">'.readableTime($lists->late).'</td>
                <td align="center">'.readableTime($lists->undertime).'</td>
                <td align="center">'.readableTime($lists->deficit).'</td>
                <td align="center">'.$lists->nodays.'</td>
                <td align="center">'.$lists->created_at.'</td>
            </tr>';
        }
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - COS DTR SUMMARY</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                    @page {
                                      margin: 10;
                                    }
                                body
                                {
                                    font-family:Helvetica;
                                }
                                td
                                {
                                    border:1px solid #555;
                                    font-size:15px;
                                }
                                .page-break {
                                    page-break-after: always;
                                   }
                            </style>
                            <body>
                            <center><h4><b>COS DTR SUMMARY<br/>for the period '.$mon2.' '.$per.', '.$yr.'</b></h4></center>
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                <tr>
                                    <td style="width: 30%"><b>Name</b></td>
                                    <td style="width: 15%"><center><b># of days Absent</center></b></td>
                                    <td style="width: 15%"><center><b>Late</center></td>
                                    <td style="width: 15%"><center><b>Undertime</center></b></td>
                                    <td style="width: 15%"><center><b>Deficit</center></b></td>
                                    <td style="width: 20%"><center><b>Days Without Pay</b></center></td>
                                    <td style="width: 20%"><center><b>DTR Process</b></center></td>
                                </tr>
                                '.$row.'
                            </table>
                            <br>
                            <br>
                            <br>
                            <br>
                                <br>
                                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size : 9px">
                                <tr valign="top">
                                    <td style="border:0px solid #FFF">
                                        <b>'.strtoupper(Auth::user()->fname.' '.substr(Auth::user()->mname, 0, 1).' '.Auth::user()->lname).'</b>
                                        <br><span style="font-size:12px"><b>Division Marshal</b></span><br/><br/><br/><br/>
                                    </td>
                        
                                    <td style="border:0px solid #FFF">
                                        <b>'.getDirector(Auth::user()->division).'<br/><br/><br/>
                                    </td>
                        
                                </tr>
                                </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function cosreverse()
    {
        if(Auth::user()->usertype == 'Administrator')
        {
            App\Payroll\ProcessCOS::where('id',request()->processid)->delete();

            return redirect('icos/payroll/'.request()->rev_mon.'/'.request()->rev_yr.'/'.request()->rev_period);
        }
        else
        {
            return redirect('/');
        }
    }
    
    public function printpcospayroll()
    {
        $mon = date('F',mktime(0, 0, 0, request()->mon, 10));
        $period_text = $mon.' '.getPeriodCOS(request()->period,request()->mon,request()->yr).' '.request()->yr;

        $staff = App\User::where('id',Auth::user()->id)->first();

        $fullname = $staff['lname'].', '.ucwords(strtolower($staff['fname'].' '.substr($staff['mname'],0,1).'.'));

        $salary = getCOSSalary(Auth::user()->id,'salary','num');

        $processdetail = App\Payroll\ProcessCOS::where('user_id',Auth::user()->id)->where('mon',request()->mon)->where('yr',request()->yr)->where('period',request()->period)->first();

        $withoutpay = ($salary / $processdetail['daysmonth']) * $processdetail['nodays'];

        $earned = ($salary / $processdetail['daysmonth']) * ($processdetail['daysperiod'] - $processdetail['nodays']);

        $itw = $earned * $processdetail['itw'];
        $totaldeduct = $itw + $processdetail['hmdf'] + $processdetail['pmpc'];
        $net = $earned - $totaldeduct;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - COS DTR SUMMARY</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                    @page {
                                      margin: 10;
                                    }
                                body
                                {
                                    font-family:Helvetica;
                                }
                                td
                                {
                                    border:1px solid #555;
                                    font-size:15px;
                                }
                                .page-break {
                                    page-break-after: always;
                                   }
                            </style>
                            <body>
                            <center><h4><b>PAYROLL SUMMARY<br/>for the period '.$period_text.'</b></h4></center>
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                <tr>
                                    <td style="width: 15%;font-size:20px"><b>Name</b></td>
                                    <td style="width: 15%;font-size:20px">'.$fullname.'</td>
                                    <td style="width: 10%;font-size:20px"><b>Salary</b></td>
                                    <td style="width: 10%;font-size:20px" align="right">'.formatCash($salary).'</td>
                                </tr>
                                
                            </table>
                            <br/>
                            <center><h4><b>DTR SUMMARY<br/></h4></center>
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                <tr>
                                    <td style="width: 15%" align="center"><b>Absent (days)</b></td>
                                    <td style="width: 15%" align="center"><b>Late</b></td>
                                    <td style="width: 15%" align="center"><b>Undertime</b></td>
                                    <td style="width: 15%" align="center"><b>Deficit</b></td>
                                    <td style="width: 15%" align="center"><b>Total days without pay</b></td>
                                    <td style="width: 15%" align="center"><b>Total days without pay (Amount)</b></td>
                                </tr>

                                <tr>
                                    <td align="center">'.$processdetail['absent'].'</td>
                                    <td align="center">'.readableTime($processdetail['late']).'</td>
                                    <td align="center">'.readableTime($processdetail['undertime']).'</td>
                                    <td align="center">'.readableTime($processdetail['deficit']).'</td>
                                    <td align="center">'.$processdetail['nodays'].'</td>
                                    <td align="center">'.formatCash($withoutpay).'</td>
                                </tr>
                                
                            </table>
                            <br/>
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                <tr>
                                    <td style="width: 15%" align="center" rowspan="2"><b>Earned for the Period</b></td>
                                    <td style="width: 15%" align="center" colspan="3"><b>Total Deduction</b></td>
                                    <td style="width: 15%" align="center" rowspan="2"><b>Net</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%" align="center"><b>ITW</b></td>
                                    <td style="width: 15%" align="center"><b>HMDF</b></td>
                                    <td style="width: 15%" align="center"><b>PMPC</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 15%" align="center">'.formatCash($earned).'</td>
                                    <td align="center">'.formatCash($itw).'</td>
                                    <td align="center">'.formatCash($processdetail['hmdf']).'</td>
                                    <td align="center">'.formatCash($processdetail['pmpc']).'</td>
                                    <td style="width: 15%;font-size:20px" align="center"><b>'.formatCash($net).'</b></td>
                                </tr>
                                
                            </table>
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }
}



