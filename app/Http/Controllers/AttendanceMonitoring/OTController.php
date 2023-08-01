<?php

namespace App\Http\Controllers\AttendanceMonitoring;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;
use Carbon\Carbon;

class OTController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {   
     	return view('dtr.request-ot');   
    }

    public function send()
    {
        if(request()->request_cto == 'request_ot')
            {
                $this->addOT(date('Y-m-d',strtotime(request()->leave_duration3)));
                return redirect('/');
            }
            else
            {
                if($this->requestCTO(date('Y-m-d',strtotime(request()->leave_duration3))))
                {
                    return redirect('/');
                }
                else
                {
                    return view('error-message')->with('error_message','Not enough leave balance..');
                }
            }
    }

    private function addOT($dt)
    {       
            $duration = explode('-',request()->leave_duration4);

            $from = Carbon::parse($duration[0]);
            $to = Carbon::parse($duration[1]);

            $diff = $from->diffInDays($to);

            $user = App\User::where('id',request()->userid2)->first();

            for ($i=0; $i <= $diff; $i++)
            {
                //GET DURATION
                switch (request()->leave_time) {
                    case 'wholeday':
                            $deduc = 1;
                        break;
                    
                    default:
                            $deduc = 0.5;
                        break;
                }

                if($i == 0)
                    {
                        $dt = date('Y-m-d',strtotime($from));
                    }
                    else
                    {
                        $dt_main = $from->addDays(1);
                        $dt = $dt_main;
                    }
                
                //IF DIRECTOR
                $diretor = 'NO';
                if($user['usertype'] == 'Director')
                    $diretor = 'YES';

                $request = new App\RequestOT;
                $request->userid = $user['id'];
                $request->empcode = $user['username'];
                $request->director = $diretor;
                $request->employee_name = $user['lname'].', '.$user['fname'].' ' .$user['mname'];
                $request->division = $user['division'];
                $request->ot_date = $dt;
                $request->ot_hours = request()->cto_hours;
                $request->ot_deduction = $deduc;
                $request->ot_deduction_time = request()->leave_time;
                $request->ot_purpose= request()->ot_purpose;
                $request->ot_output = request()->ot_output;
                $request->save();

                $tblid = $request->id;

                add_ot_leave($user['id'],$tblid,$dt,"Requested");
            }
        
    }

    private function requestCTO($dt)
    {
        $code = randomCode(15);
         //GET DURATION
         switch (request()->leave_time) {
            case 'wholeday':
                    $deduc = 1;
                break;
            default:
                    $deduc = 0.5;
                break;
        }

        $bal = getLeaves(request()->userid2,5);
        $pending = getPending(5,request()->userid2);
        $projected = $bal - $pending;
        $rem_bal = $projected - $deduc;


        if($rem_bal < 0)
        {
            return false;
        }
        else
        {
             //GET USER INFO
            $user = App\User::where('id',request()->userid2)->first();
            
            //IF DIRECTOR
            $director = 'NO';
            if($user['usertype'] == 'Director')
                $director = 'YES';

            $request = new App\Request_leave;
            // $request->user_id = Auth::user()->id;
            $request->user_id = $user['id'];
            $request->empcode = $user['username'];
            $request->director = $director;
            $request->user_div = $user['division'];
            $request->leave_date_from = $dt;
            $request->leave_date_to = $dt;

            $request->parent = 'YES';
            $request->parent_leave = $code;
            $request->parent_leave_code = $code;
            
            $request->leave_id = 5;
            $request->leave_deduction = $deduc;
            $request->leave_deduction_time = request()->leave_time;
            $request->save();

            $tblid = $request->id;

            add_history_leave($user['id'],5,$tblid,$dt,'Requested');

            return true;
        }
       

    }

    public function pdf()
    {   
        //GET TO DETAILS
        $ot = App\RequestOT::where('id',request()->req_id)->first();

        //CTO
        $cto_yes = "&#9744";
        $cto_no = "&#9745";

        //IF CTO
        if($ot['cto'] == 'YES')
        {
            $cto_yes = "&#9745";
            $cto_no = "&#9744";
        }

        if($ot->ot_status == 'Pending')
        {
            $cto_yes = "&#9744";
            $cto_no = "&#9744";
        }

        //IF DIRECTOR
        $director = 'REYNALDO V. EBORA';
        if($ot['director'] == 'NO')
            $director = getDirector(Auth::user()->division);


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - T.O</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                @page {
                                  size: 21cm 29.7cm;
                                  margin: 20;
                                }
                                body
                                {
                                    font-family: DejaVu Sans;
                                }
                                th,td
                                {
                                 
                                    font-size:11px;
                                }
                            </style>
                            <body>

                            <table cellspacing="0">
                                <tr>
                                    <td style="border: 1px solid #000000;" rowspan=5 height="97" align="center">
                                        <font face="Arial" color="#000000"><br><img src="'.asset('img/stii.png').'" width=81 height=81 hspace=5 vspace=10>
                                        </font>
                                    </td>
                                    <td colspan=5 align="left" style="border-top: 1px solid #000000;" valign=bottom bgcolor="#FFFFFF">
                                        <font face="Arial" color="#000000" size="13px">&nbsp;Republic of the Philippines</font>
                                    </td>
                                    <td style="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center">
                                        <b><font face="Arial" color="#000000">FR- FAD-HR No. 002</font></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" align="left" colspan=5>
                                        <b><font face="Arial" color="#000000" size="13px">&nbsp;SCIENCE AND TECHNOLOGY INFORMATION INSTITUTE</font></b>
                                    </td>
                                    <td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle bgcolor="#FFFFFF">
                                        <i><font face="Arial" size=1 color="#000000">(rev 00 09/04/17)</font></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" align="left" colspan=5 valign=top bgcolor="#FFFFFF">
                                        <font face="Arial" color="#000000" size="13px">&nbsp;STII Building, DOST Complex, Bicutan, Taguig City</font>
                                    </td>
                                    <td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3></td>
                                </tr>   
                            
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=8 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" size=3 color="#000000">OVERTIME REQUEST SLIP</font></b></td>
                                    </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=8 align="center" valign=middle bgcolor="#FFFFFF"><i><font face="Arial" size=1 color="#000000">For Availment of Compensatory Overtime Credits (CSC-DBM Joint Circular No. 2 dated October 24, 2004)</font></i></td>
                                    </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000"  height="21" align="left" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">&nbsp;Division</font></b></td>
                                    <td style="border-bottom: 1px solid #000000; align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"></font></td>
                                    <td style="border-bottom: 1px solid #000000" align="right" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000" style="font-size: 13px">'.$ot['division'].'</font></b></td>
                                    <td style="border-bottom: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">Control No.</font></b></td>
                                    </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-left: 1px solid #000000" height="23" align="t" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">&nbsp;Period Duration</font></b></td>
                                    <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; align="left" valign=middle bgcolor="#FFFFFF"><font face="Arial" color="#000000"></font></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="right" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000" style="font-size: 13px">'.$ot['ot_date'].'</font></b></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 rowspan=2 align="center" valign=middle bgcolor="#FFFFFF"></td>
                                    </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 rowspan=3 height="63" align="left" valign=top bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">&nbsp;Purpose</font></b></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 rowspan=3 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000" style="font-size: 13px">'.$ot['ot_purpose'].'</font></b></td>
                                    </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">Date of Filing</font></b></td>
                                    </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align="left" valign=middle bgcolor="#FFFFFF" sdnum="1033;1033;M/D/YYYY"><b><font face="Arial" color="#000000" style="font-size: 12px">'.$ot['updated_at'].'</font></b></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 rowspan=2 height="34" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">Name/s</font></b></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 rowspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">Specific Work Activity</font></b></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" colspan=3 rowspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">Output/Work to be Accomplished</font></b></td>
                                    
                                <tr>
                                </tr>
                                
                                <tr>
                                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 rowspan=12 height="34" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">'.$ot['employee_name'].'</font></b></td>
                                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 rowspan=12 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">'.$ot['ot_purpose'].'</font></b></td>
                                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" colspan=3 rowspan=12 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">'.$ot['ot_output'].'</font></b></td>
                                </tr>
                              
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            </tr>
                            <tr>
                            <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                            <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        </tr>
                        <tr>
                        <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                        <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                    </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                            
                                
                               
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" colspan=4 rowspan=2 height="34" align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">REQUESTED BY:</font></b></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 rowspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">RECOMMENDING APPROVAL:</font></b></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" colspan=3 rowspan=2 align="center" valign=middle bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">APPROVED:</font></b></td>
                                    </tr>
                                <tr>
                                </tr>
                               
                               
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-left: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-left: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-left: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-left: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                </tr>
                                <tr>
                                    <td style=" border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 height="17" align="center" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000">______________________________________</font></i></b></td>
                                    <td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000">_____________________</font></i></td>
                                    <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=bottom bgcolor="#FFFFFF"><b><u><font face="Arial" color="#000000"><br></font></u></b></td>
                                    </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 height="17" align="center" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"></font></i></td>
                                    <td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000">Division Chief</font></i></td>
                                    <td style="border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000">Chief, FAD</font></b></td>
                                    </tr>
                                
                                <tr>
                                    <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-bottom: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-bottom: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-bottom: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 height="20" align="center" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=3 align="center" valign=top bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    </tr>
                                <tr>
                                    <td style="border-top: 1px solid #000000; border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-top: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-top: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-top: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><i><font face="Arial" color="#000000"><br></font></i></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 rowspan=2 align="left" valign=middle bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000">&nbsp;For Personnel Section ONLY</font></i></b></td>
                                    </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="center" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000">Note:</font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000">Please accomplish two (2) copies</font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000"><br></font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000"><br></font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000">1st copy - Personnel </font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000"><br></font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" rowspan=2 align="left" valign=middle bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000">&nbsp;Received by:</font></i></b></td>
                                    <td style="border-top: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-top: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-top: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" height="17" align="left" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000"><br></font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000">2nd copy - Guard on Duty</font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000"><br></font></i></b></td>
                                    <td align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><b><font face="Arial" color="#000000"><br></font></b></td>
                                    <td style="border-bottom: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-bottom: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                    <td style="border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#FFFFFF"><font face="Arial" color="#000000"><br></font></td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid #000000" border-bottom: 1px solid #000000; height="20" align="left" valign=middle bgcolor="#FFFFFF" rowspan=2 colspan=5><b><i><font face="Arial" color="#000000">&nbsp;*To be filed before the period duration</font></i></b></td>
 
                                    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" rowspan=5 colspan=8 align="left" valign=middle bgcolor="#FFFFFF"><b><i><font face="Arial" color="#000000">&nbsp;Date/Time:</font></i></b></td>
                                   
                                </tr>
                              
                            </table>

                      
                                </td>
                            </tr>
                            </body>
                            </html>')
        ->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function batchPDF()
    {   
        //GET ALL OT
        $mon = request()->ot_batch_mon;
        $yr = request()->ot_batch_year;

        $req = App\RequestOT::where('division',Auth::user()->division)->where('userid',Auth::user()->id)->whereIn('ot_status',['Approved','Pending'])->get();
        // $req = App\RequestOT::get();

        //ROW
        $rows = "";
        foreach ($req as $key => $value) {

            // if($value->ot_date != '2023-05-03')
            // {
                $rows .= "
                    <tr>
                        <td>".$value->employee_name."</td>
                        <td>".$value->ot_purpose."</td>
                        <td>".$value->ot_output."</td>
                        <td align='center'>".$value->ot_date."</td>
                        <td align='center'>".$value->ot_in."</td>
                        <td align='center'>".$value->ot_out."</td>
                        <td align='center'>".countTotalTimeDiff($value->ot_in,$value->ot_out,$value->ot_date)."</td>
                        <td align='center'>".$value->cto."</td>
                        <td></td>
                    </tr>
                    ";
            // }
            
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - O.T</title>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                            </head>
                            <style type="text/css">
                                body
                                {
                                    font-family: DejaVu Sans;
                                }
                                th,td
                                {
                                    border:1px solid #555;
                                    font-size:11px;
                                }
                            </style>
                            <body>

                            <table width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td style="border : 1px solid #FFF;width:20%" align="right">
                                  <img src="'.asset('img/stii.png').'" style="width:100px">
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;" align="center">
                                        Republic of the Philippines<br/>
                                        SCIENCE AND TECHNOLOGY INFORMATION INSTITUTE <br/>
                                        STII Building, DOST Complex, Bicutan, Taguig City
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;width:20%" >

                                  </td>
                                </tr>
                            </table>
                                    <center>
                                    <h4><b>ORDER TO RENDER OVERTIME SERVICES</b></h4>
                                    '.date('F',mktime(0, 0, 0, $mon, 10)).' '.$yr.'
                                    </center>
                            
                                <table width="100%" cellspacing="0" cellpadding="2">
                                    <tr>
                                        <td><b>Name of Staff</b></td>
                                        <td><b>Purpose</b></td>
                                        <td><b>Expected Output</b></td>
                                        <td align="center"><b>Date</b></td>
                                        <td style="width:50px !important" align="center"><b>IN</b></td>
                                        <td style="width:100px !important" align="center"><b>OUT</b></td>
                                        <td style="width:100px !important" align="center"><b>No. of Hours</b></td>
                                        <td align="center"><b>CTO</b></td>
                                        <td align="center"><b>Signature</b></td>
                                    </tr>
                                    '.$rows.'
                                </table>
                                <br>
                                <br>
                                <p>'.getDirector(Auth::user()->division).'</p>
                            </body>
                            </html>')
        ->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function ctotime($id)
    {
        //GET REQUEST DETAIL
        $req = App\RequestOT::where('id',$id)->first();
        $userid = $req['userid'];
        $dt = $req['ot_date'];

        $dtr = App\Employee_dtr::select('fldEmpDTRotIn','fldEmpDTRotOut')->where('user_id',$userid)->where('fldEmpDTRdate',$dt)->first();

        $tm = collect([]);

        $tm->put('otIn', $req['ot_in']);
        $tm->put('otOut', $req['ot_out']);

        // if($dtr['fldEmpDTRotIn'] == null && $dtr['fldEmpDTRotOut'] == null)
        // {
        //     $tm->put('otIn', "17:31:00");
        //     $tm->put('otOut', null);
        // }

        // if($dtr['fldEmpDTRotIn'] != null && $dtr['fldEmpDTRotOut'] != null)
        // {
        //     $tm->put('otIn', $dtr['fldEmpDTRotIn']);
        //     $tm->put('otOut', $dtr['fldEmpDTRotOut']);
        // }

        // if($dtr['fldEmpDTRotIn'] != null && $dtr['fldEmpDTRotOut'] == null)
        // {
        //     $tm->put('otIn', "17:31:00");
        //     $tm->put('otOut', $dtr['fldEmpDTRotIn']);
        // }

        // if($dtr['fldEmpDTRotIn'] == null && $dtr['fldEmpDTRotOut'] != null)
        // {
        //     $tm->put('otIn', "17:31:00");
        //     $tm->put('otOut', $dtr['fldEmpDTRotOut']);
        // }

        return $tm->all();
            
    }
}
