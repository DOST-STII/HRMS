<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class SALAAttendance extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $mon = date('F',mktime(0, 0, 0, request()->mon1, 10));

        $total = 0;

        $tr = "";
        foreach (getStaffDivisionMC(request()->division) as $value) {

            //ON-TRIP
            // $perdiemYesDates = "";
            // $perdiemYes = $this->getPerDiem($value->id,'YES',request()->mon1,request()->year);
            // foreach ($perdiemYes as $key => $values) {
            //     $perdiemYesDates .= date('d',strtotime($values->to_date)).",";
            // }

           
            // $perdiemNo = $this->getPerDiem($value->id,'NO',request()->mon1,request()->year);
            // foreach ($perdiemNo as $key => $values) {
            //     $perdiemNoDates .= date('d',strtotime($values->to_date)).",";
            // }

            // //LEAVES
            // $leaveWholeDates = "";
            // $leaveWhole = getLeave($value->id,1,request()->mon1,request()->year);
            // foreach ($leaveWhole as $key => $values) {
            //     $leaveWholeDates .= date('d',strtotime($values->leave_date_from)).",";
            // }

            
            // $leaveHalf = getLeave($value->id,0.5,request()->mon1,request()->year);
            // foreach ($leaveHalf as $key => $values) {
            //     $leaveHalfDates .= date('d',strtotime($values->leave_date_from)).",";
            // }

            //COUNT NO. LEAVES (WHOLEDAY)
            $leaveWholeDates = "";
            $l1_total2 = 0;

            $l1_total = App\Request_leave::where('user_id',$value->id)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',request()->year)->get();
            foreach ($l1_total as $k1 => $v1) {
                if(!checkIfWeekend($v1->leave_date_from))
                {
                    if($v1->leave_action_status == 'Approved')
                    {   
                        $l1_total2++;
                        $leaveWholeDates .= date('d',strtotime($v1->leave_date_from)).", ";
                    }
                }
            }

            //SINGLE DATE (WHOLEDAY)
            $l2_total = App\Request_leave::where('user_id',$value->id)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',request()->year)->where('leave_deduction',1)->get();
            foreach ($l2_total as $k2 => $v2) {
                if(!checkIfWeekend($v2->leave_date_from))
                {
                    if($v2->leave_action_status == 'Approved')
                    { 
                        $l1_total2++;
                        $leaveWholeDates .= date('d',strtotime($v2->leave_date_from)).", ";
                    }
                }
            }

            $l_total = count($l1_total) + count($l2_total);


            //SINGLE DATE (HALFDAY)
            $leaveHalfDates = "";
            $l3_total = App\Request_leave::where('user_id',$value->id)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16,19])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',request()->year)->where('leave_deduction',0.5)->get();
            
            foreach ($l3_total as $k3 => $v3) {
                if(!checkIfWeekend($v3->leave_date_from))
                {
                    
                    $leaveHalfDates .= date('d',strtotime($v3->leave_date_from)).", ";
                }
            }


            //TARDY
            $tardy_total = 0;
            $tardy_total_half = 0;
            $tardy = App\Employee_tardy::where('user_id',$value->id)->whereMonth('fldEmpDTRdate',request()->mon1)->whereYear('fldEmpDTRdate',request()->year)->get();
            
            foreach ($tardy as $trk => $trs) {
                $tardy_total += $trs->total_day;
                if($trs->total_day == 1)
                {
                    $leaveWholeDates .= date('d',strtotime($trs->fldEmpDTRdate)).", ";
                }
                else
                {
                    $leaveHalfDates .= date('d',strtotime($trs->fldEmpDTRdate)).", ";
                }
            }



            //TO PER DIEM YES MULTIPLE
            $perdiemYesDates = "";
            $l4_total = App\RequestTO::where('userid',$value->id)->whereNull('parent')->whereNotNull('parent_to_code')->where('to_perdiem','YES')->where('to_status','Approved')->whereMonth('to_date_from',request()->mon1)->whereYear('to_date_from',request()->year)->get();

            $t_to_y_0 = 0;
            foreach ($l4_total as $k4 => $v4) {
                if(!checkIfWeekend($v4->to_date_from))
                {
                    if(!checkIfHoliday($v4->to_date_from))
                    {  
                        //$t_to_y_0 += $v4['to_total_day'];
                        $t_to_y_0 += 1;
                        $perdiemYesDates .= date('d',strtotime($v4->to_date_from)).", ";
                    }
                    
                }
            }


            //TO PER DIEM YES SINGLE DATE
            $l5_total = App\RequestTO::where('userid',$value->id)->where('parent','YES')->whereIn('to_total_day',[1.0,0.5])->where('to_perdiem','YES')->where('to_status','Approved')->whereMonth('to_date_from',request()->mon1)->whereYear('to_date_from',request()->year)->get();

            $t_to_y_1 = 0;
            foreach ($l5_total as $k5 => $v5) {
                if(!checkIfWeekend($v5->to_date_from))
                {
                    if(!checkIfHoliday($v5->to_date_from))
                    {
                        //$t_to_y_1 += $v5['to_total_day'];
                        $t_to_y_0 += 1;
                        $perdiemYesDates .= date('d',strtotime($v5->to_date_from)).", ";
                    }
                    
                }
            }


            //TO PER DIEM YES MULTIPLE
            $perdiemNoDates = "";
            $l6_total = App\RequestTO::where('userid',$value->id)->whereNull('parent')->whereNotNull('parent_to_code')->where('to_perdiem','NO')->where('to_status','Approved')->whereMonth('to_date_from',request()->mon1)->whereYear('to_date_from',request()->year)->get();

            foreach ($l6_total as $k6 => $v6) {
                if(!checkIfWeekend($v6->to_date_from))
                {
                    if(!checkIfHoliday($v6->to_date_from))
                        $perdiemNoDates .= date('d',strtotime($v6->to_date_from)).", ";
                }
            }


            //TO PER DIEM YES SINGLE DATE
            $l7_total = App\RequestTO::where('userid',$value->id)->where('parent','YES')->whereIn('to_total_day',[1.0,0.5])->where('to_perdiem','NO')->where('to_status','Approved')->whereMonth('to_date_from',request()->mon1)->whereYear('to_date_from',request()->year)->get();
            foreach ($l7_total as $k7 => $v7) {
                if(!checkIfWeekend($v7->to_date_from))
                {
                    if(!checkIfHoliday($v7->to_date_from))
                        $perdiemNoDates .= date('d',strtotime($v7->to_date_from)).", ";
                }
            }
            

            //TARDY



            if($leaveWholeDates == "")
            {
                $leaveWholeDates = "00";
            }

            if($leaveHalfDates == "")
            {
                $leaveHalfDates = "00";
            }

            if($perdiemYesDates == "")
            {
                $perdiemYesDates = "00";
            }

            if($perdiemNoDates == "")
            {
                $perdiemNoDates = "00";
            }
            //SORTING VALUES FROM TEXT TO ARRAY TO TEXT
            $leaveHalfDatesArr = substr($leaveHalfDates,0,-2);
            $leaveHalfDatesArr = explode(',',$leaveHalfDatesArr);
            asort($leaveHalfDatesArr);
            $leaveHalfDatesArr = implode(',',$leaveHalfDatesArr);

            $leaveWholeDatesArr = substr($leaveWholeDates,0,-2);
            $leaveWholeDatesArr = explode(',',$leaveWholeDatesArr);
            asort($leaveWholeDatesArr);
            $leaveWholeDatesArr = implode(',',$leaveWholeDatesArr);

            $perdiemYesDatesArr = substr($perdiemYesDates,0,-2);
            $perdiemYesDatesArr = explode(',',$perdiemYesDatesArr);
            asort($perdiemYesDatesArr);
            $perdiemYesDatesArr = implode(',',$perdiemYesDatesArr);

            $perdiemNoDatesArr = substr($perdiemNoDates,0,-2);
            $perdiemNoDatesArr = explode(',',$perdiemNoDatesArr);
            asort($perdiemNoDatesArr);
            $perdiemNoDatesArr = implode(',',$perdiemNoDatesArr);

            $total = ($l1_total2 + $tardy_total) + (count($l3_total) * 0.5) + $t_to_y_0 + $t_to_y_1;
            $total = $this->formatNull($total);

            //CHECK IF DTR IS PROCESS
            $checkmon = request()->mon1;
            if($checkmon == 1)
            {
                $checkmon = 12;
                $checkyr = request()->year - 1;
            }
            else
            {
                $checkmon = request()->mon1 + 1;
                $checkyr = request()->year;
            }

            $proc = App\Payroll\MC::where('userid',$value->id)->where('payroll_mon',$checkmon)->where('payroll_yr',$checkyr)->count();
            $error = "";
            if($proc == 0)
            {
                $error = "<br><span style='color:red'>PENDING DTR PROCESS</span>";
            }

            $tr .= "<tr>
                        <td>".$value->lname.", ".$value->fname." ".substr($value->mname,0,1).''.$error."</td>
                        <td>".$value->username."</td>
                        <td align='center'>".$leaveHalfDatesArr ."</td>
                        <td align='center'>".$leaveWholeDatesArr."</td>
                        <td align='center'>".$perdiemYesDatesArr."</td>
                        <td align='center'>".$perdiemNoDatesArr."</td>
                        <td align='center'>".$total."</td>
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
                               @page { margin: 50px; }
                                body
                                {
                                    font-family:Helvetica;
                                    margin: 0px; 
                                }
                                th,td
                                {
                                    border:1px solid #555;
                                    font-size:13px;
                                }
                            </style>
                            <body>
                                
                                <center>
                                    <h4 style="font-size:12px">
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                        <br>
                                        <br>
                                        <b>ATTENDANCE MONITORING SHEET (Subsistence Allowance)</b>
                                        <br>
                                        '.$mon.' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="5" style="table-layout: fixed" page-break-inside:auto;>
                                <thead>
                                <tr>
                                  <td align="center" rowspan="2" style="width:10%"><b>EMPLOYEE NAME</b></td>
                                  <td align="center" rowspan="2" style="width:5%"><b>EMPLOYEE CODE</b></td>
                                  <td align="center" colspan="2"><b>NO. OF LEAVE</b></td>
                                  <td align="center" colspan="2"><b>ON TRIP</b></td>
                                  <td align="center" rowspan="2" style="width:5%"><b>TOTAL NO OF DAYS</b></td>
                                </tr>
                                <tr>
                                  <td align="center" style="width:10%"><b>HALF DAY</b></td>
                                  <td align="center" style="width:10%"><b>WHOLE</b></td>
                                  <td align="center" style="width:10%"><b>WILL CLAIM PER DIEM ON THESE DATES</b></td>
                                  <td align="center" style="width:10%"><b>WILL NOT CLAIM PER DIEM ON THESE DATES</b></td>
                                </tr>
                                </thead>
                                <tr>
                                    <td colspan="7"><b>Division : '.getDivision(request()->division).'</b></td>
                                </tr>
                                '.$tr.'
                                </table>
                                <br>
                                <br>
                                <table width="100%" cellspacing="0" cellpadding="5" style="font-size:12px!important">
                                <tr>
                                  <td style="width:50%;border: 1px solid #FFF">
                                    Prepared by : <br/><br/><br/>
                                    <b>'.mb_strtoupper(Auth::user()->fname." ".substr(Auth::user()->mname,0,1).". ".Auth::user()->lname).'<br><small><b>Division Marshal</b></small>
                                  </td>
                                  <td style="border: 1px solid #FFF">
                                    Verified Correct : <br/><br/><br/>
                                    <b>'.getDirector(request()->division).'</b>
                                  </td>
                                </tr>
                                </table>
                                <p align="right" border="0" style="font-size:8px">Date Printed : '.Carbon::now().'</p>
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    private function getPerDiem($userid,$claim,$mon,$yr)
    {
        $collection = collect(App\RequestTO::where('userid',$userid)->where('to_perdiem',$claim)->whereMonth('to_date',$mon)->whereYear('to_date',$yr)->where('to_status','!=','Pending')->get());
        
        // $dates = "";
        // foreach ($collection->all() as $key => $value) {
        //         $dates .= date('d',strtotime($value->to_date)).",";
        // }
        return $collection->all();
    }

    private function formatNull($val)
    {
        if($val == null || $val == 0)
        {
            return 0;
        }
        else
        {
            return $val;
        }
    }
}
