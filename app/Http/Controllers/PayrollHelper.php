<?php

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
	$collect = collect(App\Payroll\Deduction::where('empCode',$empcode)->get());
	return $collect->all();
}

function getDeductionsPrev($empcode,$mon,$yr)
{
	return App\Payroll\Prevmanda::where('empCode',$empcode)->where('fldMonth',$mon)->where('fldYear',$yr)->get();
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
		return 0;
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

function computePhil($salary)
{
	$ph = $salary * 0.04;
	$ph = $ph / 2;

	$ps =  floor($ph * 100) / 100;
	
	return $ps;
}