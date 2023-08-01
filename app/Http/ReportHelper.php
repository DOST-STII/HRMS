<?php
use Illuminate\Support\Collection;

function getDTRMonitoring($div,$date)
{
	$collection = collect(App\Employee_dtr::where('division',$div)->where('fldEmpDTRdate',$date)->get());
	return $collection->all();
}

function checkIfProcess($empcode,$mon,$yr)
{
	$dtr = App\DTRProcessed::where('empcode',$empcode)->where('dtr_mon',$mon)->where('dtr_year',$yr)->count();

	if($dtr > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function getDTRSummary($div,$mon,$yr)
{
	$summary = collect(App\DTRProcessed::where('dtr_division',$div)->where('dtr_mon',$mon)->where('dtr_year',$yr)->orderBy('employee_name')->get());
	return $summary->all();

}

function formatNull($val)
{
	if($val <= 0)
	{
		return "-";
	}
	else
	{
		return $val;
	}
}

function getDirector2($div)
    {
        $division = App\Division::where('division_id',$div)->first();
        return $division['director'];
    }
function getAllLeave()
{
	$lv = collect(App\Leave_type::whereNotIn('id',[10,13,14,15])->get());
	return $lv->all();
}

function getTotalTardy($userid,$mon,$yr)
{
	// return $userid." ".$mon." ".$yr;
	
	$lwp = getLWP($userid,$mon,$yr);
	$lwp = explode("|", $lwp);

	return number_format((float)$lwp[1], 3, '.', '');
}

function getSummary($userid,$mon,$yr)
{
	$summary = collect(App\DTRProcessed::where('userid',$userid)->where('dtr_mon',$mon)->where('dtr_year',$yr)->get());
	return $summary;
}

function getLVSummary($leaveid,$userid,$mon,$yr)
{
	$prv_mon = $mon - 1;
	$yr = $yr;

	if($prv_mon == 0)
	{
		$prv_mon = 12;
		$yr = $yr - 1;
	}

	$col = "sl_bal";
	if($leaveid == 1)
	{
		$col = "vl_bal";
	}

	$summary = App\DTRProcessed::where('userid',$userid)->where('dtr_mon',$prv_mon)->where('dtr_year',$yr)->first();

	return $summary[$col];
}

function getLVDesc($id,$col)
{
	$lv = App\Leave_type::where('id',$id)->first();
	return $lv[$col];
}

function GetMCreport($userid,$mon,$yr)
{
        $date = $mon .' '.$yr;

        //MC
        $mc = App\Payroll\MC::where('userid',Auth::user()->id)->where('payroll_mon',$mon)->where('payroll_yr',$yr)->first();
        
        if($mc)
        {
            $hp = $mc['salary'] * $mc['hprate'];
        

        //COMPENSATION
        $comp = $mc->lp + $mc->sa + $mc->la + $hp;

        //DEDUCTION
        $deduc = $mc->hmo + $mc->gsis + $mc->pmpc + $mc->cdc + $mc->gfal + $mc->landbank + $mc->itw;

        //NET MC
        $net = $comp - $deduc;

        // // $mon2 = date('F',mktime(0, 0, 0, $mon, 10));

        //     //ROW
        //     $mcs = App\View_employee_mc::where('user_id',$userid)->first();  

        //     $hp = getPercent($mcs['hp_per'],$mcs['hp_salary']);

		// 	//GET HP RATE
        //     $hp_rate = App\Payroll\MC::where('userid',Auth::user()->id)->where('payroll_mon',$mon)->where('payroll_yr',$yr)->first();

        //     //GET SALARY
        //     $plantilla = getPlantillaInfo(Auth::user()->username);

        //     $hp = $plantilla['plantilla_salary'] * $hp_rate['hprate'];

        //     $lp = getLP($userid);

        //     $itw = getITW($userid);

        //     $total_deduc = getTotalMCDeduc('total',$userid,$mon,$yr) + $itw;

        //     //GET PROCESS CODE
        //     if($mon == 1)
        //     {
        //         $codemon = 12;
        //         $codeyear = $yr - 1;
        //     }
        //     else
        //     {
        //         $codemon = $mon - 1;
        //         $codeyear = $yr;
        //     }

        //     $code = App\DTRProcessed::where('userid',Auth::user()->id)->where('dtr_mon',$codemon)->where('dtr_year',$codeyear)->first();


        //     //TOTAL S.A DEDUCTION
        //     $rows3 = "";
        //     $m = 0;
        //     $mcd = App\MCday::where('process_code',$code['process_code'])->get();
        //     foreach($mcd as $key => $value) {
        //             $dt = date('M d, y',strtotime($value->req_date_from));
        //             if($value->req_date_from != $value->req_date_to)
        //             {
        //                 $dt = date('M d, y',strtotime($value->req_date_from))." - ".date('M d, y',strtotime($value->req_date_to));
        //             }

        //             $rows3 .= "<tr><td>".$value->req_type."</td><td>".$dt."</td><td>".$value->req_deduc."</td></tr>";
        //             $m += $value->req_deduc;
        //     } 
            
        //     $total_sa = $mcs['sa_amt'] - ($m * 150);

        //     $total = ($lp + $total_sa + $mcs['la_amt'] + $mcs['hp_amt'] + $hp) - $total_deduc;

            return formatCash($net);
        }

        
       
}

function getLVUpdate($userid,$mon,$yr)
{
	$lv = App\Employee_update_leave::where('userid',$userid)->where('mon',$mon)->where('yr',$yr)->first();
    
    if($lv)
	    return $lv['vl_bal'];
    else
        return '';
}

function getDefUpdate($userid,$mon,$yr)
{
    $lv = App\Employee_update_leave::where('userid',$userid)->where('mon',$mon)->where('yr',$yr)->first();
    
    if($lv)
    {
        if($lv['total'] != null || $lv['total'] > 0)
            return readableTime($lv['total']);
        else
            return "<button class='btn btn-primary btn-sm' onclick='submitFrm($userid,$mon)'>Get</button>";
    }  
    else
    {
        return "<button class='btn btn-primary btn-sm' onclick='submitFrm($userid,$mon)'>Get</button>";
    }
        
}

