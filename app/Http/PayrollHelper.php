<?php

use Illuminate\Support\Facades\Auth;

function getMembership($empcode)
{
	return App\Payroll\Membership::where('fldEmpCode',$empcode)->get();
}

function getPersonalLoans($empcode)
{
	$collect = collect(App\Payroll\PersonalLoan::where('fldEmpCode',$empcode)->get());
	return $collect->all();
}

function getPersonalPrevLoans($empcode,$mon,$yr)
{
	$collect = collect(App\Payroll\Prevdeduc::where('fldEmpCode',$empcode)->where('fldMonth',$mon)->where('fldYear',$yr)->get());
	return $collect->all();
}

function getLoanMonitor($empcode)
{
	$collect = collect(App\Payroll\LoanMonitor::where('fldEmpCode',$empcode)->where('loan','YES')->get());
	return $collect->all();
}

function getDeductions($empcode)
{
	$collect = collect(App\Payroll\Deduction::where('empCode',$empcode)->whereNotIn('deductID',[3,5,6,7])->get());
	return $collect->all();
}

function getDeductionsPrev($empcode,$mon,$yr)
{
	return App\Payroll\Prevmanda::where('empCode',$empcode)->where('fldMonth',$mon)->where('fldYear',$yr)->whereNotIn('deductID',[3,5,6,7])->get();
}

function getCompensation($empcode)
{
	$collect = collect(App\Payroll\Compensation::where('empCode',$empcode)->get());
	return $collect->all();
}

function getCompensationPrev($empcode,$mon,$yr)
{
	return App\Payroll\Prevcomp::where('empCode',$empcode)->where('fldMonth',$mon)->where('fldYear',$yr)->get();
}

function getCompensation_rata($empcode,$cond)
{
	if($cond)
	{
		return App\Payroll\Compensation::where('empCode',$empcode)->whereIn('compID',[3,4])->get();
	}
	else
	{
		return App\Payroll\Compensation::where('empCode',$empcode)->whereNotIn('compID',[3,4])->get();
	}
	
}

function formatCash($val)
{
	if($val == 0 || $val == null || $val == "")
	{
		return "-";
	}
	else
	{
		return number_format($val,2);
	}
}

function getPercent($percentage,$value)
{
	return number_format((($percentage / 100) * $value),2,'.','');
}

function getPlantillaInfo($empcode)
{
	$position = App\View_employee_position::where('username',$empcode)->orderBy('plantilla_date_from','DESC')->first();
	return $position;
}

function getLast($empcode)
{
	$position = App\Plantilla::where('username',$empcode)->orderBy('plantilla_date_to')->first();
	return $position;
}

function getPayrollList()
{
	$directories = Storage::disk('payroll')->directories();
	return $directories;
}

function getPayrollFileList($dir)
{
	$collection = collect([]);
    $filesInFolder = \File::files(storage_path('app/payroll/'.$dir));     
    foreach($filesInFolder as $path) { 
          $file = pathinfo($path);
          $collection->push($file['filename'] . '.' .$file['extension']);
     }

     return $collection->all();
}

function getSalaryWeek($username,$salary,$week,$type = null)
{
	//GET COMPENSATION
	// $total_comp = 0;
	// foreach(getCompensation_rata($username,false) AS $values)
	// {
	// 	$total_comp += $values->compAmount;
	// }

	// $rata = 0;
	// foreach(getCompensation_rata($username,true) AS $values)
	// {
	// 	$rata += $values->compAmount;
	//}


	//RATA
	$rata = 0;
	$pera = 0;

	$comp = collect(App\Payroll\Compensation::where('empCode',$username)->get());
	$comp = $comp->all();

	foreach ($comp as $c => $comps) {
		switch ($comps->compID) {
			case 1:
					$pera = $comps->compAmount;
				break;
			case 3:
			case 4:
					$rata += $comps->compAmount;
				break;
		}
	}

	$total_deduc = 0;
	//GET LOANS
	foreach(getPersonalLoans($username) AS $values)
	{
		$total_deduc += $values->DED_AMOUNT;
	}
	//GET MANDA DEDUC
	foreach(getDeductions($username) AS $values)
	{
		//REMOVE SIC FROM TABLE
		// if($values->deductID != 2)
			$total_deduc += $values->deductAmount;
	}

	//GET SIC FROM COMPUTATION
	// $sic = $salary * 0.09;
	// $total_deduc += $sic;

	$amnet = ($salary + $pera) - $total_deduc;
	

	$a = number_format(($amnet/4), 2, ".", "");

	$tenths = 0;
	 $cents = 0;
	 $excess = 0;
	 $r_fmod = fmod($a, 10);
	 
	 if($r_fmod > 0){
	 	 //$cents = $a - floor($a);
		 $cents = fmod($a,1);
		 $floored = $a - $cents;
		 $tenths = fmod($floored, 10);
		 $excess = $tenths + $cents;
	 } else {
	 	 $tenths = 0;
		 $cents = 0;
		 $excess = 0;
	 }

	$amount1 = number_format($a - $excess, 2, ".", "");
	$amount2 = number_format($a - $excess, 2, ".", "");
	$amount3 = number_format($a - $excess, 2, ".", "");
	

	switch ($week) {
		case 1:
				if($type == 1)
					return $amount1 + $rata;
				else
					return formatCash($amount1 + $rata);
		case 2:
		case 3:
				if($type == 1)
					return $amount2;
				else
					return formatCash($amount2);
			break;
		
		default:

				//FOR FEB 2022 ONLY
				$sic = 0;
				if(date('Y-m') == "2022-02")
				{
					$sic = App\Payroll\SICAdj::where('empcode',$username)->first();
					if($sic)
					{
						$sic = $sic['amount'];
					}
					
				}
				

				$amount4 = $amnet - $amount1 - $amount2 - $amount3 - $sic;

				if($type == 1)
					return $amount4;
				else
					return formatCash($amount4);
			break;
	}
}

function getSalaryTbl()
{
	//GET LATEST FILE
	$salarytbl = App\SalaryTable::first();

	$salary = storage_path('app/salarysched/'.$salarytbl['salary_filename'].'.csv');
}

function getPayrollLibrary($tbl)
{
	switch ($tbl) {
		case 'organization':
				$t = App\Payroll\Organization::get();
			break;
		case 'service':
				$t = App\Payroll\OrganizationService::get();
			break;
		case 'deduction':
				$t = App\Payroll\Deduc::get();
			break;
		case 'compensation':
				$t = App\Payroll\Comp::get();
			break;
	}

	return $t;
}

function getLP($userid)
{
	$lp = App\Payroll\LP::where('userid',$userid)->first();

	if($lp)
	{
		return $lp['lp'];
	}
	else
	{
		return 0;
	}
}

function getITW($userid)
{
	$lp = App\Payroll\ITW::where('userid',$userid)->first();
	if($lp)
	{
		return $lp['itw'];
	}
	else
	{
		return 0;
	}
	
}


function getTotalMCDeduc($type,$userid,$mon,$yr)
{
	$mc = App\Payroll\MC::where('userid',$userid)->where('payroll_mon',$mon)->where('payroll_yr',$yr)->first();

	if($type == 'total')
	{
		if(isset($mc))
		{
			return $mc['hmo'] + $mc['gsis'] + $mc['pmpc'] + $mc['cdc'] + $mc['gfal'] + $mc['landbank'];
		}
		else
		{
			return 0.00;
		}
		
	}
	else
	{
		if(isset($mc))
		{
			$collection = collect(['HMO' => $mc['hmo'], 'GSIS' => $mc['gsis'], 'PMPC' => $mc['pmpc'], 'CDC' => $mc['cdc'], 'GFAL' => $mc['gfal'], 'Landbank' => $mc['landbank']]);
		}
		else
		{
			$collection = collect(['HMO' => 0.00, 'GSIS' => 0.00, 'PMPC' => 0.00, 'CDC' => 0.00, 'GFAL' => 0.00, 'Landbank' => 0.00]);
		}

		return $collection->all();
	}
	
}

function checkMCProcess($mon,$yr)
{
	$mc = App\Payroll\MCProcess::where('payroll_mon',$mon)->where('payroll_year',$yr)->count();

	if($mc > 0)
		return true;
	else
		return false;
}


function getWeekSalary($username,$amnet,$week,$type = null)
{
	//RATA
	$rata = 0;
	$pera = 0;

	$comp = collect(App\Payroll\Compensation::where('empCode',$username)->get());
	$comp = $comp->all();

	foreach ($comp as $c => $comps) {
		switch ($comps->compID) {
			case 1:
					$pera = $comps->compAmount;
				break;
			case 3:
			case 4:
					$rata += $comps->compAmount;
				break;
		}
	}	

	$a = number_format(($amnet/4), 2, ".", "");

	$tenths = 0;
	 $cents = 0;
	 $excess = 0;
	 $r_fmod = fmod($a, 10);
	 
	 if($r_fmod > 0){
	 	 //$cents = $a - floor($a);
		 $cents = fmod($a,1);
		 $floored = $a - $cents;
		 $tenths = fmod($floored, 10);
		 $excess = $tenths + $cents;
	 } else {
	 	 $tenths = 0;
		 $cents = 0;
		 $excess = 0;
	 }

	$amount1 = number_format($a - $excess, 2, ".", "");
	$amount2 = number_format($a - $excess, 2, ".", "");
	$amount3 = number_format($a - $excess, 2, ".", "");
	

	switch ($week) {
		case 1:
				if($type == 1)
					return $amount1 + $rata;
				else
					return formatCash($amount1 + $rata);
		case 2:
		case 3:
				if($type == 1)
					return $amount2;
				else
					return formatCash($amount2);
			break;
		
		default:
				$amount4 = $amnet - $amount1 - $amount2 - $amount3;

				if($type == 1)
					return $amount4;
				else
					return formatCash($amount4);
			break;
	}
}

function getWeekSalary2($username,$amnet,$week,$type = null)
{
	//RATA
	$rata = 0;

	$a = number_format(($amnet/4), 2, ".", "");

	$tenths = 0;
	 $cents = 0;
	 $excess = 0;
	 $r_fmod = fmod($a, 10);
	 
	 if($r_fmod > 0){
	 	 //$cents = $a - floor($a);
		 $cents = fmod($a,1);
		 $floored = $a - $cents;
		 $tenths = fmod($floored, 10);
		 $excess = $tenths + $cents;
	 } else {
	 	 $tenths = 0;
		 $cents = 0;
		 $excess = 0;
	 }

	$amount1 = number_format($a - $excess, 2, ".", "");
	$amount2 = number_format($a - $excess, 2, ".", "");
	$amount3 = number_format($a - $excess, 2, ".", "");
	

	switch ($week) {
		case 1:
		case 2:
		case 3:
				if($type == 1)
					return $amount1;
				else
					return formatCash($amount1);
			break;
		
		default:
				$amount4 = $amnet - $amount1 - $amount2 - $amount3;

				if($type == 1)
					return $amount4;
				else
					return formatCash($amount4);
			break;
	}
}

function computePhil($salary,$type = null)
{
	$ph1 = $salary * 0.04;
	$ph2 = $ph1 / 2;

	//$ps =  floor($ph2 * 100) / 100;
	$ps =  round($ph2,2);
	$gs =  $ph1 - $ps;
	$gs =  round($gs,2);
	//$gs =  floor($gs * 100) / 100;
	
	if($type == null)
	{
		return $ps;
	}
	else
	{
		$ph1 = $salary * 0.03;
		$ph2 = $ph1 / 2;

		//$ps =  floor($ph2 * 100) / 100;
		$ps =  round($ph2,2);
		$gs =  $ph1 - $ps;
		$gs =  round($gs,2);
		//$gs =  floor($gs * 100) / 100;
		return $ps;
		//return "Salary : ".$salary."<br/>PHIL : ".$ph1."<br>PS: ".$ps."<br/>GS : ".$gs;
	}
		
}

function getMandaPrev($id,$emp,$mon,$yr)
{
	$manda = App\Payroll\Prevmandatbl::where('empCode',$emp)->where('fldMonth',$mon)->where('fldYear',$yr)->where('deductID',$id)->first();
	if($manda)
		return $manda['deductAmount'];
}

function getPayrollList2()
{
	$list = App\Payroll\PrevInfo::select('fldMonth','fldYear')->groupBy('fldMonth','fldYear')->orderBy('fldYear','DESC')->orderBy('fldMonth','DESC')->get();
	return $list;
}

function getInfoCOS($userid,$type)
{
	$user = App\Payroll\SalaryCOS::where('user_id',$userid)->first();
	if($user)
	{
		switch($type)
		{
			case 'position':
				$pos = App\Position::where('position_id',$user['position_id'])->first();
				if(isset($pos))
					return $pos['position_desc'];
				else
					return null;
			break;
			default:
				return $user[$type];
			break;
		}
	}
	else
	{
		return null;
	}
	
}

function getDeductionCOS($userid,$deduc,$mon,$yr,$period,$type = null)
{

	$amt = App\Payroll\DeductionCOS::where('user_id',$userid)->where('deduction',$deduc)->where('period',$period)->first();
	if(isset($amt))
		{
			$amt = $amt['amt'];
		}
		else
		{
			$amt = 0.00;
		}
	

	if($type == 'num')
	{
		return $amt;
	}
	else
	{
		//CHECK IF PROCESS NA
		$p = App\Payroll\ProcessCOS::where('user_id',$userid)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->first();
		if($p)
		{
			if($p['process_date'])
			{
				if($deduc == 'HDMF')
					return "<span>".formatCash($p['hdmf'])."</span>";
				else
					return "<span>".formatCash($p['pmpc'])."</span>";
			}
			else
			{
				return "<a href='#' onclick='editAMT(1,\"$userid\",\"$deduc\",$amt)'>".formatCash($amt)."</a>";
			}
			
		}
		else
		{
			return "<a href='#' onclick='editAMT(1,\"$userid\",\"$deduc\",$amt)'>".formatCash($amt)."</a>";
		}
	}

	
}

function getPeriodCOS($period = null,$mon = null, $yr = null)
{
	$per = 1;
	if(date('d') > 15)
		$per = 2;
	
	if($period)
	{
		if($period == 1)
		{
			return "1-15";
		}
		else
		{
			$e = date($yr.'-'.$mon.'-01');
			$e = date("Y-m-t", strtotime($e));
			$e = date("d", strtotime($e));

			return "16-".$e;
		}
			
	}
	else
	{
		return $per;
	}
}

function getProcessCOS($userid,$mon,$yr,$period,$total = null)
{
	if($total == 'total')
	{
		return App\Payroll\ProcessCOS::where('mon',$mon)->where('yr',$yr)->where('period',$period)->count();
	}
	elseif($total == 'salary' || $total == 'nodays' || $total == 'daysperiod' || $total == 'itw' || $total == 'process_date' || $total == 'id' || $total == 'created_at')
	{
		$result = App\Payroll\ProcessCOS::where('user_id',$userid)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->first();
		if(isset($result))
			return $result[$total];
		else
			return 0;
	}
	else
	{
		$p = App\Payroll\ProcessCOS::where('user_id',$userid)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->first();
		if($p)
		{
			if($p['process_date'])
			{
				return "<center>".$p['ors']."</center>";
			}
			else
			{
				if($p['ors'])
				{
					return "<center><a href='#' onclick='editAMT(2,\"$userid\",\"\",\"".$p['ors']."\",".$p['id'].")'>".$p['ors']."</a></center>";
				}
				else
				{					
					return "<center><a href='#' onclick='editAMT(2,\"$userid\",\"\",\"\",".$p['id'].")'>Enter ORS Number</a></center>";
				}
			}
		}
		else
		{
			return "<center class='text-danger'>DTR Not Process</center>";
		}
	}
	
}

function getProcessCOSPayroll($mon,$yr,$period)
{
	$p = App\Payroll\ProcessCOS::where('mon',$mon)->where('yr',$yr)->where('period',$period)->whereNotNull('process_date')->count();
	if($p > 0)
		return true;
	else
		return false;
}

function getProcessInfoCOS($userid,$mon,$yr,$period,$type)
{
	$p = App\Payroll\ProcessCOS::where('user_id',$userid)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->first();
	if($p )
	{
		return $p[$type];
	}
	else
	{
		return null;
	}

}

function getCOSSalary($userid,$col = null,$type = null)
{
	$p = App\Payroll\SalaryCOS::where('user_id',$userid)->first();
	if(isset($p))
	{
		//return $p[$col];
		$amt = $p[$col];
		if($col == 'salary' || $col == 'tax_rate' || $col == 'atm' | $col == 'charging')
		{
			if(!$type)
				if($col == 'atm' || $col == 'charging')
				{
					$amt = $p[$col];
					if($amt == null)
						$amt = '-';
					else
						$amt = $p[$col];
				}	
				else
				{
					$amt = formatCash($p[$col]);
				}
					
			else
				return $p[$col];
				
		}
		else
		{
			if($amt == null)
				$amt = '-';
		}
			

		return "<a href='#' onclick='editAMT(1,\"$userid\",\"$col\",".$p[$col].")'>".$amt."</a>";
	}
	else
	{
		return null;
	}

}

function checkProcessCOS($userid,$mon,$yr,$period,$div = null)
{
	$p = App\Payroll\ProcessCOS::where('user_id',$userid)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->first();
	if($p)
		return true;
	else
		return false;
}

function checkDTRCOS($userid,$mon,$yr,$period)
{
	//$p = App\Employee_icos_dtr2::where('user')
	$mn = date('m',strtotime($mon));
	$mn2 = date('F',mktime(0, 0, 0, $mn, 10));
    $date = $mn2 ."-" . $yr;
	$total = Carbon\Carbon::parse($date)->daysInMonth;
	$totalhrs = 0;

	$ctr = 0;
	$ctrtext = "";

	//PERIOD
	$d1 = 16;
	$d2 = 31;
	if($period == 1)
	{
		$d1 = 1;
		$d2 = 15;
	}

	for($i = 1;$i <= $total;$i++)
        {
			if($i >= $d1 && $i <= $d2)
            {
				$dt = date('Y-m-d',strtotime($yr.'-'.$mon.'-'.$i));

				if($dt <= date('Y-m-d'))
				{
					$dayDesc = weekDesc($dt);

					if($dayDesc == "Sat" || $dayDesc == "Sun")
					{
						$ctr++;
						$ctrtext .= $dt.",";
					}
					else
					{
						$p = App\Employee_icos_dtr2::where('user_id',$userid)->whereDate('fldEmpDTRdate',$dt)->first();
						if($p)
						{
							if($p['fldEmpDTRamIn'] != null && $p['fldEmpDTRamOut'] != null && $p['fldEmpDTRpmIn'] != null && $p['fldEmpDTRpmOut'] != null )
							{
								$totalhrs = totalHrs($dayDesc,$dt,$p['fldEmpDTRamIn'],$p['fldEmpDTRpmOut'],null,1);
							}

							if($p['fldEmpDTRamIn'] != null && $p['fldEmpDTRamOut'] != null && $p['fldEmpDTRamOut'] == null && $p['fldEmpDTRpmOut'] == null )
							{
								$tm1 = $p['fldEmpDTRamIn'];
								$tm2 = $p['fldEmpDTRamOut'];
								if($dayDesc == 'Mon')
								{
									if($tm1 <= "07:30:00")
										$tm1 = "07:30:00";
								}

								if($tm2 >= "12:00:00")
                                	$tm2 = "12:00:00";

									$dt1 = Carbon\Carbon::parse($dt.' '.$tm1)->format('Y-m-d H:s:i');
									$dt2 = Carbon\Carbon::parse($dt.' '.$tm2)->format('Y-m-d H:s:i');
									$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
									$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
			
									$totalhrs = $to->diffInMinutes($from);
							}

							if($p['fldEmpDTRamIn'] == null && $p['fldEmpDTRamOut'] == null && $p['fldEmpDTRpmIn'] != null && $p['fldEmpDTRpmOut'] != null )
							{
								$tm1 = $p['fldEmpDTRpmIn'];
								$tm2 = $p['fldEmpDTRpmOut'];
								if($dayDesc == 'Mon')
								{
									if($tm1 <= "13:00:00")
										$tm1 = "13:00:00";
									
									if($tm2 >= "17:00:00")
										$tm2 = "17:00:00";
								}

								if($tm2 >= "17:30:00")
										$tm2 = "17:30:00";
								
								$dt1 = Carbon\Carbon::parse($dt.' '.$tm1)->format('Y-m-d H:s:i');
								$dt2 = Carbon\Carbon::parse($dt.' '.$tm2)->format('Y-m-d H:s:i');
								$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
								$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);

								$totalhrs = $to->diffInMinutes($from);
							}


							if($totalhrs >= 240 && $totalhrs < 360)
                   			{
								$ctr += 0.5;

								$ctrtext .= $dt."(h),";
							}

							if($totalhrs >= 360)
                   			{
								$ctr += 1;

								$ctrtext .= $dt.",";
							}



						}
					}
				}
				
					
			}
		}

	return $ctr;
}

function checkDTRCOSWP($userid,$mon,$yr,$period)
{
	//return $userid.'-'.$mon.'-'.$yr.'-'.$period;


	$lastdays = "";
	$worksched = getDTROption();

	$emp = App\User::where('id',$userid)->first();

	$rows = "";
	
	$mon = date('m',strtotime($mon));
	$mon2 = date('F',mktime(0, 0, 0, $mon, 10));
	$yr = $yr;
	$date = $mon2 ."-" . $yr;
	//$month = ++$mon;

	//return $date;

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

	$week1Time = 1920;

	$totalabsent = 0;

	$total = \Carbon\Carbon::parse($date)->daysInMonth;
				 
				  $prevweek = 1;
				  $rows .= "<tr><td></td><td colspan='8' align='center'>  <b>WEEK 1 </b> </td><td></td></tr>";
				  $week_num = 2;
				  $total_days = 0;
				  for($i = 1;$i <= $total;$i++)
				  {
					  
					if($period == 1)
					{
						$d1 = 1;
						$d2 = 15;
					}
					else
					{
						$d1 = 16;

						$lastDayofMonth = \Carbon\Carbon::parse($date.'-01')->endOfMonth()->toDateString();
						$d2 = date('d',strtotime($lastDayofMonth));

						//return $d2;
					}

					if($i >= $d1 && $i <= $d2)
					{
					  $weeknum = weekOfMonth(date($yr.'-'.$mon.'-'.$i));
					  $dtr_date = date("Y-m-d",strtotime($yr.'-'.$mon.'-'.$i));
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
														$totalabsent += 1;
														$total_days -= 480;
														$datetxt .= $dtr_date.',';
													}
													else
													{
														if($sus['suspension_time_desc'] == 'AM' || $sus['suspension_time_desc'] == 'PM')
														{
															$totalabsent += 0.5;
															$total_days -= 240;
															$datetxt .= $dtr_date.',';
														}
														
													}
												}

											if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] != null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null)
												{
													if(!$sus)
													{
														$totalabsent += 0.5;
														$total_days -= 240;
														$datetxt .= $dtr_date.',';
													}
												}
											
											if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] != null && $dtr['fldEmpDTRpmOut'] != null)
												{
													if(!$sus)
													{
														$totalabsent += 0.5;
														$total_days -= 240;
														$datetxt .= $dtr_date.',';
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
										$tDeficit = readableTime(($total_days - $lastWeekLeaves) - $lastTimeWeek);

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
												$totalDeficit = $totalDeficit + (($total_days - $lastWeekLeaves) - $lastTimeWeek);
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
		$test = '<td style="border : 1px solid #FFF;font-size:12px">
			 <b>Total Lates : </b> '.readableTime($tLates).'<br>
			 <b>Total Undertime : </b>'.readableTime($tUndertime).'<br>
			 <b>Total Deficit : </b>'.readableTime($totalDeficit).'<br>
			 <b>Total Absences : </b>'.$totalabsent.' d<br>'.$datetxt.'<br><br>
	   </td>';

	   //return $datetxt;

	   $total = $tLates + $tUndertime + $totalDeficit;
	   $totaltxt = explode(' ',readableTime($total));
	   $totaltxt_hr = substr($totaltxt[0],0,-1);
	   if(isset($totaltxt[1]))
	   		$totaltxt_min = substr($totaltxt[1],0,-1);
		else
			$totaltxt_min = 0;

	   //return readableTime($total)." ".$totaltxt_hr.' - '.$totaltxt_min;
	   if($totaltxt_hr > 0)
			$totaltxt_hr = (int)$totaltxt_hr / 8;
		else
			$totaltxt_hr = 0;
		
		if($totaltxt_min > 0)
			$totaltxt_min = (int)$totaltxt_min / 480;
		else
			$totaltxt_min = 0;

			
	   $totaldayswithoutpay = $totalabsent + $totaltxt_hr + $totaltxt_min;

	   //return $totalabsent;

	   $totaldayswithoutpay = number_format((float)$totaldayswithoutpay, 2, '.', '');
	   

	   if($totaldayswithoutpay == '0.00')
	   	{
			return null;
	   	}
		else
		{
			$data = [
				'totaldayswithoutpay' => $totaldayswithoutpay,
				'totalabsent' => $totalabsent,
				'totallate' => $tLates,
				'totalundertime' => $tUndertime,
				'totaldeficit' => $totalDeficit,
			];

			// if($totalabsent <= 0)
			// 	$totalabsent = 0;

			// if($tLates <= 0)
			// 	$tLates = 0;
			
			// if($tUndertime <= 0)
			// 	$tUndertime = 0;
			
			// if($totalDeficit <= 0)
			// 	$totalDeficit = 0;

			return $totaldayswithoutpay."|".$totalabsent."|".$tLates."|".$tUndertime."|".$totalDeficit;
		}
			 
			 
}

function checkDTRCOSWP2($userid,$mon,$yr,$period)
{
// return request()->mon2;

	$lastdays = "";
	$worksched = getDTROption();

	$emp = App\User::where('id',$userid)->first();

	$rows = "";
	
	//$mon = date('m',strtotime($mon));
	$mon2 = date('F',mktime(0, 0, 0, $mon, 10));
	$yr = $yr;
	$date = $mon2 ."-" . $yr;
	$month = $mon + 1;

	
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
	$gDaysDeficit = 0;

	$tDeficit = 0;
	$tDaysLeave = 0;
	$tTimeWeek = 0;
	$lastTimeWeek = 0;
	$lastWeekLeaves = 0;

	$tDaysDeficitTxt = "";

	$week1Time = 1920;

	$totalabsent = 0;

	$total = \Carbon\Carbon::parse($date)->daysInMonth;
				 
				  $prevweek = 1;
				  $rows .= "<tr><td></td><td colspan='8' align='center'>  <b>WEEK 1 </b> </td><td></td></tr>";
				  $week_num = 2;
				  $total_days = 0;
				  for($i = 1;$i <= $total;$i++)
				  {
					  
					if($period == 1)
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
					  $weeknum = weekOfMonth(date($yr.'-'.$mon.'-'.$i));
					  $dtr_date = date("Y-m-d",strtotime($yr.'-'.$mon.'-'.$i));
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
							 $gDaysDeficit += $totalDeficit;
							 //$tDaysDeficitTxt .= $dtr_date." - ".$tDeficit.",";
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
                                                                //$totalabsent += 0.5;
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
															//$totalabsent += 0.5;
															$total_days -= 240;
														}
														
													}
												}

											if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] != null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null)
												{
													if(!$sus)
													{
														if(!checkIfHalfHoliday($dtr_date))
                                                            {
                                                                //$totalabsent += 0.5;
                                                                $total_days -= 240;
                                                            }
														//$totalabsent += 0.5;
														//$total_days -= 240;

													}
												}
											
											if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] != null && $dtr['fldEmpDTRpmOut'] != null)
												{
													if(!$sus)
													{
														if(!checkIfHalfHoliday($dtr_date))
                                                            {
                                                                //$totalabsent += 0.5;
                                                                $total_days -= 240;
                                                            }
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
                                    // if(!checkIfWeekend($dtr_date))
				                    // {
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
												$gDaysDeficit += $tDaysDeficit - $tDaysExcess;
                                                $tDaysDeficitTxt .= $dtr_date." - ".$tDeficit.",";
                                            }
                                            else
                                            {
                                                //$tDaysExcess = 0;
                                                $tDeficit = "";
                                                $totalDeficit = 0;
												$gDaysDeficit += 0;
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

                                    // }
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


			 $final = '<tr>
			 <td style="border : 1px solid #FFF;font-size:12px">
				   <b>Total Lates : </b> '.readableTime($tLates).'<br>
				   <b>Total Undertime : </b>'.readableTime($tUndertime).'<br>
				   <b>Total Deficit : </b>'.readableTime($gDaysDeficit).''.$tDaysDeficitTxt.'<br>
				   <b>Total Absences : </b>'.$totalabsent.' d<br>'.$datetxt.'<br><br>
			 </td>
		   </tr>';

	   $total = $tLates + $tUndertime + $gDaysDeficit;
	   $totaltxt = explode(' ',readableTime($total));
	   $totaltxt_hr = substr($totaltxt[0],0,-1);
	   if(isset($totaltxt[1]))
	   		$totaltxt_min = substr($totaltxt[1],0,-1);
		else
			$totaltxt_min = 0;

	   //return readableTime($total)." ".$totaltxt_hr.' - '.$totaltxt_min;
	   if($totaltxt_hr > 0)
			$totaltxt_hr = (int)$totaltxt_hr / 8;
		else
			$totaltxt_hr = 0;
		
		if($totaltxt_min > 0)
			$totaltxt_min = (int)$totaltxt_min / 480;
		else
			$totaltxt_min = 0;

			
	   $totaldayswithoutpay = $totalabsent + $totaltxt_hr + $totaltxt_min;

	   //return $totalabsent;

	   $totaldayswithoutpay = number_format((float)$totaldayswithoutpay, 3, '.', '');
	   

	   if($totaldayswithoutpay == '0.00')
	   	{
			return null;
	   	}
		else
		{
			$data = [
				'totaldayswithoutpay' => $totaldayswithoutpay,
				'totalabsent' => $totalabsent,
				'totallate' => $tLates,
				'totalundertime' => $tUndertime,
				'totaldeficit' => $gDaysDeficit,
			];

			// if($totalabsent <= 0)
			// 	$totalabsent = 0;

			// if($tLates <= 0)
			// 	$tLates = 0;
			
			// if($tUndertime <= 0)
			// 	$tUndertime = 0;
			
			// if($totalDeficit <= 0)
			// 	$totalDeficit = 0;

			return $totaldayswithoutpay."|".$totalabsent."|".$tLates."|".$tUndertime."|".$gDaysDeficit."|".$date;
		}
}

function getPosition($id)
{
	$pos = App\Position::where('position_id',$id)->first();
	return $pos['position_desc'];
}

function computerITWCOS($salary)
{
	return $salary * 0.03;
}


function getDaysInMonth($dt)
{
	$total = Carbon\Carbon::parse($dt)->daysInMonth;
	return $total;
}

function getICOSProcess($charging,$mon,$yr,$period,$dt = null)
{
	if($dt)
	{
		return App\Payroll\View_COS_process::where('charging',$charging)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->whereDate('process_date',$dt)->orderBy('division_acro')->orderBy('lname')->orderBy('fname')->get();
	}
	else
		return App\Payroll\View_COS_process::where('charging',$charging)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->orderBy('division_acro')->orderBy('lname')->orderBy('fname')->get();
}

function ifNull($val,$zero = null)
{

	if($val > 0)
	{
		return $val;
	}	
	else
	{
		if($zero)
			return null;
		else
			return '-';
	}
		
}


function checkSummaryCOSDTR($mon,$yr,$period)
{
	// if(Auth::user()->usertype == 'Marshal')
	// {
	// 	$process = App\Payroll\ProcessCOS::where('division',Auth::user()->division)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();
	// }
	// else
	// {
	// 	$process = App\Payroll\ProcessCOS::where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();
	// }
	
	$process = App\Payroll\ProcessCOS::where('division',Auth::user()->division)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();

	if(count($process) > 0)
		return true;
	else
		return false;
}

function checkPendingCOSProcess($mon,$yr,$period)
{
	$process = App\Payroll\ProcessCOS::whereNull('process_date')->where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();
	if(count($process) > 0)
		return true;
	else
		return false;
}

function checkPendingCOSProcess2($mon,$yr,$period)
{
	$process = App\Payroll\ProcessCOS::whereNull('process_date')->where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();
	return count($process);
	if(count($process) > 0)
		return true;
	else
		return false;
}

function checkDateCOSProcess($mon,$yr,$period)
{
		$process = App\Payroll\ProcessCOS::whereNotNull('process_date')->where('mon',$mon)->where('yr',$yr)->where('period',$period)->groupBy('process_date')->get();
		if(count($process) >= 1)
		{
			return $process;
		}
		else
			return null;
	
}

function getCOSProcessList($mon,$yr,$period)
{
	if(Auth::user()->usertype == 'Marshal' || Auth::user()->id == 233 || Auth::user()->id == 295)
	{
		$process = App\Payroll\View_COS_process::where('division',Auth::user()->division)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->orderBy('lname')->orderBy('fname')->get();
	}
	else
	{
		$process = App\Payroll\View_COS_process::where('mon',$mon)->where('yr',$yr)->where('period',$period)->orderBy('lname')->orderBy('fname')->get();
		//process = App\Payroll\ProcessCOS::where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();
	}

	
	if(count($process) > 0)
		return $process;
	else
		return null;
}

function convertTextCOSPayroll($userid,$lname,$fname,$mname,$net,$period)
{
	//CREATE FILE
	$text = "";
	$text2 = "";

	//MAX CHARACTER
	$max = 73;
	$max2 = 23;

	//CONST FOR LANDBANK
	$atm = "1890000";

	$atmNo = getCOSSalary($userid,'atm','num');

	$txt1 = $atmNo.''.$lname.','.$fname.' '.$mname[0];
	
    $txtctr = strlen($txt1);

    $wksal = str_replace(array('.', ','), '' , $net);

    $chrs = 73 - ($txtctr + 23);

	//return $wksal.'<br/>';
    $spaces = str_repeat(' ', $chrs);

    //ZERO BEFORE SALARY
    $txt2 = $wksal.$atm.''.$period;
    $txtctr = strlen($txt2);
    $chrs = 23 - ($txtctr);
    $zeros = str_repeat('0', $chrs);

	$text = $atmNo.''.ucwords(strtolower($lname)).','.ucwords(strtolower($fname)).' '.ucwords(strtolower($mname[0])).$spaces.$zeros.$txt2.str_repeat(' ', 7)."\n";

	return $text;
}

function getProcessDivCOS($div,$mon,$yr,$period)
{
	$process = App\Payroll\ProcessCOS::where('division',$div)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();
	if(count($process) >= 1)
	{
		return count($process);
	}
	else
		return 0;
}

function getPendingProcessDivCOS($div,$mon,$yr,$period)
{
	if($div == 'all')
	{
		$process = App\Payroll\ProcessCOS::whereNull('process_date')->where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();
		if(count($process) >= 1)
		{
			return count($process);
		}
		else
			return 0;
		}
	else
	{
		$process = App\Payroll\ProcessCOS::whereNull('process_date')->where('division',$div)->where('mon',$mon)->where('yr',$yr)->where('period',$period)->get();
		if(count($process) >= 1)
		{
			return count($process);
		}
		else
			return '-';
	}
	
}

function getPayrollCOS()
{
	$userid = Auth::user()->id;
	$payroll = App\Payroll\ProcessCOS::where('user_id',$userid)->orderBy('id')->get();

	$data = array();

	foreach ($payroll as $key => $payrolls) {
		//$data = array();

		$salary = $payrolls->salary;

		//TOTAL DAYS
		$totaldays = $payrolls->daysmonth;

		//PERIOD DAYS
		$daysperiod = $payrolls->daysperiod;

		//NO OF DAYS
		$nodays = $payrolls->nodays;

		//EARNED FOR THE PERIOD
		$earned = ($salary / $totaldays) * ($daysperiod - $nodays);

		//ITW
		$itw = $payrolls->itw;
			if($itw == 0 || $itw == null)
				$itw = 0;
		else
			$itw = $earned * $payrolls->itw;

		//HDMF
			$hdmf = $payrolls->hdmf;

		//PMPC
			$pmpc = $payrolls->pmpc;

		//DEDUCTIONS
			$deductions = $itw + $hdmf + $pmpc;

		//WITHOUT PAY
			$withoutpay = ($salary / $totaldays) * $nodays;
		//NET
			$net = (($salary / $totaldays) * ($daysperiod - $nodays)) - $deductions;


		$mon = date('F',mktime(0, 0, 0, $payrolls->mon, 10));

		$period_text = $mon.' '.getPeriodCOS($payrolls->period,$payrolls->mon,$payrolls->yr).' '.$payrolls->yr;

		$data[] = array("period_text" => $period_text,'net' => $net, "mon" => $payrolls->mon, "yr" => $payrolls->yr, "period" => $payrolls->period);

	}

	
	return $data;
}

function checkLockPayroll($mon,$yr,$period)
{
	$lock = App\Payroll\ProcessCOSLock::where('mon',$mon)->where('yr',$yr)->where('period',$period)->first();
	if(isset($lock))
		return true;
	else
		return false;
}

function checkBenefitRemove($ty,$yr,$userid)
{
	$remove = App\Payroll\Benefit_remove::where('benefit_type',$ty)->where('benefit_year',$yr)->where('userid',$userid)->first();
	if(isset($remove))
		return true;
	else
		return false;
}

function checkBenefitProcess($ty,$yr)
{
	$proc = App\Payroll\Benefit_process::where('benefit_type',$ty)->where('benefit_year',$yr)->first();
	if(isset($proc))
		return true;
	else
		return false;
}