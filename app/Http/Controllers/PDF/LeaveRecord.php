<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App;
use Carbon\Carbon;
use Auth;

class LeaveRecord extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $mon = date('F',mktime(0, 0, 0, request()->mon2, 10));

        //GET ALL STAFF

        $tr = "";

        //GET MONTHS
        if(request()->mon1 == request()->mon2)
        {
          if(checkIfProcess(request()->employee,request()->mon1,request()->year))
          {

            //GET PREVIOUS MONTH
            // $prv_mon = request()->mon1 - 1;
            // $mon = $prv_mon;
            // $yr = request()->year;

            // if($prv_mon == 0)
            // {
            //   $mon = 12;
            //   $yr = request()->year - 1;
            // }

            foreach (getSummary(request()->employee,request()->mon1 ,request()->year) as $key => $value)
            {
              $tr .= "<tr> 
                          <td align='center'><b>".strtoupper(date('F',mktime(0, 0, 0,request()->mon1, 10)))."</b></td>
                          <td>Beginning Balance ".request()->employee."</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align='center'>". $value->vl_bal."</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td align='center'>". $value->sl_bal."</td>
                          <td></td>
                        </tr>";
            }


            foreach (getSummary(request()->employee,request()->mon1,request()->year) as $key => $value)
            {
              //GET LEAVES
                foreach (getLeaveDetails2(request()->mon1,request()->year,request()->employee) as $ky => $val) {

                  $dur_to = date('d',strtotime($val->leave_date_to));
                  $dur_from = date('d',strtotime($val->leave_date_from));
                  if($dur_to != null)
                  {
                    if($dur_from != $dur_to)
                        $dur = $dur_from."-".$dur_to;
                      else
                        $dur = $dur_from;
                  }


                  if($val->leave_id == 1 || $val->leave_id == 6)
                  {
                    

                    $tr .= "<tr> 
                          <td></td>
                          <td>".$val->leave_deduction." (".getLVDesc($val->leave_id,'leave_acro').") ".$dur."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'>".$val->leave_deduction."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                        </tr>";
                  }
                  elseif ($val->leave_id == 2)
                  {
                    $tr .= "<tr> 
                          <td></td>
                          <td>".$val->leave_deduction." (".getLVDesc($val->leave_id,'leave_acro').") ".$dur."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'>".$val->leave_deduction."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                        </tr>";
                  }
                  else
                  {
                    $tr .= "<tr> 
                          <td></td>
                          <td>".$val->leave_deduction." (".getLVDesc($val->leave_id,'leave_acro').") ".$dur."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                        </tr>";
                  }
                  
                }

                $vl_end = $value->vl_bal;
                $sl_end = $value->sl_bal;

                $tr .= "<tr> 
                          <td></td>
                          <td>Ending Balance</td>
                          <td align='center'>1.25</td>
                          <td align='center'>".$value->vl_totalunderlatededuc."</td>
                          <td align='center'>".$value->vl_leave."</td>
                          <td align='center'>".$vl_end."</td>
                          <td align='center'></td>
                          <td align='center'>".$value->vl_lwop."</td>
                          <td align='center'></td>
                          <td align='center'>1.25</td>
                          <td align='center'>".$value->sl_leave."</td>
                          <td align='center'>".$sl_end."</td>
                          <td align='center'>".$value->sl_lwop."</td>
                        </tr>";
                }
            
          }
        }
        else
        {
          $begin_vl = 0;
          $begin_sl = 0;
          $ctrlv = 0;
          for ($i=request()->mon1; $i <= request()->mon2; ++$i) {
              // if(checkIfProcess(request()->employee,$i,request()->year))
                $prv_mon = $i - 1;
                $mon = $prv_mon;
                $yr = request()->year;

                $vl_total = 0;
                $sl_total = 0;

                $vl_total_half = 0;
                $sl_total_half = 0;

                if($prv_mon <= 0)
                {
                  $mon = 12;
                  $yr = request()->year - 1;
                }


                foreach (getSummary(request()->employee,$mon ,$yr) as $key => $value)
                {
                  if($ctrlv == 0)
                          {
                            $begin_vl = $value->vl_bal;
                            $begin_sl = $value->sl_bal;
                            $ctrlv++;
                          }

                  $tr .= "<tr style='background-color: #EEE'> 
                              <td align='center'><b>".strtoupper(date('F',mktime(0, 0, 0,$i, 10)))."</b></td>
                              <td>Beginning Balance</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td align='center'>". $value->vl_bal."</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td align='center'>". $begin_sl."</td>
                              <td></td>
                            </tr>";
                          
                            

                }

                foreach (getLeave2(request()->employee,$i,request()->year) as $ky => $val) {

                  $dur_to = date('d',strtotime($val->leave_date_to));
                  $dur_from = date('d',strtotime($val->leave_date_from));
                  if($dur_to != null)
                  {
                    if($dur_from != $dur_to)
                        $dur = $dur_from."-".$dur_to;
                      else
                        $dur = $dur_from;
                  }


                  if($val->leave_id == 1 || $val->leave_id == 6)
                  {
                    
                    $vl_desc = $val->leave_deduction." (".getLVDesc($val->leave_id,'leave_acro').") ".$dur;

                    if($val->leave_id == 1)
                    {
                      $vl_total += $val->leave_deduction;
                    }

                    if($val->leave_id == 1 && $val->leave_deduction == 0.5)
                    {
                      $vl_total_half += $val->leave_deduction;
                    }

                    
                    if($val->leave_id == 6 && $val->leave_action_status == 'Approved' )
                    {
                      $vl_total += $val->leave_deduction;
                    }

                    if($val->leave_id == 6 && $val->leave_action_status == 'Approved' && $val->leave_deduction == 0.5)
                    {
                      $vl_total_half += $val->leave_deduction;
                    }

                    if($val->leave_id == 6 && $val->leave_action_status == 'Disapproved')
                    {
                      $vl_desc = $val->leave_deduction." (".getLVDesc($val->leave_id,'leave_acro')." D) ".$dur;
                    }


                    $tr .= "<tr> 
                          <td></td>
                          <td>".$vl_desc."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'>".$val->leave_deduction."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                        </tr>";
                  }
                  elseif ($val->leave_id == 2)
                  {

                    if($val->leave_deduction == 0.5)
                    {
                      $sl_total_half += $val->leave_deduction;
                    }

                    $sl_total += $val->leave_deduction;
                    
                    $tr .= "<tr> 
                          <td></td>
                          <td>".$val->leave_deduction." (".getLVDesc($val->leave_id,'leave_acro').") ".$dur."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'>".$val->leave_deduction."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                        </tr>";
                  }
                  else
                  {
                    if($val->leave_id != 2)
                    $tr .= "<tr> 
                          <td></td>
                          <td>".$val->leave_deduction." (".getLVDesc($val->leave_id,'leave_acro').") ".$dur."</td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                          <td align='center'></td>
                        </tr>";
                  }
                  
                }

                if($prv_mon >= 0)
                {
                      foreach (getSummary(request()->employee,$i,request()->year) as $key => $value)
                      {

                          // $vl_end = $value->vl_bal - $vl_total;
                          // $sl_end = $value->sl_bal - $sl_total;

                          // $total_tardy = null;
                          // $tardyh = null;
                          // $tardym = null;
                          // if($value->vl_tardy != null)
                          // {
                          //   $tardy = explode('-',$value->vl_tardy."-");
                          //   $tardyh = $tardy[0];
                          //   $tardym = $tardy[1];
                          //   $tardyh = (int)$tardyh / 8;
                          //   $tardym = (int)$tardym / 480;
                          //   $total_tardy = $tardyh + $tardym;
                          // }

                          //$vl_end = $value->vl_bal - $vl_total) + 1.25;
                          $vl_end = $value->vl_bal;
                          $sl_end = ($begin_sl + 1.25) - $sl_total;
                          $begin_sl = $sl_end;

                          
                          


                          $tr .= "<tr style='background-color: #EEE'> 
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td align='center'>1.25</td>
                                    <td align='center'>".$value->vl_tardy."</td>
                                    <td align='center'>".$vl_total."</td>
                                    <td align='center'>".$vl_end."</td>
                                    <td align='center'></td>
                                    <td align='center'>".$value->vl_lwop."</td>
                                    <td align='center'></td>
                                    <td align='center'>1.25</td>
                                    <td align='center'>".$sl_total."</td>
                                    <td align='center'>".$sl_end."</td>
                                    <td align='center'>".$value->sl_lwop."</td>
                                  </tr>";
                          }
                }
                
          }
        }

        $staffinfo = getStaffAllInfo(request()->employee);
        $fullname = $staffinfo['lname'].', '.$staffinfo['fname'].' '.$staffinfo['mname']. ' '.$staffinfo['exname'];
        $division = getDivision($staffinfo['division']);

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
                               
                                <center>
                                    <h4 style="font-size:15px">
                                        PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
                                        RESEARCH AND DEVELOPMENT<br/>
                                        Los Baños, Laguna
                                        <br>
                                        <br>
                                        <b>LEAVE RECORD</b>
                                        <br>
                                        As of '.date('F',mktime(0, 0, 0,request()->mon1, 10)).' '.request()->year.'
                                    </h4>
                                </center>
                                <table width="100%" cellspacing="0" cellpadding="5">
                                <tr>
                                  <td align="left" style="border-right:1px solid #FFF">
                                    Employee : '.$fullname.'
                                    <br>Division : '.$division.'
                                    <br/>First Day of Service: '.$staffinfo['fldservice'].'
                                  </td>
                                  <td align="right" colspan="6" style="border-left:1px solid #FFF">
                                    <p align="right" border="0" style="font-size:8px">Date Printed : '.Carbon::now().'</p>
                                  </td>
                                </tr>
                                </table>
                                <table width="100%" cellspacing="0" cellpadding="5" page-break-inside:auto;>
                                <thead>
                                  <tr>
                                    <th align="center" rowspan="3"><b>PERIOD</b></th>
                                    <th align="center" rowspan="3"><b>PARTICULARS</b></th>
                                    <th align="center" colspan="6"><b>VACATION LEAVE</b></th>
                                    <th align="center"><b>UNAUTHORIZED ABSENCE</b></th>
                                    <th align="center" colspan="4"><b>SICK LEAVE</b></th>
                                  </tr>
                                  <tr>
                                    <th align="center" rowspan="2"><b>EARNED</b></th>
                                    <th align="center" colspan="2"><b>USED</b></th>
                                    <th align="center" rowspan="2"><b>BALANCE</b></th>
                                    <th align="center" colspan="2"><b>W/OUT PAY</b></th>
                                    <th align="center" rowspan="2"><b>W/OUT PAY</b></th>
                                    <th align="center" rowspan="2"><b>EARNED</b></th>
                                    <th align="center" rowspan="2"><b>USED</b></th>
                                    <th align="center" rowspan="2"><b>BALANCE</b></th>
                                    <th align="center" rowspan="2"><b>W/OUT PAY</b></th>
                                  </tr>
                               
                                <tr>
                                  <td align="center"><b>TARDY</b></td>
                                  <td align="center"><b>LEAVE</b></td>
                                  <td align="center"><b>TARDY</b></td>
                                  <td align="center"><b>LEAVE</b></td>
                                </tr>
                                </thead>
                                '.$tr.'
                                </table>
                                <br>
                            </body>
                            </html>');
        // $pdf->setOptions([
        //   'isPhpEnabled' => true,
        //   'isRemoteEnabled' => true,
        //   'isHtml5PatserEnabled' => true,
        // ]);


        $pdf->setPaper('legal', 'landscape');
        // $pdf->render();

        // $pageCount = $pdf->getDomPDF()->get_canvas()->get_page_count();

        // $font = 'Arial';
        // $size = 6;
        // $style = 'font-size:italic;';

        // $t = 590;
        // for ($i = 1; $i <= $pageCount; $i++) {
        //     $pdf->getDomPDF()->get_canvas()->page_text(480, $t, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, $size, array(0, 0, 0));

        //     $t += 590;
        // }
    
        return $pdf->stream();
    }

    
}
