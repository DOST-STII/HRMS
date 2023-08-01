<?php

function getDTR($d,$t,$daydesc,$user_id)
{
	$time = "-";
	$dtr = App\Employee_dtr::where('user_id',$user_id)->whereDate('dtr_date',$d)->first();
	switch ($t) {
		case 'am-in':
				if(isset($dtr['dtr_am_in']))
				{
					if($daydesc == "Mon")
					{
						if(strtotime($dtr['dtr_am_in']) > strtotime("8:00:59"))
						{
							$time = "<span style='color:red'><b>".date("g:i a",strtotime($dtr['dtr_am_in']))."</b></span>";
							// $time = date("g:i a",strtotime($dtr['dtr_am_in']));
						}
						else
						{
							$time = date("g:i a",strtotime($dtr['dtr_am_in']));
						}
					}
					else
					{
						if(strtotime($dtr['dtr_am_in']) > strtotime("8:30:59"))
						{
							$time = "<span style='color:red'><b>".date("g:i a",strtotime($dtr['dtr_am_in']))."</b></span>";
							// $time = date("g:i a",strtotime($dtr['dtr_am_in']));
						}
						else
						{
							$time = date("g:i a",strtotime($dtr['dtr_am_in']));
						}
					}
				}
				// if(isset($dtr['dtr_am_in']))
				// {
				// 	$time = date("g:i a",strtotime($dtr['dtr_am_in']));
				// }
				// else
				// {
				// 	$time = "-";
				// }
			break;
		case 'am-out':
				if(isset($dtr['dtr_am_out']))
				{
					$time = date("g:i a",strtotime($dtr['dtr_am_out']));
				}
			break;
		case 'pm-in':
				if(isset($dtr['dtr_pm_in']))
				{
					if(strtotime($dtr['dtr_pm_in']) > strtotime("13:00:59"))
						{
							$time = "<span style='color:red'><b>".date("g:i a",strtotime($dtr['dtr_pm_in']))."</b></span>";
							// $time = date("g:i a",strtotime($dtr['dtr_am_in']));
						}
						else
						{
							$time = date("g:i a",strtotime($dtr['dtr_pm_in']));
						}
				}
			break;
		case 'pm-out':
				if(isset($dtr['dtr_pm_out']))
				{
					$time = date("g:i a",strtotime($dtr['dtr_pm_out']));
				}
			break;
	}
	return $time;
}

function getPending($id,$userid = null)
{
	if($userid == null)
	{
		$empid = Auth::user()->id;
	}
	else
	{
		$empid = $userid;
	}
	
	if($id == 5)
	{
		$ctr = App\Request_leave::whereNotNull('parent_leave')->where('user_id',$empid)->where('leave_id',$id)->where('leave_action_status','Pending')->get()->sum('leave_deduction');
	}
	else
	{
		//MULTIPLE DATES
		$ctr_0 = App\Request_leave::whereNull('parent_leave')->where('user_id',$empid)->where('leave_id',$id)->whereNotIn('leave_action_status',['Processed','Cancelled','Disapproved'])->whereNull('process_code')->get()->sum('leave_deduction');

		//MULTIPLE DATES
		$ctr_1 = App\Request_leave::where('parent','YES')->where('leave_deduction',1)->where('user_id',$empid)->where('leave_id',$id)->whereNotIn('leave_action_status',['Processed','Cancelled','Disapproved'])->whereNull('process_code')->get()->sum('leave_deduction');

		$ctr = $ctr_0 + $ctr_1;
	}
	
	if($ctr == 0)
	{
		return 0;
	}
	else
	{
		return $ctr;
	}
}

function getProjectedLeave($leave,$pending)
{
	if($pending == "-")
	{
		$pending = 0;
	}

	if($leave == "-")
	{
		$leave = 0;
	}

	$total = $leave - $pending;
	
	if($total == 0)
	{
		return '-';
	}
	else
	{
		return $total;
	}

}

function getDisableDates()
{
	//HOLIDAYS
	$req = App\Holiday::get();
	foreach ($req as $reqs) {
		$data[] = array("date_desc" => $reqs->holiday_date);
	}

	//EXISTING LEAVES
	$req = App\Request_leave::where('user_id',Auth::user()->id)->get();
	foreach ($req as $reqs) {
		$data[] = array("date_desc" => $reqs->leave_date);
	}

	return $data;
}

function getDTRicos($d,$empcode)
{
	$dtr = App\Employee_icos_dtr::whereDate('fldEmpDTRdate',$d)->where('fldEmpCode',$empcode)->first();
	if($dtr)
	{
		return array("fldEmpDTRamIn" => formatTime($dtr['fldEmpDTRamIn']),"fldEmpDTRamOut" => formatTime($dtr['fldEmpDTRamOut']),"fldEmpDTRpmIn" => formatTime($dtr['fldEmpDTRpmIn']),"fldEmpDTRpmOut" => formatTime($dtr['fldEmpDTRpmOut']));
	}
	else
	{
		return array("fldEmpDTRamIn" => "","fldEmpDTRamOut" => "","fldEmpDTRpmIn" => "","fldEmpDTRpmOut" => "");
	}
	
}

function getDTRemp($d,$user_id,$employment_id,$empcode)
{
	
	switch ($employment_id) {

		case 5:
		case 6:
		case 7:
		case 8:
				$dtr = App\Employee_icos_dtr::whereDate('fldEmpDTRdate',$d)->where('fldEmpCode',$empcode)->orderBy('id','DESC')->first();

				if($dtr)
				{
					return array("id" => $dtr['id'],"fldEmpDTRamIn" => $dtr['fldEmpDTRamIn'],"fldEmpDTRamOut" => $dtr['fldEmpDTRamOut'],"fldEmpDTRpmIn" => $dtr['fldEmpDTRpmIn'],"fldEmpDTRpmOut" => $dtr['fldEmpDTRpmOut'],"fldEmpDTRotIn" => $dtr['fldEmpDTRotIn'],"fldEmpDTRotOut" => $dtr['fldEmpDTRotOut'],"dtr_ot" => $dtr['dtr_ot'],"dtr_remarks" => $dtr['dtr_remarks'],"dtr_request" => $dtr['request'],"wfh" => $dtr['wfh'],"dtr_to" => $dtr['dtr_to'],"fldEmpDTRdate" => $dtr['fldEmpDTRdate'],"dtr_option_id" => $dtr['dtr_option_id']);
				}
				else
				{
					return array("id" => "","fldEmpDTRamIn" => "","fldEmpDTRamOut" => "","fldEmpDTRpmIn" => "","fldEmpDTRpmOut" => "","fldEmpDTRotIn" => "","fldEmpDTRotOut" => "", "dtr_ot" => "","dtr_remarks" => "","dtr_request" => "","wfh" => "","dtr_to" => "","fldEmpDTRdate" => "","dtr_option_id" => "");
				}
			break;
		
		default:

				$dtr = App\Employee_dtr::whereDate('fldEmpDTRdate',$d)->where('fldEmpCode',$empcode)->orderBy('id','DESC')->first();

				if($dtr)
				{
					return array("id" => $dtr['id'],"fldEmpDTRamIn" => $dtr['fldEmpDTRamIn'],"fldEmpDTRamOut" => $dtr['fldEmpDTRamOut'],"fldEmpDTRpmIn" => $dtr['fldEmpDTRpmIn'],"fldEmpDTRpmOut" => $dtr['fldEmpDTRpmOut'],"fldEmpDTRotIn" => $dtr['fldEmpDTRotIn'],"fldEmpDTRotOut" => $dtr['fldEmpDTRotOut'],"dtr_ot" => $dtr['dtr_ot'],"dtr_remarks" => $dtr['dtr_remarks'],"dtr_request" => $dtr['request'],"wfh" => $dtr['wfh'],"dtr_to" => $dtr['dtr_to'],"fldEmpDTRdate" => $dtr['fldEmpDTRdate'],"dtr_option_id" => $dtr['dtr_option_id'],"request" => $dtr['request']);
				}
				else
				{
					return array("id" => "","fldEmpDTRamIn" => "","fldEmpDTRamOut" => "","fldEmpDTRpmIn" => "","fldEmpDTRpmOut" => "","fldEmpDTRotIn" => "","fldEmpDTRotOut" => "", "dtr_ot" => "","dtr_remarks" => "","dtr_request" => "","wfh" => "","dtr_to" => "","fldEmpDTRdate" => "","dtr_option_id" => "","request" => "");
				}
			break;
	}	
}

function formatTime($t)
{
	if($t != null){
		return date("g:i", strtotime($t));
	}
	else
	{
		return null;
	}
}

function countTotalTime($amIn,$amOut,$pmIn,$pmOut,$ot,$otIn = null,$otOut = null,$dt = null,$day = null)
{

	$notime = false;

	if($amIn == null && $amOut == null && $pmIn != null && $pmOut != null)
	{
		//PM
		$start_date2 = new DateTime($dt." ".$pmOut);
		$since_start2 = $start_date2->diff(new DateTime($dt." 13:00:00"));
		$h2 = $since_start2->h;
		$min2 = $since_start2->i;

		$h1 = 0;
		$min1 = 0;
	}
	elseif($amIn != null && $amOut != null && $pmIn == null && $pmOut == null)
	{
		//AM
		$start_date1 = new DateTime($dt." ".$amIn);
		$since_start1 = $start_date1->diff(new DateTime($dt." 12:00:00"));
		$h1 = $since_start1->h;
		$min1 = $since_start1->i;

		$h2 = 0;
		$min2 = 0;
	}
	elseif($amIn != null && $amOut != null && $pmIn != null && $pmOut != null)
	{
		$am = "12:00:00";
		$pm = "13:00:00";
		//AM
		$start_date1 = new DateTime($dt." ".$amIn);
		$since_start1 = $start_date1->diff(new DateTime($dt." ".$am));
		$h1 = $since_start1->h;
		$min1 = $since_start1->i;

		//PM
		$start_date2 = new DateTime($dt." ".$pmOut);
		$since_start2 = $start_date2->diff(new DateTime($dt." ".$pm));
		$h2 = $since_start2->h;
		$min2 = $since_start2->i;
	}
	else
	{
		$h1 = 0;
		$min1 = 0;
		$h2 = 0;
		$min2 = 0;
		$notime = true;
	}


	if(($min1 + $min2) >= 59)
		{
			$mintotal = ($min1 + $min2) - 59;
			$hrtotal = $h1 + $h2 + 1;
		}
		else
			{
				$mintotal = $min1 + $min2;
				$hrtotal = $h1 + $h2;
			}
	if($notime)
	{
		return null;
	}
	else
	{
		return $hrtotal."h ".$mintotal. "m";
	}
}

function countTotalTimeDiff($t1,$t2,$dt)
{
	$start_date = new DateTime($dt." ".$t1);
	$since_start = $start_date->diff(new DateTime($dt." ".$t2));
	$hr = $since_start->h;
	$min = $since_start->i;

	

	if($min >= 59)
		{
			$mintotal = $min - 59;
			$hrtotal = $hr + 1;
		}
		else
			{
				$mintotal = $min;
				$hrtotal = $hr;
			}
	
			return $hrtotal."h ".$mintotal. "m";
}

function countTotalTimeWeek($amIn,$amOut,$pmIn,$pmOut,$ot,$otIn,$otOut,$dt,$day,$ws)
{
	$notime = false;

	if($ws == 5 || $ws == 6 || $ws == 7)
	{
			return 8;
	}
	else
	{
		if($amIn == null && $amOut == null && $pmIn != null && $pmOut != null)
			{
				//PM
				$start_date2 = new DateTime($dt." ".$pmOut);
				$since_start2 = $start_date2->diff(new DateTime($dt."13:00:00"));
				$h2 = $since_start2->h;
				$min2 = $since_start2->i;

				$h1 = 0;
				$min1 = 0;
			}
			elseif($amIn != null && $amOut != null && $pmIn == null && $pmOut == null)
			{
				//AM
				$start_date1 = new DateTime($dt." ".$amIn);
				$since_start1 = $start_date1->diff(new DateTime($dt."12:00:00"));
				$h1 = $since_start1->h;
				$min1 = $since_start1->i;

				$h2 = 0;
				$min2 = 0;
			}
			elseif($amIn != null && $amOut != null && $pmIn != null && $pmOut != null)
			{
				$am = "12:00:00";
				$pm = "13:00:00";
				//AM
				$start_date1 = new DateTime($dt." ".$amIn);
				$since_start1 = $start_date1->diff(new DateTime($dt." ".$am));
				$h1 = $since_start1->h;
				$min1 = $since_start1->i;

				//PM
				$start_date2 = new DateTime($dt." ".$pmOut);
				$since_start2 = $start_date2->diff(new DateTime($dt." ".$pm));
				$h2 = $since_start2->h;
				$min2 = $since_start2->i;
			}
			else
			{
				$h1 = 0;
				$min1 = 0;
				$h2 = 0;
				$min2 = 0;
				$notime = true;
			}


			if(($min1 + $min2) >= 59)
				{
					$mintotal = ($min1 + $min2) - 59;
					$hrtotal = $h1 + $h2 + 1;
				}
				else
				{
						$mintotal = $min1 + $min2;
						$hrtotal = $h1 + $h2;
				}

			if($notime)
			{
				return null;
			}
			else
			{
				return number_format(($hrtotal + $mintotal), 2, '.', '');
			}

	}
}

function countTotalTimeEach($dt,$t1,$t2)
{
	$total = 0;
	if($t1 != null && $t2 != null)
		{
			// $time1 = strtotime($t1);
			// $time2 = strtotime($t2);
			// $difference = round(abs($time1 - $time2) / 3600,2);

			// if($difference != 0)
			// {
			// 	$total = $difference;
			// }
			$start_date = new DateTime($dt." ".$t1);
			$since_start = $start_date->diff(new DateTime($dt." ".$t2));

			$total =  $since_start->h.".".$since_start->i."".$since_start->s;
		}

	return round($total,2);
}


function countUndertime($dt,$day,$t1,$t2,$t3,$t4,$ws = null)
{	
//CHECK WORK SCHUDULE
// $ws = showActiveWS();
    switch ($ws) {
        case 5:
        case 6:
        case 7:
        	$undertime = null;
			$totalinmin = 0;
			return $undertime."|".$totalinmin;
        break;

        default:
        	if($t1 != null && $t2 != null && $t3 == null && $t4 == null)
				{
					$t1 = $t1;
					$t2 = $t2;
					$t = "08:30:00";

					if($day == 'Mon')
					{
						$t = "08:00:00";
					}

					if($t1 > $t)
					{
						$start_date = new DateTime($dt." ".$t);
						$since_start = $start_date->diff(new DateTime($dt." 12:00:00"));
					}
					else
					{
						$start_date = new DateTime($dt." ".$t1);
						$since_start = $start_date->diff(new DateTime($dt." 12:00:00"));
					}

					$h = 7 - $since_start->h;
					$m = 59 - $since_start->i;

					$undertime = $h."h ".$m."m ";
					$totalinmin = ($h * 60) + $m;

					return $undertime."|".$totalinmin;

				}
				elseif($t1 == null && $t2 == null && $t3 != null && $t4 != null)
				{
					$t1 = $t3;
					$t2 = $t4;
					$t = "13:00:00";

					if($t1 > $t)
					{
						$start_date = new DateTime($dt." ".$t);
						$since_start = $start_date->diff(new DateTime($dt." 12:00:00"));
					}
					else
					{
						$start_date = new DateTime($dt." ".$t1);
						$since_start = $start_date->diff(new DateTime($dt." 12:00:00"));
					}

					$h = 7 - $since_start->h;
					$m = 59 - $since_start->i;

					$undertime = $h."h ".$m."m ";
					$totalinmin = ($since_start->h * 60) + $since_start->i;

					return $undertime."|".$totalinmin;
				}
				elseif($t1 != null && $t2 != null && $t3 != null && $t4 != null)
				{
					$minpm = "16:00:00";
					$maxpm = "17:30:00";
					$maxam = "07:30:00";

					$t = "08:30:00";
					$t1 = $t1;
					$t2 = $t4;
					$undertime = "17:30:00";

					if($day == 'Mon')
					{
						$t = "08:00:00";
						$undertime = "17:00:00";
					}

					$hoursToAdd = 8;
					
					if($t1 > $t)
					{
						$undertime = $undertime;
					}
					else
					{
						
						$addtime = new DateTime($dt." ".$t1);
						$addtime->add(new DateInterval("PT{$hoursToAdd}H"));
						$undertime = $addtime->format('H:i:s');
						if($undertime < $minpm)
						{
							$undertime = $minpm;
						}
					}

					if($t2 < $undertime)
					{
						$start_date = new DateTime($dt." ".$t2);
						$since_start = $start_date->diff(new DateTime($dt." ".$undertime));
						$h = $since_start->h;
						$m = $since_start->i;

						$undertime = $since_start->h."h ".$since_start->i."m ";
						$totalinmin = ($since_start->h * 60) + $since_start->i;

						if($since_start->h == 0 && $since_start->i == 0)
						{
							$undertime = null;
							$totalinmin = 0;
						}

					}
					else
					{
						$undertime = null;
						$totalinmin = 0;
					}

					return $undertime."|".$totalinmin;
				}
				else
				{
					$undertime = null;
					$totalinmin = 0;
					return $undertime."|".$totalinmin;
				}
        break;
    }

	
}

function add_history_leave($userid,$leaveid,$tblid,$leavedate,$status)
{
	$history = new App\HistoryLeave;
	$history->user_id = $userid;
	$history->leave_id = $leaveid;
	$history->leave_tbl_id = $tblid;
	$history->leave_date = $leavedate;
	$history->leave_status = $status;
	$history->acted_by = Auth::user()->fname . ' ' . Auth::user()->lname;
	$history->save();

}

function add_to_leave($userid,$tblid,$todate,$status)
{
	$history = new App\HistoryTO;
	$history->user_id = $userid;
	$history->to_tbl_id = $tblid;
	$history->to_date = $todate;
	$history->to_status = $status;
	$history->acted_by = Auth::user()->fname . ' ' . Auth::user()->lname;
	$history->save();

}

function add_ot_leave($userid,$tblid,$otdate,$status)
{
	$history = new App\HistoryOT;
	$history->user_id = $userid;
	$history->ot_tbl_id = $tblid;
	$history->ot_date = $otdate;
	$history->ot_status = $status;
	$history->acted_by = Auth::user()->fname . ' ' . Auth::user()->lname;
	$history->save();

}

function checkIfIcos($userid)
{
	$user = App\User::where('id',$userid)->first();
        switch ($user['employment_id']) {
            case 5:
            case 6:
            case 7:
            case 8:
                	return true;
                break;
            
            default:
                	return false;
                break;
        }
}

function computeLate($dt,$d,$val,$ws = null)
{
	// $ws = showActiveWS();
    switch ($ws) {
        case 5:
        case 6:
        case 7:
        	return null;
        break;

        default:
        	if($val != null)
				{
					$tl = "08:30:00";

					if($d == 'Mon')
					{
						$tl = "08:00:00";
					}

					if($val > $tl)
					{
						$start_date = new DateTime($dt." ".$val);
						$since_start = $start_date->diff(new DateTime($dt." ".$tl));
						$totalinmin = ($since_start->h * 60) + $since_start->i;
						$tlates = $since_start->h."h ".$since_start->i."m<br/>|".$totalinmin;

						return $tlates;
					}
					else
					{
						return null;
					}
        break;
    }
	

		// $start_date = new DateTime($dt." ".$val);
		// $since_start = $start_date->diff(new DateTime($dt." ".$tl));
		// $totalinmin = ($since_start->h * 60) + $since_start->i;
		// $tlates = $since_start->h."h ".$since_start->i."m|".$totalinmin;

		// return $tlates;

		// $time1 = strtotime($tl);
		// $time2 = strtotime($val);
		// $difference = round(($time1 - $time2) / 60,2);

		// if($difference < 0)
		// {
		// 	return abs($difference);
		// }

	}
	
}

function checkDTRProcess($mon,$yr,$div)
    {
        $dtr = App\DTRProcessed::where('dtr_mon',$mon)->where('dtr_year',$yr)->where('dtr_division',$div)->first();

        if(isset($dtr))
        {
        	if($dtr['dtr_processed'] == 1)
	        {
	            return "YES";
	        }
	        else
	        {
	            return "NO";
        	}
        }
        else
        {
        	return "NO";
        }
        
    }
function checkPendingRequest($req,$mon,$year,$div)
    {
    	switch ($req) {
    		case 'Leave':
    				$request = App\Request_leave::where('leave_action_status','Pending')->whereMonth('leave_date',$mon)->whereYear('leave_date',$year)->where('user_div',$div)->count();
    			break;
    		case 'T.O':
    				$request = App\RequestTO::where('to_status','Pending')->whereMonth('to_date',$mon)->whereYear('to_date',$year)->where('division',$div)->count();
    			break;
    		default:
    				$request = App\RequestOT::where('ot_status','Pending')->whereMonth('ot_date',$mon)->whereYear('ot_date',$year)->where('division',$div)->count();
    			break;
    	}

    	if($request > 0)
    	{
    		return "Division has a pending ($request) for approval $req this ".date('F',mktime(0, 0, 0, $mon, 10))." ".$year."~false";
    	}
    	else
    	{
    		return "No Pending $req request for approval~true";
    	}

    	// echo $msg;
    }

function checkDTRStaff($mon,$yr,$div)
    {
        //GET ALL STAFF FIRST
        $staff = App\View_user::where('division',$div)->where('usertype','!=','Administrator')->orderBy('lname')->get();
        $staffarr = $staff->toArray();

        $msg = "";
        foreach ($staffarr as $staffs) 
        {
         	$mon = date('m',strtotime($mon));
	        $mon2 = date('F',mktime(0, 0, 0, $mon, 10));
	        $yr = $yr;
	        $date = $mon2 ."-" . $yr;
	        $month = ++$mon;

	        $tUndertime = null;

	        $tLates = null;

	        $totalAb = null;

	        $noentry = 0;

	        $noentry_msg = null;

	        $totanolates = 0;

	        $total = Carbon\Carbon::parse($date)->daysInMonth;
                      for($i = 1;$i <= $total;$i++)
                      {
                      		$dtr_date = date("Y-m-d",strtotime($yr.'-'.request()->mon.'-'.$i));

                            $dayDesc = weekDesc($dtr_date);

                            $dtr = getDTRemp($dtr_date,$staffs['id'],$staffs['employment_id'],$staffs['username']);


                            //UDERTIME
                            $under = null;
                            if(countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_option_id']))
                            {
                                $undertime = explode("|", countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_option_id']));
                                $under = $undertime[0];
                                $tUndertime += $undertime[1];
                            }

                            //LATES
                            $lates = null;
                            if(computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['dtr_option_id']))
                            {
                                $late = explode("|",computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['dtr_option_id']));
                                $lates = $late[0];
                                $tLates += $late[1];
                            }

                            switch ($dayDesc) {
                                case 'Sat':
                                case 'Sun':
                                    # code...
                                    break;
                                
                                default:
                                		if($dtr_date < date('Y-m-d'))
                                		{
                                			if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null && $dtr['dtr_request'] == null)
	                                        {
	                                            $noentry++;
	                                            $noentry_msg .= $dtr_date." | ";
	                                        }
                                		}
                                        
                                    break;
                            }
                            
                            
                             // checkEntry($dtr_date,);
                        
                      }
	        //DISPLAY LATES
	        $hourslate = floor($tLates / 60);
	        $minuteslate = $tLates % 60;

	        //DISPLAY UNDERTIME
	        $hoursunder = floor($tUndertime / 60);
	        $minutesunder = $tUndertime % 60;

	        //TOTAL DEDUCTION
	        //IF THERES AN ABSENT
	        $totalabsent = 0.000;
	        $hoursdeduc = 0.000;
	        $minutesdeduc = 0.000;
	        // $deducudhr = 0.000;
	        // $deducudmin = 0.000;

	        $totaldeduc = $tLates + $tUndertime;
	        $totaludlatededuc = 0.000;

	        if($totaldeduc == 60)
	        {
	        	$totaludlatededuc = 0.125;
	        }
	        elseif ($totaldeduc > 60) {
	        	//GET HOURS
	        	$hoursdeduc = floor($totaldeduc / 60);
	        	$hoursdeduc = $hoursdeduc * 0.125;

	        	$minutesdeduc = getLateDeduc($totaldeduc % 60);

	        	$totaludlatededuc = $hoursdeduc + $minutesdeduc;


	        }
	        elseif ($totaldeduc < 60) {
	        	$totaludlatededuc = getLateDeduc($totaldeduc);
	        	$totaludlatededuc = number_format((float)$totaludlatededuc, 3, '.', '');
	        }

	        if($noentry > 0)
	        {
	        	$lt = 8 * $noentry;
	        	$totalabsent = number_format((float)$noentry, 3, '.', '');
	        }

	        //CONVERT LATES/UNDERTIME HOURS
	        // $deduclatehr = $hourslate * 0.125;
	        // $deduclatehr = number_format((float)$deduclatehr, 3, '.', '');

	        // $deduclatemin  = getLateDeduc($minuteslate);
	        // $deduclatemin = number_format((float)$deduclatemin, 3, '.', '');

	        // //CONVERT LATES/UNDERTIME HOURS
	        // $deducudhr = $hoursunder * 0.125;
	        // $deducudhr = number_format((float)$deducudhr, 3, '.', '');

	        // $deducudmin  = getLateDeduc($minutesunder);
	        // $deducudmin = number_format((float)$deducudmin, 3, '.', '');
	        
	        $totaldeduction = $totalabsent + $totaludlatededuc;
	        $totaldeduction = number_format((float)$totaldeduction, 3, '.', '');

	        if($noentry > 0)
	        {
	        	$msg .= "<small><b>".$staffs['lname'].", ".$staffs['fname']. "</b> <span class='text-danger'>no entry</span> - ".$noentry_msg."<br/><b/>Total Lates : </b> ".$hourslate."h ".$minuteslate."m<br><b>Total Undertime : </b>".$hoursunder."h ".$minutesunder."m<br><b>Total Deduction : </b>".$totaldeduction."</small><br><br>";
	        }
         }

        echo $msg;
    }

    function getLateDeduc($min)
    {
    	switch (true) {
    		case ($min >= 1 && $min <= 4):
    			$x = ".00" . ($min * 2);
    			return $x;
    		case ($min >= 5 && $min <= 18):
    			$x = ".0" . (($min * 2) + 1);
    			return $x;
    		case ($min >= 19 && $min <= 30):
    			$x = ".0" . (($min * 2) + 2);
    			return $x;
    		case ($min >= 31 && $min <= 42):
    			$x = ".0" . (($min * 2) + 3);
    			return $x;
    		case ($min >= 31 && $min <= 42):
    			$x = ".0" . (($min * 2) + 4);
    			return $x;
    		case ($min >= 43 && $min <= 47):
    			$x = ".0" . (($min * 2) + 4);
    			return $x;
    		case ($min >= 48 && $min <= 54):
    			$x = "." . (($min * 2) + 4);
    			return $x;
    		case ($min >= 55 && $min <= 59):
    			$x = "." . (($min * 2) + 5);
    			return $x;
    		break;

    	}
    }

    function getLWP($userid,$mon,$yr)
    {
    	//$dtremp = collect(App\Employee_dtr::where('user_id',$userid)->whereMonth('fldEmpDTRdate',$mon)->whereYear('fldEmpDTRdate',$yr)->get());

		$user = App\User::where('id',$userid)->first();
    	
    	//COUNTER
    	$lwp = 0;

    	//GET ALL DAYS A MONTH
    	$month = date('F',mktime(0, 0, 0, $mon, 10));
    	$date = $month ."-" . $yr;

    	$tUndertime = null;
		$tLates = null;

		$totalnolates = 0;
		$totalhrs = 0;
		$totalhrsneeded = 0.0;

    	$total = Carbon\Carbon::parse($date)->daysInMonth;
        
        for($i = 1;$i <= $total;$i++)
        	{
        		$dtr_date = date("Y-m-d",strtotime($yr.'-'.request()->mon.'-'.$i));
				$dayDesc = weekDesc($dtr_date);
				$dtr = getDTRemp($dtr_date,$userid,1,$user['username']);

				

				//UDERTIME
                $under = null;
                if(countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_option_id']))
                {
                    $undertime = explode("|", countUndertime($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_option_id']));
                    $under = $undertime[0];
                    $tUndertime += $undertime[1];
                }

                //LATES
                $lates = null;
                if(computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['dtr_option_id']))
                {
                    $late = explode("|",computeLate($dtr_date,$dayDesc,$dtr['fldEmpDTRamIn'],$dtr['dtr_option_id']));
                    $lates = $late[0];
                    $tLates += $late[1];

                    if($tLates > 0)
                    {
                    	$totalnolates++;
                    }
                }

				switch ($dayDesc) {
                    case 'Sat':
                    case 'Sun':
                        # code...
                        break;
                    
                    default:
                      $totalhrsneeded += checkvaliddate($dtr_date);

                      if($dtr_date < date('Y-m-d'))
                      {
                       if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null && $dtr['dtr_request'] == null)
	                            {
	                                $lwp++;
	                            }
	                    $totalhrs += countTotalTimeWeek($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'],$dtr_date,$dayDesc,$dtr['dtr_option_id']);
                      }
                                        
                    break;
                }

               
        	}
        	//DISPLAY LATES
	        $hourslate = floor($tLates / 60);
	        $minuteslate = $tLates % 60;

	        //DISPLAY UNDERTIME
	        $hoursunder = floor($tUndertime / 60);
	        $minutesunder = $tUndertime % 60;

        	$totalabsent = 0.000;
	        $hoursdeduc = 0.000;
	        $minutesdeduc = 0.000;
	        // $deducudhr = 0.000;
	        // $deducudmin = 0.000;

	        $totaldeduc = $tLates + $tUndertime;
	        $totaludlatededuc = 0.000;

	        //LATE AND UNDERTIME
	        $lateunderhr = floor($totaldeduc / 60);
	        $lateundermin = $totaldeduc % 60;

	        if($totaldeduc == 60)
	        {
	        	$totaludlatededuc = 0.125;
	        }
	        elseif ($totaldeduc > 60) {
	        	//GET HOURS
	        	$hoursdeduc = floor($totaldeduc / 60);
	        	$hoursdeduc = $hoursdeduc * 0.125;

	        	$minutesdeduc = getLateDeduc($totaldeduc % 60);

	        	$totaludlatededuc = $hoursdeduc + $minutesdeduc;


	        }
	        elseif ($totaldeduc < 60) {
	        	$totaludlatededuc = getLateDeduc($totaldeduc);
	        	$totaludlatededuc = number_format((float)$totaludlatededuc, 3, '.', '');
	        }



        	// return number_format((float)$ctr, 2, '.', '');
        	return $lwp."|".$totaludlatededuc."|Total Lates : <b> ".$hourslate."h ".$minuteslate."m</b><br>Total Undertime : <b>".$hoursunder."h ".$minutesunder."m</b>|".$hourslate."|".$minuteslate."|".$hoursunder."|".$minutesunder."|".$totalnolates."|".$lateunderhr."|".$lateundermin ."|".$totalhrsneeded."|".$totalhrs;
    }

    function getLWPCount($i)
    {
    	$collection = collect(["0" => 1.250,"0.5" => 1.229,"1" => 1.208,"1.5" => 1.188,"2" => 1.167,"2.5" => 1.146,"3" => 1.125,"3.5" => 1.104,"4" => 1.083,"4.5" => 1.063,"5" => 1.042,"5.5" => 1.021,"6" => 1.000,"6.5" => 0.979,"7" => 0.958,"7.5" => 0.938,"8" => 0.917,"8.5" => 0.896,"9" => 0.875,"9.5" => 0.854,"10" => 0.833,"10.5" => 0.813,"11" => 0.792,"11.5" => 0.771,"12" => 0.750,"12.5" => 0.729,"13" => 0.708,"13.5" => 0.687,"14" => 0.667,"14.5" => 0.646,"15" => 0.625,"15.5" => 0.604,"16" => 0.583,"16.5" => 0.562,"17" => 0.542,"17.5" => 0.521,"18" => 0.500,"18.5" => 0.479,"19" => 0.458,"19.5" => 0.437,"20" => 0.417,"20.5" => 0.396,"21" => 0.375,"21.5" => 0.354,"22" => 0.333,"22.5" => 0.312,"23" => 0.292,"23.5" => 0.271,"24" => 0.250,"24.5" => 0.229,"25" => 0.208,"25.5" => 0.208,"26" => 0.167,"26.5" => 0.146,"27" => 0.125,"27.5" => 0.104,"28" => 0.083,"28.5" => 0.062,"29" => 0.042,"29.5" => 0.021,"30" => 0.000]);

    	return $collection->pull($i);
    	// return $collection->all();
    }

    function countLWP($userid,$mon,$yr)
    {
    	// return App\Request_leave::where('user_id',$userid)->whereNull('parent')->whereNull('leave_processed')->whereMonth('leave_date_from',$mon)->whereYear('leave_date_from',$yr)->get()->sum('leave_deduction');
    	return App\Request_leave::where('user_id',$userid)->whereIn('parent',['NO',null])->where('lwop','YES')->whereMonth('leave_date_from',$mon)->whereYear('leave_date_from',$yr)->get()->sum('leave_deduction');
    }

    function getLWPArray($i)
    {
    	$collection = collect(["0" => 1.250,"0.5" => 1.229,"1" => 1.208,"1.5" => 1.188,"2" => 1.167,"2.5" => 1.146,"3" => 1.125,"3.5" => 1.104,"4" => 1.083,"4.5" => 1.063,"5" => 1.042,"5.5" => 1.021,"6" => 1.000,"6.5" => 0.979,"7" => 0.958,"7.5" => 0.938,"8" => 0.917,"8.5" => 0.896,"9" => 0.875,"9.5" => 0.854,"10" => 0.833,"10.5" => 0.813,"11" => 0.792,"11.5" => 0.771,"12" => 0.750,"12.5" => 0.729,"13" => 0.708,"13.5" => 0.687,"14" => 0.667,"14.5" => 0.646,"15" => 0.625,"15.5" => 0.604,"16" => 0.583,"16.5" => 0.562,"17" => 0.542,"17.5" => 0.521,"18" => 0.500,"18.5" => 0.479,"19" => 0.458,"19.5" => 0.437,"20" => 0.417,"20.5" => 0.396,"21" => 0.375,"21.5" => 0.354,"22" => 0.333,"22.5" => 0.312,"23" => 0.292,"23.5" => 0.271,"24" => 0.250,"24.5" => 0.229,"25" => 0.208,"25.5" => 0.208,"26" => 0.167,"26.5" => 0.146,"27" => 0.125,"27.5" => 0.104,"28" => 0.083,"28.5" => 0.062,"29" => 0.042,"29.5" => 0.021,"30" => 0.000]);

    	return $collection->pull($i);
    	// return $collection->all();
    }

    function getLeaveInfo($id)
    {
    	$collection = collect(App\Leave_type::get());

    	foreach ($collection as $leave) {
    		if($leave->id == $id)
    		{
    			return $leave->leave_desc;
    		}
    	}
    }

    function checkIfHasReq($userid,$leaveid,$mon,$yr,$val)
    {
    	// $collection = collect(App\Request_leave::where('user_id',$userid)->where('leave_id',$leaveid)->whereMonth('leave_date',$mon)->whereYear('leave_date',$yr)->whereNull(''))
    }

    function getWarning($valid,$time,$ws = null)
    {
    	// return $ws;
    	
    	$alert = "";
    	if(isset($time))
    	{
    		return $time;
    	}
    	else
    	{	
    		if(isset($valid))
    		{
    			return $time;
    		}
    		else
    		{
    			//CHECK WORKSCHDULE
    			// $ws = showActiveWS();
    			switch ($ws) {
    				case 1:
    				case 2:
    				case 3:
    				case 4:
    						$alert = "<i class='fas fa-exclamation-circle text-danger'></i>";
    					break;
    			}
    		}
    		return $alert;
    	}


    }

    function checkifNull($time)
    {
    	if(isset($time))
    	{
    		return $time;
    	}
    	else
    	{
    		return null;
    	}
    }

    function countNoEntry($userid,$mon,$yr,$exe)
    {
    	if($exe == 1)
    	{
    		return null;
    	}
    	else
    	{
    		$collection = collect();

	    	$month = date('F',mktime(0, 0, 0, $mon, 10));
	    	$date = $month ."-" . $yr;

	    	$noentry = 0;

	    	$total = Carbon\Carbon::parse($date)->daysInMonth;
	    	for($i = 1;$i <= $total;$i++)
	        	{
	        		$dtr_date = date("Y-m-d",strtotime($yr.'-'.request()->mon.'-'.$i));

	        		$dtr = App\Employee_dtr::where('user_id',$userid)->where('fldEmpDTRdate',$dtr_date)->first();

	        		$dayDesc = weekDesc($dtr_date);

	        		switch ($dayDesc) {
	                    case 'Sat':
	                    case 'Sun':
	                        # code...
	                        break;
	                    
	                    default:
	                      if($dtr_date < date('Y-m-d'))
	                      {
	                       if(!isset($dtr))
				        		{
				        			$collection->push($dtr_date);
				        		}
	                      }
	                                        
	                    break;
	                }
	        	}

	        	$noentry = $collection->count();

		    	if($noentry > 0)
		    	{
		    		return "<span class='badge badge-danger' style='cursor:pointer' onclick='showNoEntry(\"".$collection->implode(',')."\")'>".$noentry."</span>";
		    	}
		    	else
		    	{
		    		return null;
		    	}
    	}
    	
    }


    function getPendingRequest($type,$userid,$mon,$yr)
    {
    	$collection = collect();
    	switch ($type) {
    		case 'leave':
    				$req = App\Request_leave::whereNotNull('parent')->where('user_id',$userid)->where('leave_action_status','Pending')->whereMonth('leave_date_from',$mon)->whereYear('leave_date_from',$yr)->get();
    				if(isset($req))
    				{
    					foreach ($req as $key => $value) {
    						if($value->parent == 'YES')
    						{
    							$collection->push("LEAVE - ".formatDateRead($value->leave_date_from) . " - " . formatDateRead($value->leave_date_to));
    						}
    						else
    						{
    							$collection->push("LEAVE - ".formatDateRead($value->leave_date_from));	
    						}
    						
    					}
    				}
    			break;
    		
    		case 't.o':
    				$req = App\RequestTO::where('parent','YES')->where('userid',$userid)->where('to_status','Pending')->whereMonth('to_date_from',$mon)->whereYear('to_date_from',$yr)->get();
    				if(isset($req))
    				{
    					foreach ($req as $key => $value) {

    						if($value->to_date_from == $value->to_date_to)
    							$to_date = formatDateRead($value->to_date_from);
    						else
    							$to_date = formatDateRead($value->to_date_from)." to ".formatDateRead($value->to_date_to);

    						$collection->push("T.O - ".formatDateRead($to_date));
    					}
    				}
    			break;

    		case 'o.t':
    				$req = App\RequestOT::where('userid',$userid)->where('ot_status','Pending')->whereMonth('ot_date',$mon)->whereYear('ot_date',$yr)->get();
    				if(isset($req))
    				{
    					foreach ($req as $key => $value) {
    						$collection->push("O.T - ".formatDateRead($value->ot_date));
    					}
    				}
    			break;
    	}

    	return $collection->all();
    }

    function formatDateRead($dt)
    {
    	return date('F d, Y',strtotime($dt));
    }
    

    function covertDurationNum($leaves,$dd)
    {
    	switch ($dd) {
                case 'wholeday':
                        $deduc = 1;
                    break;
                default:
                        $deduc = 0.5;
                    break;
            }
        $remaining = $leave - $deduc;

        if($remaining > 0)
        {
        	return "YES";
        }
        else
        {
        	return "NO";
        }
    }

function countDiffLeave($leaves,$dd)
{
    switch ($dd) {
                case 'wholeday':
                        $deduc = 1;
                    break;
                default:
                        $deduc = 0.5;
                    break;
            }

    $remain = $leaves - $deduc;
    if($remain < 0)
    {
        return 'YES';
    }
    else
    {
        return 'NO';
    }
}


function getLeave($userid,$deduc,$mon,$yr)
    {

        $collection = collect(App\Request_leave::where('user_id',$userid)->where('leave_deduction',$deduc)->whereMonth('leave_date_from',$mon)->whereYear('leave_date_from',$yr)->where('leave_action_status','!=','Pending')->get());

        return $collection->all();
    }

function getLeave2($userid,$mon,$yr)
    {

        $collection = collect($ctr = App\Request_leave::whereNotNull('parent')->where('user_id',$userid)->whereMonth('leave_date_from',$mon)->whereYear('leave_date_from',$yr)->where('leave_action_status','Processed')->get());

        return $collection->all();
    }


function getBalance($type,$leaveid,$userid,$mon,$yr)
    {

        if($type == 'begin')
        {
        	$sort = 'DESC';
        }
        else
        {
            $sort = 'ASC';
        }

        $bal = App\Employee_leave::where('user_id',$userid)->where('leave_id',$leaveid)->whereMonth('created_at',$mon)->whereYear('created_at',$yr)->orderBy('created_at',$sort)->first();

        // return $bal;

        if(isset($bal['leave_bal']))
        {
        	return $bal['leave_bal'];
        }
        else
        {
        	return null;
        }
        
    }

function getLastDTRProcess($userid)
{
	$dtr = App\DTRProcessed::where('userid',$userid)->orderBy('id','DESC')->first();
	return $dtr['dtr_mon'] . '-' . $dtr['dtr_year'];
}


function checkIfProcessed($userid,$mon,$yr)
{
	$dtr = App\DTRProcessed::where('userid',$userid)->where('dtr_mon',$mon)->where('dtr_year',$yr)->first();

	if($dtr)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checkvaliddate($dt)
{
	//CHECK IF HOLIDAY
	$holiday = App\Holiday::whereDate('holiday_date',$dt)->count();
	if($holiday == 0)
	{
		//CHECK IF SUSPENDED
		$suspension = App\Suspension::whereDate('fldSuspensionDate',$dt)->first();
		if(!isset($suspension))
			{
				return 8;
			}
			else
			{
				return $suspension['fldMinHrs'];
			}
	}
	else
	{
		return 0;
	}
	
}

function countDaysInOffice($userid,$mon,$yr)
{

	//LEAVES
	$leave_ctr = 0;
	$leave = App\Request_leave::where('user_id',$userid)->where('parent','YES')->whereMonth('leave_date_from',$mon)->whereYear('leave_date_from',$yr)->where('leave_action_status','Approved')->get();
	foreach ($leave as $key => $value) {
		$leave_ctr += $value->leave_deduction;
	}

	//T.O
	$to_ctr = 0;
	$tos = App\RequestTO::whereNull('parent')->where('userid',$userid)->whereMonth('to_date_from',$mon)->whereYear('to_date_from',$yr)->where('to_status','Approved')->get();
	foreach ($tos as $key => $value) {
		$to_ctr += $value->to_total_day;
	}

	return $leave_ctr + $to_ctr;
}


function countHolidays($mon,$yr)
{
	$holiday = App\Holiday::whereMonth('holiday_date',$mon)->whereYear('holiday_date',$yr)->count();
	return $holiday;
}

function countWorkSus($mon,$yr)
{
	$sus = App\Suspension::whereMonth('fldSuspensionDate',$mon)->whereYear('fldSuspensionDate',$yr)->get();
	$sus_ctr = 0;

	foreach ($sus as $key => $value) {

		if($value->fldSuspensionTime <= "12:30:00")
		{
			$sus_ctr += 1;
		}
		else
		{
			$sus_ctr += 0.5;
		}

	}

	return $sus_ctr;
}

function getLeaveInfo2($id)
{
	$lv = App\Leave_type::where('id',$id)->first();
	return $lv;
}

function LWOPAlert($code)
{
	$lv = App\Request_leave::where('parent_leave_code',$code)->whereNotIn('leave_action_status',['Processed'])->get();

	$ctr = count($lv);

	if($ctr > 1)
	{
		return "<sup><span class='badge badge-danger'>".--$ctr." days LWOP</span></sup>";
	}
	else
	{
		return "<sup><span class='badge badge-danger'>LWOP</span></sup>";
	}
}

function getDTROption()
{
	$ws = App\WorkSchedule::orderBy('id','desc')->first();
	return $ws->dtr_option_id;
}

function checkPendingProcess($empcode,$mon,$yr)
{
	if($mon == 1)
	{
		$prev_mon = 12;
		$prev_year = $yr - 1;
	}
	else
	{
		$prev_mon = $mon - 1;
		$prev_year = $yr;
	}
	
	$proc = App\DTRProcessed::where('empcode',$empcode)->where('dtr_mon',$prev_mon)->where('dtr_year',$prev_year)->whereNotNull('created_at')->count();

	

	if($proc > 0)
		$status = true;
	else
		$status = false;
	

	$data = [
				'status' => $status,
				'prev_mon' => date('M',mktime(0, 0, 0, $prev_mon, 10)),
				'prev_year' => $prev_year
			];
			
	return $data;
}


function getHoliday($dt)
{
	$holiday = App\Holiday::where('holiday_date',$dt)->first();
	return $holiday['holiday_desc'];
}

function getSuspension($dt)
{
	$suspension = App\Suspension::where('fldSuspensionDate',$dt)->first();
	return $suspension;
}

function getWeekMonth($type,$week,$mon,$year)
{
	$mon2 = date('F',mktime(0, 0, 0, $mon, 10));
	$date = $mon2  ."-" . $year;
    $total = Carbon\Carbon::parse($date)->daysInMonth;

	if($type == 'total')
	{
		$prevweek = 1;
		$week_num = 2;
		for($i = 1;$i <= $total;$i++)
		{
			$weeknum = weekOfMonth($year.'-'.$mon.'-'.$i) + 1;
            if($weeknum == $prevweek)
            	{
					//
            	}
            	else
            	{
                	$prevweek = $weeknum;
                	$week_num++;
            	}
		}

		return $week_num;
	}
	else
	{
		$days = collect();
		for($i = 1;$i <= $total;$i++)
		{
			$dt = $year.'-'.$mon.'-'.$i;
			$weeknum = weekOfMonth($dt) + 1;
            if($weeknum == $week)
			{	
				$dayDesc = weekDesc(date($dt));
				if($dayDesc == 'Sat' || $dayDesc == 'Sun')
                {

				}
				else
				{
					$days->push($i); 
				}
				
			}
		}
		return $days->all();
	}
}

function getWeekSchedStaff($userid,$dt)
{
	$sched = App\WeekSchedule::where('userid',$userid)->where('sched_date',$dt)->first();
	if(isset($sched))
	{
		if($sched['sched_status'] == 'WFH')
		{
			return '<span style="cursor:pointer" onclick="editSched('.$sched['id'].')">WFH</span>';
		}
		elseif($sched['sched_status'] == 'Pickup')
		{
			return '<span style="cursor:pointer" onclick="editSched('.$sched['id'].')">OFC/P</span>';
		}
		elseif($sched['sched_status'] == 'On-Trip')
		{
			return '<span style="cursor:pointer" onclick="editSched('.$sched['id'].')">On-Trip</span>';
		}
		elseif($sched['sched_status'] == 'On-Leave')
		{
			return '<span style="cursor:pointer" onclick="editSched('.$sched['id'].')">On-Leave</span>';
		}
		else
		{
			return '<span style="cursor:pointer" onclick="editSched('.$sched['id'].')">OFC</span>';
		}
		
	}
	else
	{
		return "-";
	}
}

function getWeekSchedStaff2($userid,$dt)
{
	$sched = App\WeekSchedule::where('userid',$userid)->where('sched_date',$dt)->first();
	if(isset($sched))
	{
		if($sched['sched_status'] == 'WFH')
		{
			return "WFH";
		}
		elseif($sched['sched_status'] == 'Pickup')
		{
			return "OFC/P";
		}
		else
		{
			return "OFC";
		}
	}
	else
	{
		return "-";
	}
}


function formatDTRrowPDF($mon,$yr,Object $emp)
{
	$row = "";

	$dtrcol = "<td align='center'></td><td align='center'></td><td align='center'></td><td align='center'><td align='center'>";

	$mon2 = date('F',mktime(0, 0, 0, $mon, 10));
    $date = $mon2  ."-" . $yr;

    $total = Carbon\Carbon::parse($date)->daysInMonth;
    $prevweek = 1;

    $week_num = 2;


    $row .= "<tr><td></td><td colspan='5' align='center'>  <b>WEEK 1 </b> </td></tr>";
    for($i = 1;$i <= $total;$i++)
                      {
                      	$remarks = "";
                        $weeknum = weekOfMonth($yr.'-'.$mon.'-'.$i) + 1;
                        if($weeknum == $prevweek)
                        {
                          
                        }
                        else
                        {
                          $prevweek = $weeknum;
                          $row .= "<tr><td></td><td colspan='5' align='center'> <b>WEEK $week_num </b> </td></tr>";
                          $week_num++;
                        }

                       $dtr_date = $yr.'-'.$mon.'-'.$i;

                       $dayDesc = weekDesc(date($yr.'-'.$mon.'-'.$i));

                       $dtr_date2 = date("Y-m-d",strtotime($dtr_date));

                      $dtr = getDTRemp($dtr_date,$emp['id'],$emp['employment_id'],$emp['username']);

                      if($dtr['id'])
                        {
                          $dtrid = $dtr['id'];
                        }
                        else
                        {
                          $dtrid = 0;
                        }
                       
                       $amIn = "";
                       $amOut = "";
                       $pmIn = "";
                       $pmOut = "";

                       $req = "";

                       if($dayDesc == 'Sat' || $dayDesc == 'Sun')
                       {  
                          if(isset($dtr))
                              {
                                $dtrcol = "<td align='center'></td><td align='center'></td><td align='center'></td><td align='center'></td><td align='center'></td>";
                              }
                       }
                       else
                       {

                          // if($dtr_date2 <= date('Y-m-d'))
                          //  {
                              if(isset($dtr))
                              {
                                 //CHECK IF HAS HOLIDAY
			                       if(!checkIfHoliday($dtr_date2))
			                          {


			                          	//CHECK IF SUSPENDED
			                          	// $remarks .= $dtr['remarks'];
			                          	if(checkIfSuspended($dtr_date2))
			                          	{
			                          		//GET SUSPENSION DETAILS
			                          		$suspension = getSuspension($dtr_date2);
			                          		$remarks .= $suspension['fldSuspensionRemarks'];
			                          	}


			                          	$amIn = "<td align='center'  style='cursor:pointer;font-size:15px;font-weight:bold' onclick='showEdit(".$dtrid.",".$emp['id'].",1,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRamIn'])."</div></td>";
				                           $amOut = "<td align='center' style='cursor:pointer;font-size:15px;font-weight:bold' onclick='showEdit(".$dtrid.",".$emp['id'].",2,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRamOut'])."</div></td>";
				                           $pmIn = "<td align='center' style='cursor:pointer;font-size:15px;font-weight:bold' onclick='showEdit(".$dtrid.",".$emp['id'].",3,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRpmIn'])."</div></td>";
				                           $pmOut = "<td align='center' style='cursor:pointer;font-size:15px;font-weight:bold' onclick='showEdit(".$dtrid.",".$emp['id'].",4,".$yr.",".$mon.",".$i.")'>".formatTime($dtr['fldEmpDTRpmOut'])."</div></td>";

				                           $remarksrow = "<td align='center' style=';font-size:13px;font-weight:bold'>".$remarks."</div></td>";
				                          
				                           	$dtrcol = $amIn."".$amOut."".$pmIn."".$pmOut."".$remarksrow;

				                           	//CHECK IF LEAVE
				                           	if(checkIfHasLeave($dtr_date2,$emp['id']))
				                          	{
				                          		//GET LEAVE DETAILS
				                          		$leave = getLeaveDetails($dtr_date2,$emp['id']);
				                          		switch($leave['leave_deduction_time'])
				                          		{
				                          			case 'wholeday':
				                          				$dtrcol = "<td align='center' colspan='5' class='text-primary'><b>".$leave['leave_desc']."</b></td>";
				                          			break;

				                          			case "AM":
				                          			break;

				                          			case "PM":
				                          			break;
				                          		}
				                          	}

			                            
			                          }
			                          else
			                          {
			                            $dtrcol = "<td align='center' colspan='5' class='text-success'><b>".getHoliday($dtr_date)."</b></td>";
			                          }

                                
                              }
                              else
                              {
                                $dtrcol = "<td align='center'></td><td align='center'></td><td align='center'></td><td align='center'><td align='center'>";
                              }
                              
                           // }
                           // else
                           // {
                           // 			$dtrcol = "<td align='center'></td><td align='center'></td><td align='center'></td><td align='center'><td align='center'>";

                           // }
                       }
                       
                       
                       $row .= "<tr><td><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td>".$dtrcol."</tr>";
                        
                      }

    return $row;
}

function getPendingDivision($type)
{
	switch ($type) {
		case 'leave':
				$req = App\Request_leave::where('user_div',Auth::user()->division)->where('parent','YES')->where('leave_action_status','Pending')->get();
			break;
		case 't.o':
				$req = App\Request_TO::where('division',Auth::user()->division)->where('to_status','Pending')->get();
			break;
		case 'o.t':
				$req = App\Request_OT::where('division',Auth::user()->division)->where('ot_status','Pending')->get();
			break;
	}

	return $req;
}

function checkIfAbsent($userid,$employment,$date)
{
	if($employment == 8)
	{
		$dtr = App\Employee_icos_dtr::where('user_id',$userid)->where('fldEmpDTRdate',$date)->first();  
	}
	else
	{
		$dtr = App\Employee_dtr::where('user_id',$userid)->where('fldEmpDTRdate',$date)->first();  
	}

	if(isset($dtr))
	{
		return false;
	}
	else
	{
		return true;
	}
}


function showDate(Array $dtr,$dt,$i,$dayDesc,$userid,$employmentid,$username,$lates,$under)
{
	// return "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='8' class='text-success'><b>".$dtr['dtr_option_id']."</b></td></tr>";

	// $dtr = getDTRemp($dt,$userid,$employmentid,$username);

	$remarks = "";
	$rows = "";
	if($dtr['id'])
    	{
        	$dtrid = $dtr['id'];
    	}
    	else
    	{
        	$dtrid = 0;
    	}


	if(!checkIfHoliday($dt))
	{

		//CHECK IF SUSPENDED
		if(checkIfSuspended($dt))
		{	
			$suspension = getSuspension($dt);
			$remarks = $suspension['fldSuspensionRemarks'];
		}
		
			//CHECK WORK SCHUDULE
			$ws = $dtr['dtr_option_id'];
			switch ($ws) {
				case 5:
				case 6:
				case 7:
					if(isset($dtr))
					{
							if($dtr['wfh'] == null)
							{
								if($dayDesc != 'Sat' && $dayDesc != 'Sun')
									
									if(checkIfHasLeave($dt,$userid))
											{
												//GET LEAVE DETAILS
												$leave = getLeaveDetails($dt,$userid);

												//LWOP
												$lwop = "";
												if($leave['lwop'] == 'YES')
													$lwop = " (LWOP)";

												switch($leave['leave_deduction_time'])
												{

													case "wholeday":
														$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>2".$leave['leave_desc']."$lwop</b></td><td></td></tr>";
													break;
		
													case "AM":
														$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
														if($lst)
														{
															if($lst['wfh'] != null || $lst['wfh'] != "")
															{
																$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td colspan='2' align='center'><b>WFH</b></td><td></td><td></td><td></td><td></td></tr>";
															}
															else
															{
																$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td colspan='2' align='center'>SKELETON WF<td><td></td><td></td><td></td><td></td></tr>";
															}
														}
														else
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td></td><td></td><td></td><td></td></tr>";
														}
														
													break;
		
													case "PM":

													$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();

													if($lst)
													{
														if($lst['wfh'] != null || $lst['wfh'] != "")
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'><b>WFH</b></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
														}
														else
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'>SKELETON WF<td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
														}
													}
													else
													{
														$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
													}
														
													break;
												}
											}
											else
											{
												$rows =  "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan = '7'>SKELETON WF</td><td>".$remarks."</td></tr>";
											}
								else
									$rows =  "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center'></td><td align='center' style='width:10%'></td><td align='center'></td><td align='center'>".$remarks."</td></tr>"; 

							}
							else
							{
								
								//CHECK IF DTR
								if(checkIfDTR('check',$dt,$userid,$employmentid) > 0)
								{
								  $dtrs = checkIfDTR('list',$dt,$userid,$employmentid);
								  
									//   $rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b></b></td><td>".$dtrs['dtr_to']."</td></tr>";
										//CHECK IF LEAVE
										if(checkIfHasLeave($dt,$userid))
										{
											//GET LEAVE DETAILS
											$leave = getLeaveDetails($dt,$userid);
											
											//LWOP
											$lwop = "";
											if($leave['lwop'] == 'YES')
												$lwop = " (LWOP)";

											switch($leave['leave_deduction_time'])
											{

												case "wholeday":
													$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td></tr>";
												break;
	
												case "AM":
														$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
														if($lst)
														{
															if($lst['wfh'] != null || $lst['wfh'] != "")
															{
																$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td colspan='2' align='center'><b>WFH</b></td><td></td><td></td><td></td><td></td></tr>";
															}
															else
															{
																$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td colspan='2' align='center'>SKELETON WF<td><td></td><td></td><td></td><td></td></tr>";
															}
														}
														else
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td></td><td></td><td></td><td></td></tr>";
														}
														
													break;
		
													case "PM":

													$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();

													if($lst)
													{
														if($lst['wfh'] != null || $lst['wfh'] != "")
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'><b>WFH</b></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td></td><td></td><td></td><td></td></tr>";
														}
														else
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'>SKELETON WF<td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td></td><td></td><td></td><td></td></tr>";
														}
													}
													else
													{
														$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td></td><td></td><td></td><td></td></tr>";
													}
														
													break;
											}
										}
									
									

									
								  
							 	}
								
							}
						}
					break;
				default:
				
					if(checkIfHasLeave($dt,$userid))
					{
						//GET LEAVE DETAILS
						$leave = getLeaveDetails($dt,$userid);

						//LWOP
						$lwop = "";
						if($leave['lwop'] == 'YES')
							$lwop = " (LWOP)";

						switch($leave['leave_deduction_time'])
						{

							case "wholeday":
								$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td></tr>";
							break;
		
							case "AM":
														$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
														if($lst)
														{
															if($lst['wfh'] != null || $lst['wfh'] != "")
															{
																$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td colspan='2' align='center'><b>WFH<b><td></td><td></td><td></td><td></td></tr>";
															}
															else
															{
																$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td colspan='2' align='center'>SKELETON WF<td><td></td><td></td><td></td><td></td></tr>";
															}
														}
														else
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td></td><td></td><td></td><td></td></tr>";
														}
														
													break;
		
													case "PM":

													$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();

													if($lst)
													{
														if($lst['wfh'] != null || $lst['wfh'] != "")
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'><b>WFH</b></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
														}
														else
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'>SKELETON WF<td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
														}
													}
													else
													{
														$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
													}
														
													break;
						}
					}
					else
					{
						$rows =  "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' style='width:10%'>".countTotalTime($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'],$dt,$dayDesc)."</td><td align='center' style='width:10%'>".$lates."</td><td align='center' style='width:10%'>".$under."</td><td align='center'>".$remarks."</td></tr>";
					}
					
				break;

			}
	}
	else
	{
		$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>".getHoliday($dt)."</b></td><td></td></tr>";
	}

	//IF WFH
	if($employmentid == 8)
		{
			$list = App\Employee_icos_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
		}
		else
		{
			$list = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
		}
	if($list)
	{
		if(!checkIfHasLeave($dt,$userid))
		{
			switch($list['wfh'])
			{
				case "Wholeday":
					$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>WFH</b></td><td></td></tr>";
				break;

				case "AM":

					$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>WFH</b></td><td align='center'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td></td><td></td><td></td><td></td><</tr>";
				break;

				case "PM":
					$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' colspan='2' class='text-success'><b>WFH</b></td><td></td><td></td><td></td><td></td><</tr>";
			   break;
			}
		}
		else
		{
			$leave = getLeaveDetails($dt,$userid);
			//LWOP
			$lwop = "";
			if($leave['lwop'] == 'YES')
				$lwop = " (LWOP)";

						switch($leave['leave_deduction_time'])
						{

							case "wholeday":
								$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td></tr>";
							break;
		
							case "AM":
														$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
														if($lst)
														{
															if($lst['wfh'] != null || $lst['wfh'] != "")
															{
																$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td colspan='2' align='center'><b>WFH<b><td></td><td></td><td></td><td></td></tr>";
															}
															else
															{
																$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td colspan='2' align='center'>SKELETON WF<td><td></td><td></td><td></td><td></td></tr>";
															}
														}
														else
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td></td><td></td><td></td><td></td></tr>";
														}
														
													break;
		
													case "PM":

													$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();

													if($lst)
													{
														if($lst['wfh'] != null || $lst['wfh'] != "")
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'><b>WFH</b></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
														}
														else
														{
															$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'>SKELETON WF</td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
														}
													}
													else
													{
														$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."$lwop</b></td><td></td><td></td><td></td><td></td></tr>";
													}
														
													break;
						}
		}
	}
	
	//IF T.O
	$tos = App\RequestTO::where('to_date_from',$dt)->where('empcode',$username)->whereIn('to_status',['Approved','Processed'])->first();

	if($tos)
	{
		//   $rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b></b></td><td>".$dtrs['dtr_to']."</td></tr>";
		switch($tos['to_deduction_time'])
		{
			case "wholeday":
				$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>On-Trip</b></td><td></td></tr>";
			break;

			case "AM":

				$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td></td><td></td><td></td><td></td><</tr>";
			break;

			case "PM":
				$lst = App\Employee_dtr::where('fldEmpCode',$username)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();

				if($lst)
				{
					if($lst['wfh'] != null || $lst['wfh'] != "")
					{
						$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'><b>WFH</b></td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td></td><td></td><td></td><td></td></tr>";
					}
					else
					{
						$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td colspan='2' align='center'>SKELETON WF</td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td></td><td></td><td></td><td></td></tr>";
					}
				}
				else
				{
					$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td>".formatTime($dtr['fldEmpDTRamIn'])."</td><td>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td></td><td></td><td></td><td></td></tr>";
				}
			break;

			default:
			   $rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>On-Trip</b></td><td></td></tr>";
		   break;
		}
	}

	return $rows;
}


function getSchedStaff($type,$userid,$dt)
{
	$weeksched = App\WeekSchedule::where('userid',$userid)->where('sched_date',$dt)->first();
	
	if(isset($weeksched))
	{
		if($type == 'val')
			return $weeksched['sched_status'];
		else
			if(isset($weeksched['id']))
				return $weeksched['id'];
			else
				return 0;
	}
	else
	{
		if($type == 'val')
			return "";
		else
			return 0;
	}
	
}



