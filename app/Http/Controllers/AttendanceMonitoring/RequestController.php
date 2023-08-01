<?php

namespace App\Http\Controllers\AttendanceMonitoring;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;
use Carbon\Carbon;


class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function dt() 
    // {
    //     if($this->isWeekend("2021-02-11"))
    //     {
    //         return "YES";
    //     }
    //     else
    //     {
    //         return "NO"; 
    //     }
    // }

    // public function isWeekend($dt)
    // {
    //     $dt1 = strtotime($dt);
    //     $dt2 = date("l", $dt1);
    //     $dt3 = strtolower($dt2);
    //     if(($dt3 == "saturday" )|| ($dt3 == "sunday"))
    //         {
    //             return true;
    //         } 
    //     else
    //         {
    //             return false;
    //         }  
    // }

    public function index($type)
    {

      $err_msg = "";
    	switch ($type) {
    		case 'leave':
                $duration = explode(",", request()->Dates);

                if(count($duration) > 0)
                {
                    $deduc = 1;
                }
                else
                {
                    switch (request()->leave_time) {
                        case 'wholeday':
                                $deduc = 1;
                            break;
                        case 'AM':
                                $deduc = 0.5;
                            break;
                        case 'PM':
                                $deduc = 0.5;
                            break;

                    }
                }

                foreach ($duration as $values) {
                    $request = new App\Request_leave;
                    $request->user_id = Auth::user()->id;
                    $request->empcode = Auth::user()->username;
                    $request->user_div = Auth::user()->division;
                    $request->leave_date = $values;
                    $request->leave_id = request()->leave_id[++$loop];
                    $request->leave_action_status = 'Pending';
                    $request->leave_deduction = $deduc;
                    $request->leave_deduction_time = request()->leave_time[++$loop];
                    $request->save();
                }
                
    		break;
    	}
        
     return redirect('/');   
    }

public function pdf()
    {
      //GET REQUEST DETAILS
      $req = App\Request_leave::where('id',request()->req_id)->first();

      //EMPLOYEE DETAILS
      $user = App\User::where('id',$req['user_id'])->first();

      //PLANTILLA INFO
      $plantilla = getPlantillaInfo($user['username']);

      //INCLUSIVE DATES
      if($req['leave_date_from'] == $req['leave_date_to'])
      {
        $inc_dates = date('F d, Y',strtotime($req['leave_date_from']));
      }
      else
      {
        $inc_dates = date('F d, Y',strtotime($req['leave_date_from'])).' - '.date('F d, Y',strtotime($req['leave_date_to']));
      }

      //LEAVE BALANCES
      $leave_vl = 0;
      $leave_sl = 0;
      $leave_vl_deduc = 0;
      $leave_sl_deduc = 0;
      $leave_vl_bal = 0;
      $leave_sl_bal = 0;

      //CHECKBOXES LEAVE TYPE
      $ck_vl = '&#9744';
      $ck_sl = '&#9744';
      $ck_fl = '&#9744';
      $ck_ml = '&#9744';
      $ck_pl = '&#9744';
      $ck_patl = '&#9744';
      $ck_spl = '&#9744';
      $ck_solop = '&#9744';
      $ck_studl = '&#9744';
      $ck_10l = '&#9744';
      $ck_rhl = '&#9744';
      $ck_slb = '&#9744';
      $ck_cl = '&#9744';
      $ck_al = '&#9744';
      $others = '';


      foreach(showLeaves() AS $leaves)
      { 
                      
        $bal = getLeaves($user['id'],$leaves->id);

        if($leaves->id == 1)
        {
          // $bal = $bal + 1.25;
          $leave_vl = $bal;
        }
        elseif($leaves->id == 2)
        {
          // $bal = $bal + 1.25;
          $leave_sl = $bal;
        }

        $pending = getPending($leaves->id);
        $projected = $bal - $pending;

      }

      //CHECKBOXES LEAVE TYPE
      switch($req['leave_id'])
        {
          case 1:
            $ck_vl = '&#9632';
          break;
          case 2:
            $ck_sl = '&#9632';
          break;
          case 3:
            $ck_pl = '&#9632';
          break;
          case 4:
            $ck_solop = '&#9632';
          break;
          case 5:
            $others = 'Compensatory Time-Off';
          break;
          case 6:
            $ck_fl = '&#9632';
          break;
          case 7:
            $ck_ml = '&#9632';
          break;
          case 8:
            $ck_patl = '&#9632';
          break;
          case 9:
            $ck_studl = '&#9632';
          break;
          case 10:
            $ck_rhl = '&#9632';
          break;
          case 11:
            $ck_cl = '&#9632';
          break;
          case 12:
            $ck_slb = '&#9632';
          break;
          case 17:
            $others = 'COVID Treatment Leave';
          break;
          case 19:
            $others = 'Excused Absence';
          break;
        }
      //DETAILS FOR SL
      $vl_select_1 = '&#9744';
      $vl_select_2 = '&#9744';

      //DETAIL SL
      $sl_select_1 = '&#9744';
      $sl_select_2 = '&#9744';

      $sl_select_1_specify = '';
      $sl_select_2_specify = '';

      //GET DEDCUTED
      if($req['leave_id'] == 1)
      {
        $leave_vl_deduc = $req['leave_deduction'];

        if($req['vl_select'] == 'Within the Philippines')
        {
            $vl_select_1 = '&#9632';
        }
        else
        {
            $vl_select_2 = '&#9632';
        }
      }
      elseif($req['leave_id'] == 2)
      {
        $leave_sl_deduc = $req['leave_deduction'];
        
        if($req['sl_select'] == 'Hospital')
        {
            $sl_select_1 = '&#9632';
            $sl_select_1_specify = $req['sl_select_specify'];
        }
        else
        {
            $sl_select_2 = '&#9632';
            $sl_select_2_specify = $req['sl_select_specify'];
        }
       
      }

      $leave_vl_bal = $leave_vl - $leave_vl_deduc;
      $leave_sl_bal = $leave_sl - $leave_sl_deduc;

      $totallv = $req['leave_deduction'];
      
      //LWOP

      $paid = "&emsp;&emsp;";
      $lwop = "&emsp;&emsp;";

      if($leave_vl_bal < 0)
      {
        $totallwop = abs($leave_vl_bal);
        $lwop = " $totallwop ";
        $paid = " $leave_vl ";
      }
        

      if($leave_sl_bal < 0)
      {
        $totallwop = abs($leave_sl_bal);
        $lwop = " $totallwop ";
        $paid = " $leave_sl ";
      }


      //SIGNATORY
      if($totallv > 5 && $totallv < 16)
      {
        //GET CLUSTER
        $signatory = getCluster($user['division']);
      }
      elseif($totallv > 15)
      {
        $signatory = getDirectorNoDesc('O');
      }
      else
      {
        $signatory = getDirectorNoDesc($user['division'],$user['id']);
      }

      if($user['usertype'] == 'Director')
      {
        $signatory = getDirectorNoDesc('O');
      }

      if($user['id'] == 312)
      {
        $signatory = mb_strtoupper("Renato U. Solidum, Jr")." (Secretary, DOST)";
      }

      

      //LEAVE CREDITS
      if($req['leave_id'] == 5)
      {
        //CHECK CTO BAL
        $cto = App\Employee_cto::where('user_id',$user['id'])->orderBy('id','DESC')->first();

        $pending = getPending(5,$user['id']);

        $proj = $cto['cto_bal'] - $req['leave_deduction'];

        $credits =
        '<tr>
          <td align="left" colspan="3" style="vertical-align: top;border-bottom:1px solid #FFF">
          7.A CERTIFICATION OF LEAVE CREDITS
          <br/>
          <center>As of : '.date('M d, Y',strtotime($req['created_at'])).'</center>

          <table width="100%" border="1" cellspacing="0">
          <tr>
            <td></td>
            <td align="center">CTO</td>
          </tr>
          <tr>
            <td>Total Earned</td>
            <td align="center">'.$cto['cto_bal'].'</td>
          </tr>
          <tr>
            <td>Less this Application</td>
            <td align="center">'.$req['leave_deduction'].'</td>
          </tr>
          <tr>
            <td>Balance</td>
            <td align="center">'.$proj.'</td>
          </tr>
          </table>
          </td>

          <td align="left" colspan="2" style="vertical-align: top;border-bottom:1px solid #FFF">
          7.B RECOMMENDATION<br>
            &emsp; &#9744 <small>For Approval:</small><br>
            &emsp; &#9744 <small>For Disapproval due to:<u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u></small>
            &emsp; <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u>
            &emsp; <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u>
          </td>
        </tr>';
      }
      else
      {
        $credits = 
        '<tr>
          <td align="left" colspan="3" style="vertical-align: top;border-bottom:1px solid #FFF">
          7.A CERTIFICATION OF LEAVE CREDITS
          <br/>
          <center>As of : '.date('M d, Y',strtotime($req['created_at'])).'</center>

          <table width="100%" border="1" cellspacing="0">
          <tr>
            <td></td>
            <td align="center">Vacation Leave</td>
            <td align="center">Sick Leave</td>
          </tr>
          <tr>
            <td>Total Earned</td>
            <td align="center">'.$leave_vl.'</td>
            <td align="center">'.$leave_sl.'</td>
          </tr>
          <tr>
            <td>Less this Application</td>
            <td align="center">'.$leave_vl_deduc.'</td>
            <td align="center">'.$leave_sl_deduc.'</td>
          </tr>
          <tr>
            <td>Balance</td>
            <td align="center">'.$leave_vl_bal.'</td>
            <td align="center">'.$leave_sl_bal.'</td>
          </tr>
          </table>
          </td>

          <td align="left" colspan="2" style="vertical-align: top;border-bottom:1px solid #FFF">
          7.B RECOMMENDATION<br>
            &emsp; &#9744 <small>For Approval:</small><br>
            &emsp; &#9744 <small>For Disapproval due to:<u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u></small>
            &emsp; <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u>
            &emsp; <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u>
          </td>
      </tr>';
      }


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - LEAVE APPLICATION</title>
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
                                    border:1px solid #555;
                                    font-size:11px;
                                }
                            </style>
                            <body>

                            <table width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td style="border : 1px solid #FFF;width:20%" align="center">
                                  '.showLogo().'
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:11px;" align="center">
                                        Republic of the Philippines<br/>
                                       SCIENCE AND TECHNOLOGY INFORMATION INSTITUTE<br/>
                                       STII Building, DOST Complex, Bicutan, Taguig City
                                  </td>
                                  <td style="border : 1px solid #FFF;font-size:12px;width:20%" >

                                  </td>
                                </tr>
                            </table>
                                    <center><h5><b>APPLICATION FOR LEAVE</b></h5></center>

                            <table width="100%" cellspacing="0" cellpadding="2" border="1">
                                <tr>
                                  <td style="width:33%">
                                    1. OFFICE/DEPARTMENT
                                  </td>
                                  <td style="border-right : 1px solid #FFF">
                                    2. NAME
                                  </td>
                                  <td align="center" style="border-left : 1px solid #FFF;border-right : 1px solid #FFF">
                                    <b><small>'.$user['lname'].'</small></b>
                                  </td>
                                  <td align="center" style="border-left : 1px solid #FFF;border-right : 1px solid #FFF">
                                    <b><small>'.$user['fname'].'</small></b>
                                  </td>
                                  <td align="center" style="border-left : 1px solid #FFF">
                                    <b><small>'.$user['mname'].' '.$user['exname'].'</small></b>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    3. DATE OF FILLING <br/>'.date('M d, Y',strtotime($req['created_at'])).'
                                  </td>
                                  <td colspan="2">
                                    4. POSITION <br/>
                                  </td>
                                  <td colspan="2">
                                    5. SALARY <br/>
                                    <?php// formatCash($plantilla[plantilla_salary]) ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td align="center" colspan="5">
                                    6.  DETAILS OF APPLICATION
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" colspan="3" style="vertical-align: top;">
                                    6.A  TYPE OF LEAVE TO BE AVAILED OF
                                    <br/>
                                    &emsp; '.$ck_vl.' <small>Vacation Leave <small>(Sec 15, Rule XVII, Omnibus Rules Implimenting E.O No. 292)</small></small><br>
                                    &emsp; '.$ck_fl.' <small>Mandatory/Force Leave <small>(Sec 25, Rule XVII, Omnibus Rules Implimenting E.O No. 292)</small></small><br>
                                    &emsp; '.$ck_sl.' <small>Sick Leave <small>(Sec 43, Rule XVII, Omnibus Rules Implimenting E.O No. 292)</small></small><br>
                                    &emsp; '.$ck_ml.' <small>Maternity Leave<small>(R.A No. 11210/ IRR issued by CSC DOLE and SSS)</small></small><br>
                                    &emsp; '.$ck_patl.' <small>Paternity Leave<small>(R.A No. 8187/ CSC MC No 71, s. 1998 as amended)</small></small><br>
                                    &emsp; '.$ck_pl.' <small>Special Privilege Leave<small>(Sec 21, Rule XVII, Omnibus Rules Implimenting E.O No. 292)</small></small><br>
                                    &emsp; '.$ck_solop.' <small>Solo Parent Leave<small>(RA No. 8972/CSC MC NO. 8, s. 2004)</small></small><br>
                                    &emsp; '.$ck_studl.' <small>Study Leave<small>(Sec 68, Rule XVII, Omnibus Rules Implimenting E.O No. 292)</small></small><br>
                                    &emsp; '.$ck_10l.' <small>10-Day VAWC<small>(RA No. 9262/CSC MC NO. 15, s. 2005)</small></small><br>
                                    &emsp; '.$ck_rhl.' <small>Rehabilitation Priviledge<small>(Sec 55, Rule XVII, Omnibus Rules Implimenting E.O No. 292)</small></small><br>
                                    &emsp; '.$ck_slb.' <small>Special Leave Benefits for Women<small>(RA No. 9710/CSC MC NO. 25, s. 2010)</small></small><br>
                                    &emsp; '.$ck_cl.' <small>Special Emergency(Calamity) Leave<small>(CSC MC NO. 2, s. 2012, as amended)</small></small><br>
                                    &emsp; '.$ck_al.' <small>Adoption Leave<small>(RA No. 8552)</small></small><br>
                                    <br>
                                    &emsp;<i>Others</i><br/>
                                    &emsp;&emsp;<small>'.$others.'</small>
                                    <br>
                                  </td>
                                  <td align="left" colspan="2" style="vertical-align: top;">
                                    6.B  DETAILS OF LEAVE
                                    <br/>
                                    <i>In case of Vacation/Special Privilege Leave:</i><br>
                                    &emsp; '.$vl_select_1.' <small>Within the Phillipines:</small><br>
                                    &emsp; '.$vl_select_2.' <small>Abroad (Specify): </small><br>
                                    <br/>
                                    <i>In case of Sick Leave:</i>
                                    <br/>
                                    &emsp; '.$sl_select_1.' <small>In Hospital (Specify Illness): '.$sl_select_1_specify.'</small><br>
                                    &emsp; '.$sl_select_2.' <small>Out-Patient (Specify Illness): '.$sl_select_2_specify.'</small><br>
                                    <br>
                                    <i>In case of Special Leave Benefits for Women:</i><br>
                                    &emsp; <small>Specify Illness: <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u></small>
                                    &emsp; <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u>
                                    <br>
                                    <i>In case of Study Leave:</i><br>
                                    &emsp; &#9744 <small>Completion of Master`s Degree:</small><br>
                                    &emsp; &#9744 <small>BAR/Board Exam Review: </small><br>
                                    <br>
                                    <i>Other purpose:</i><br>
                                    &emsp; &#9744 <small>Monetization of Leave Credits:</small><br>
                                    &emsp; &#9744 <small>Terminal Leave: </small><br>
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" colspan="3" style="vertical-align: top;">
                                  6.C NUMBER OF WORKING DAYS APPLIED FOR : '.$req['leave_deduction'].'

                                  <br>
                                  <br>
                                  &emsp; INCLUSIVE DATES
                                  <br>&emsp;&emsp;'.$inc_dates.'
                                  </td>

                                  <td align="left" colspan="2" style="vertical-align: top;">
                                  6.D COMMUTATION<br>
                                    &emsp; &#9744 <small>Not Requested</small><br>
                                    &emsp; &#9744 <small>Requested</small><br>
                                    <br>
                                    <br>
                                    <center>
                                    <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u>
                                    (Signature of Applicant)
                                    </center>
                                  </td>
                                </tr>
                                '.$credits.'
                                <tr>
                                  <td align="left" colspan="3" style="vertical-align: top;border-top:1px solid #FFF">
                                  <br>
                                  <br>
                                    <center>
                                    <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u><br/>
                                    (Authorized Officer)
                                    </center>   
                                    <br>
                                  </td>

                                  <td align="left" colspan="2" style="vertical-align: top;border-top:1px solid #FFF">
                                    <center>
                                    <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u><br/>
                                    (Authorized Officer)
                                    </center>
                                  </td>
                                </tr>
                                <tr>
                                  <td align="left" colspan="3" style="vertical-align: top;border-right:1px solid #FFF;border-bottom:1px solid #FFF">
                                  7.C APPROVED FOR<br>
                                  &emsp; <u>'.$paid.'</u> days with pay<br/>
                                  &emsp; <u>'.$lwop.'</u> days without pay<br/>
                                  &emsp; <u>&emsp;&emsp;</u> others (specify)<br/>
                                  </td>

                                  <td align="left" colspan="2" style="vertical-align: top;border-left:1px solid #FFF;border-bottom:1px solid #FFF">
                                  7.D DISAPPOVED DUE TO <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u></small>
                                  &emsp; <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u>
                                  &emsp; <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<br/></u>
                                  </td>
                                </tr>
                                <tr>
                                <td align="left" colspan="5" style="vertical-align: top;border-top:1px solid #FFF">
                                    <center>
                                    <u>'.$signatory.'</u><br/>
                                    (Authorized Officer)
                                    </center>
                                    </center>
                                  </td>
                                </tr>

                            </table>
                            </body>
                            </html>')
        ->setPaper('a4', 'portrait');
        return $pdf->stream();
    }


public function wfh()
  {
    //GET REQUEST DETAILS
    $req = App\Request_leave::where('id',request()->req_id)->first();

    //EMPLOYEE DETAILS
    $user = App\View_user::where('id',$req['user_id'])->first();

    //PLANTILLA INFO
    $plantilla = getPlantillaInfo($user['username']);

    //APPROVED
    $wfh_approved = '&#9744';
    $wfh_disapproved = '&#9744';

    //INCLUSIVE DATES
    if($req['leave_date_from'] == $req['leave_date_to'])
      {
        $inc_dates = date('F d, Y',strtotime($req['leave_date_from']));
      }
      else
      {
        $inc_dates = date('F d, Y',strtotime($req['leave_date_from'])).' - '.date('F d, Y',strtotime($req['leave_date_to']));
      }

    if($user['usertype'] == 'Director')
      {
        $signatory = getDirectorNoDesc('O');
      }
      else
      {
        $signatory = getDirectorNoDesc($user['division']);
      }

    //CHECK NUMBER OF DAYS
    if($req['leave_deduction'] > 5 && $req['leave_deduction'] <= 14)
      {
        $signatory = getCluster($user['division']);
      }
      elseif($req['leave_deduction'] >= 15)
      {
        $signatory = getDirectorNoDesc('O');
      }
      else
      {
        $signatory = getDirectorNoDesc($user['division'],$user['id']);
      }

    $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<!DOCTYPE html>
                            <html>
                            <head>
                              <title>HRMIS - WFH</title>
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
                                    border:1px solid #FFF;
                                    font-size:11px;
                                }
                                .input
                                {
                                  text-align:center;
                                }
                            </style>
                            <body>

                            <table width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td style="border : 1px solid #FFF;width:20%" align="right">
                                  <img src="'.asset('img/DOST.png').'" style="width:70px">
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
                                    <center><h4><b>WORK FROM HOME APPLICATION</b></h4></center>

                            <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                 <tr>
                                  <td align="center" style="border : 1px solid #FFF">
                                  <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                   <tr>
                                    <td class="input">'.$user['division_acro'].'</td>
                                    <td>&nbsp;</td>
                                    <td class="input">'.$user['lname'].'</td>
                                    <td>&nbsp;</td>
                                    <td class="input">'.$user['fname'].'</td>
                                    <td>&nbsp;</td>
                                    <td class="input">'.$user['mname'].' '.$user['exname'].'</td>
                                   </tr>
                                   <tr>
                                    <td class="input"><>DIVISION</td>
                                    <td>&nbsp;</td>
                                    <td class="input">NAME (Lastname)</td>
                                    <td>&nbsp;</td>
                                    <td class="input">(Firstname)</td>
                                    <td>&nbsp;</td>
                                    <td class="input">(M.I.)</td>
                                   </tr>
                                   <tr>
                                    <td class="input">'.date('F d, Y',strtotime($req['created_at'])).'</td>
                                    <td>&nbsp;</td>
                                    <td class="input" colspan="5">'.$plantilla['position_desc'].'</td>
                                   </tr>
                                   <tr>
                                    <td class="input">DATE OF FILING</td>
                                    <td>&nbsp;</td>
                                    <td class="input" colspan="5">POSITION</td>
                                   </tr>
                                  </table>
                                  </td>
                                 </tr>
                                 <tr>
                                  <td align="center" class="">DETAILS OF APPLICATION <hr/></td>
                                 </tr>
                                 <tr>
                                  <td align="center">
                                    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                      <tr>
                                      <td>Number of Days Applied For:</td>
                                      <td>&nbsp;</td>
                                        <td>Reason</td>
                                    </tr>
                                    <tr>
                                      <td>'.$req['leave_deduction'].'</td>
                                      <td>&nbsp;</td>
                                      <td rowspan="3"><p>'.$req['wfh_reason'].'</p></td>
                                    </tr>
                                    <tr>
                                      <td>Inclusive Dates:</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                      <tr>
                                      <td>'.$inc_dates.'</td>
                                      <td>&nbsp;</td>
                                    </tr>
                                      <tr>
                                      <td colspan="3">Expected Output:</td>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3"><p>'.$req['wfh_output'].'</p></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td id="signatory_input"><font style="text-decoration:underline;">'.strtoupper($user['fname'].' '.$user['mname'].' '.$user['lname'].' '.$user['exname']).'</font></td>
                                    </tr>
                                    <tr>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td id="signatory_label">Signature of Applicant</td>
                                    </tr>
                                    </table>
                                  </td>
                                 </tr>
                                 <tr><td><hr/></td></tr>
                                 <tr>
                                   <td align="center">
                                     <table width="100%" cellspacing="0" cellpadding="2" border="0">
                                     <tr>
                                       <td align="left" class="label">
                                        '.$wfh_approved.' &nbsp;Approval<br />
                                        '.$wfh_disapproved.' &nbsp;Disapproval due to: 
                                       </td>
                                         <td>&nbsp;</td>
                                       <td>&nbsp;</td>
                                     </tr>
                                       <tr>
                                         <td class="input" colspan="3">&nbsp;</td>
                                       </tr>
                                     <tr>
                                       <td id="signatory_input" colspan="3" align="center"><font style="text-decoration:underline;">'.$signatory.'</font></td>
                                       </tr>
                                       <tr>
                                         <td id="signatory_label" colspan="3" align="center">AUTHORIZED OFFICIAL</td>
                                       </tr>
                                     </table>
                                   </td>
                                 </tr>
                                </table>

                            </html>')
        ->setPaper('a4', 'portrait');
        return $pdf->stream();
  }

  public function json($type,$id)
  {
    switch ($type) {
      case 'to':
          $req = App\RequestTO::where('id',$id)->first();
        break;
    }

    return json_encode($req);
  }

  public function staffAllRequest()
  {
    return view('dtr.director.staff-all-request');
  }
}
