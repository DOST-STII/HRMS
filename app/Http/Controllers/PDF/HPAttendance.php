<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class HPAttendance extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $mon = date('F',mktime(0, 0, 0, request()->mon1, 10));

        //GET ALL STAFF

        $tr = "";
        foreach (getStaffDivision2(request()->division) as $value) {

            //COUNT NO. LEAVES (WHOLEDAY)
            $leaveWholeDates = "";

            $l1_total = App\Request_leave::where('user_id',$value->id)->whereNull('parent_leave')->whereNotNull('parent_leave_code')->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',request()->year)->get();
            foreach ($l1_total as $k1 => $v1) {
                $leaveWholeDates .= date('d',strtotime($v1->leave_date_from)).", ";
            }

            //SINGLE DATE (WHOLEDAY)
            $l2_total = App\Request_leave::where('user_id',$value->id)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',request()->year)->where('leave_deduction',1)->get();
            foreach ($l2_total as $k2 => $v2) {
                $leaveWholeDates .= date('d',strtotime($v2->leave_date_from)).", ";
            }

            $l_total = count($l1_total) + count($l2_total);


            //SINGLE DATE (HALFDAY)
            $leaveHalfDates = "";
            $l3_total = App\Request_leave::where('user_id',$value->id)->where('parent','YES')->where('leave_deduction','<=',1)->whereNotIn('leave_id',[5,16])->where('leave_action_status','Approved')->whereMonth('leave_date_from',request()->mon1)->whereYear('leave_date_from',request()->year)->where('leave_deduction',0.5)->get();
            foreach ($l3_total as $k3 => $v3) {
                $leaveHalfDates .= date('d',strtotime($v3->leave_date_from)).", ";
            }

            if($leaveWholeDates == "")
            {
                $leaveWholeDates = "00";
            }

            if($leaveHalfDates == "")
            {
                $leaveHalfDates = "00";
            }

            
            $total = $l_total + (count($l3_total) * 0.5);
            $total = $this->formatNull($total);

            $tr .= "<tr>
                        <td>".$value->lname.", ".$value->fname." ".substr($value->mname,0,1)."</td>
                        <td>".$value->username."</td>
                        <td align='center'>".substr($leaveHalfDates,0,-1)."</td>
                        <td align='center'>".substr($leaveWholeDates,0,-1)."</td>
                        <td align='center'>".$total."</td>
                    </tr>";
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - Report</title>
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
                                <center>
                                    <h4 style="font-size:12px">
                                        Republic of the Philippines<br/>
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                        <br>
                                        <br>
                                        <b>ATTENDANCE MONITORING SHEET (Hazard Pay)</b>
                                        <br>
                                        '.$mon.' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="1" style="table-layout: fixed">
                                <tr>
                                  <td align="center" rowspan="2" style="width:10%"><b>EMPLOYEE NAME</b></td>
                                  <td align="center" rowspan="2" style="width:5%"><b>EMPLOYEE CODE</b></td>
                                  <td align="center" colspan="2" ><b>NO. OF LEAVE</b></td>
                                  <td align="center" rowspan="2" style="width:5%"><b>TOTAL NO OF DAYS</b></td>
                                </tr>
                                <tr>
                                  <td align="center" style="width:10%"><b>HALF DAY</b></td>
                                  <td align="center" style="width:10%"><b>WHOLE</b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>Division : '.getDivision(request()->division).'</b></td>
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
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
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
