<?php

namespace App\Http\Controllers\AttendanceMonitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Carbon\Carbon;
use Auth;

class PDFController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','staff']);
    }

    public function myDTR()
    {
    	$emp = App\User::where('id',request()->empid)->first();

    	$rows = "";

    	//BREAKDOWN DATE
    	// $dt = explode("-", $date);
    	// $mon = date('m',strtotime($dt[0]));
    	// $yr = $dt[1];
    	$date = request()->dtr_mon ."-" . request()->dtr_yr;
    	$mon = date('m',strtotime(request()->dtr_mon));
    	$yr = request()->dtr_year;


    	$total = Carbon::parse("February-2021")->daysInMonth;
                      $prevweek = 1;
                      $rows .= "<tr><td colspan='9' align='center'>  <b>WEEK 1 </b> </td></tr>";
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
                          $rows .= "<tr><td colspan='9' align='center'> <b>WEEK $week_num </b> </td></tr>";
                          $week_num++;
                        }


                        $dtr_date = $yr.'-'.$mon.'-'.$i;

                        $dayDesc = weekDesc(date($yr.'-'.$mon.'-'.$i));

                        $dtr = getDTRemp($dtr_date,Auth::user()->username);

                        // $rows .= "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'>".getDTR($dtr_date,'am-in',$dayDesc,Auth::user()->id)."</td><td align='center'>".getDTR($dtr_date,'am-out',$dayDesc,Auth::user()->id)."</td><td align='center'>".getDTR($dtr_date,'pm-in',$dayDesc,Auth::user()->id)."</td><td align='center'>".getDTR($dtr_date,'pm-out',$dayDesc,Auth::user()->id)."</td></tr>";
                        
                       	 $rows .=  "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRotIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRotOut'])."</td><td align='center'>".countTotalTime($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'])."</td><td align='center'></td></tr>";


                      }

    	$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE html>
							<html>
							<head>
							  <title>HRMIS - MY DTR</title>
							  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
							</head>
							<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
								th,td
								{
									border:1px solid #555;
									font-size:10px;
								}
							</style>
							<body>
								<p align="right" border="0" style="font-size:10px">Date Printed : '.Carbon::now().'</p>
								<center>
									<h4 style="font-size:12px">
										Republic of the Philippines<br/>
										PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
										RESEARCH AND DEVELOPMENT<br/>
										Los Baños, Laguna
									</h4>
								</center>
								<center><h3><b>DTR '.request()->dtr_mon.'  '.request()->dtr_year.'</b></h3></center>
								<table width="100%" cellspacing="0" cellpadding="4">
									<tr>
										<th><b>Day</b></th><th><center><b>AM In</b></center></th><th><center><b>AM Out</b></center></th><th><center><b>PM In</b></center></th><th><center><b>PM Out</b></center></th><th><center><b>OT In</b></center></th><th><center><b>OT Out</b></center></th><th><center><b>Total Hours</b></center></th><th><center><b>Remarks</b></center></th>
									</tr>
									<tbody>
										'.$rows.'
									</tbody>
								</table>
								
							</body>
							</html>')
		->setPaper('a4', 'portrait');
		return $pdf->stream();
    }
}
