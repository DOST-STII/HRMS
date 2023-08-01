<?php
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

function getLeaveDesc($id)
{
	$leave = App\Leave_type::where('id',$id)->first();
	return $leave['leave_desc'];
}

function formatRequestStatus($status)
{
	switch ($status) {
		case 'Pending':
				$cl = "badge-secondary";
			break;
		case 'Approved':
				$cl = "badge-success";
			break;
		case 'Disapproved':
				$cl = "badge-danger";
			break;
		case 'Cancelled':
		case 'Time Edited':
				$cl = "badge-warning";
			break;
		case 'OED Approved':
				$cl = "badge-info";
			break;
		default:
				$cl = "badge-success";
			break;
	}

	return "<span class='badge ".$cl."' style='font-size:15px'>".$status."</span>";
}

function checkRequest($userid = null)
{
	// switch ($type) {
	// 	case 'Vacation Leave':
	// 	case 'Sick Leave':
	// 	case 'Emergency Leave':
	// 	case 'Privilege Leave':
	// 	case 'Force Leave':
	// 		# code...
	// 		break;
	// 	case 'C.T.O':
	// 		# code...
	// 		break;
	// 	case 'T.O':
	// 		# code...
	// 		break;
	// 	case 'O.T':
	// 		# code...
	// 		break;
	// }

	if($userid == null)
	{
		$empid = Auth::user()->id;
	}
	else
	{
		$empid = $userid;
	}	
	

	$collection = collect([]);

	//CHECK LEAVE
	$leaves = App\Request_leave::where('user_id',$empid)->whereIn('parent',['YES','NO'])->whereIn('leave_action_status',['Pending','Approved'])->whereNull('process_code')->get();

	foreach ($leaves as $value) {
		# code...
		$m = date('m',strtotime($value->leave_date_from));
		$y = date('Y',strtotime($value->leave_date_from));
		
		if(!checkifProcesReq($empid,$m,$y))
		{
			$request_date = date('M d, Y',strtotime($value->leave_date_from)) ."-".date('M d, Y',strtotime($value->leave_date_to));

			if($value->leave_date_from == $value->leave_date_to)
			{
				$request_date = date('F d, Y',strtotime($value->leave_date_from));
			}

			$collection->push([
				'request_id' => $value->id,
				'request_desc' => getLeaveDesc($value->leave_id),
				'request_date' => $request_date,
				'request_lwop' => $value->lwop,
				'request_code' => $value->parent_leave,
				'request_action_status' => $value->leave_action_status
			]);
		}	
	}

	//CHECK T.O
	$tos = App\RequestTO::where('userid',$empid)->where('parent','YES')->where('to_status','!=','Cancelled')->whereNull('process_code')->get();

	foreach ($tos as $value) {
		# code...

		$m = date('m',strtotime($value->to_date_from));
		$y = date('Y',strtotime($value->to_date_from));

		if(!checkifProcesReq($empid,$m,$y))
		{
			if($value->to_date_from == $value->to_date_to)
			{
				$req_date = date('M, d Y',strtotime($value->to_date_from));
			}
			else
			{
				$req_date = date('M, d Y',strtotime($value->to_date_from))."-".date('M, d Y',strtotime($value->to_date_to));
			}
			$collection->push([
								'request_id' => $value->id,
								'request_desc' => "T.O",
								'request_date' => $req_date,
								'request_action_status' => $value->to_status
							]);
		}
	}

	//CHECK O.T
	$ots = App\RequestOT::where('userid',$empid)->where('ot_status','Pending')->get();

	foreach ($ots as $value) {
		# code...
		$collection->push([
							'request_id' => $value->id,
							'request_desc' => "O.T",
							'request_date' => date('F d, Y',strtotime($value->ot_date)),
							'request_action_status' => $value->ot_status
						  ]);
	}

	return $collection->toArray();
}

function checkIfHoliday($dt)
{
	$dt = App\Holiday::where('holiday_date',date('Y-m-d',strtotime($dt)))->where('suspension','!=','YES')->where('holidayTime',480)->count();

	if($dt > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checkIfHalfHoliday($dt)
{
	$dt = App\Holiday::where('holiday_date',date('Y-m-d',strtotime($dt)))->where('suspension','!=','YES')->where('holidayTime',240)->count();

	if($dt > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}


function checkIfSuspended($dt)
{
	$dt = App\Suspension::where('fldSuspensionDate',date('Y-m-d',strtotime($dt)))->count();

	if($dt > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checkIfHasLeave($dt,$userid)
{
	$dt = App\Request_leave::where('user_id',$userid)->where('leave_date_from',date('Y-m-d',strtotime($dt)))->whereIn('leave_action_status',['Approved','Processed'])->count();

	if($dt > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checkIfDTR($type,$dt,$userid,$employid,$empcode = null)
{
	if($employid == 8 || $employid == 5)
		{
			$dtr = App\Employee_icos_dtr::where('fldEmpCode',$empcode)->where('fldEmpDTRdate',$dt)->count();
			$list = App\Employee_icos_dtr::where('fldEmpCode',$empcode)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
		}
		else
		{
			$dtr = App\Employee_dtr::where('fldEmpCode',$empcode)->where('fldEmpDTRdate',$dt)->count();
			$list = App\Employee_dtr::where('fldEmpCode',$empcode)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
		}	

	if($type == 'check')
	{
		return $dtr;
	}
	else
	{
		return $list;
	}
	
}

function getLeaveDetails($dt,$userid)
{
	$leave = App\Request_leave::where('user_id',$userid)->where('leave_date_from',date('Y-m-d',strtotime($dt)))->where('leave_action_status','Approved')->first();
	return $leave;
}

function getLeaveDetails2($mon,$yr,$userid)
{
	$leave = App\Request_leave::where('user_id',$userid)->whereYear('leave_date_from',$yr)->whereMonth('leave_date_from',$mon)->where('leave_action_status','Approved')->get();
	return $leave;
}

function getLeaveCount($type,$t,$dt,$userid)
{
	if($type == "count")
	{
		$leave = App\Request_leave::where('user_id',$userid)->where('leave_date_from',date('Y-m-d',strtotime($dt)))->where('leave_action_status','Approved')->count();
		return $leave;
	}
	else
	{
		$leave = App\Request_leave::where('user_id',$userid)->where('leave_date_from',date('Y-m-d',strtotime($dt)))->where("leave_deduction_time",$t)->where('leave_action_status','Approved')->first();
		return $leave;
	}
	
}

function randomCode($ctr)
{
	return Str::random($ctr);
}

function getDTRApprovedBy($id)
{
	$dt = App\Request_leave::where('id',$id)->first();
	return $dt['leave_action_by'];
}

function getCancelled($type,$div,$userid = null)
{
	switch($type)
	{
		case "leave":
			if($userid)
				{
					$req = App\Request_leave::where('user_id',$userid)->where('leave_action_status','Cancelled')->whereNotNull('parent_leave')->get();
					return $req;
				}
				elseif($div == 'All')
				{
					$req = App\Request_leave::where('leave_action_status','Cancelled')->whereNotNull('parent_leave')->get();
					return $req;
				}
				else
				{
					$req = App\Request_leave::where('leave_action_status','Cancelled')->whereNotNull('parent_leave')->get();
					return $req;
				}
		break;

		case "to":
			if($userid)
				{
					$req = App\RequestTO::where('userid',$userid)->where('to_status','Cancelled')->where('parent','YES')->get();
					return $req;
				}
				else
				{
					$req = App\RequestTO::where('to_status','Cancelled')->where('parent','YES')->get();
					return $req;
				}
		break;
	}
	
}

function getDisapproved($div,$userid = null)
{
	if($userid)
				{
					$req = App\Request_leave::where('user_id',$userid)->where('leave_action_status','Disapproved')->whereNotNull('parent_leave')->get();
					return $req;
				}
				elseif($div == 'All')
				{
					$req = App\Request_leave::where('leave_action_status','Disapproved')->whereNotNull('parent_leave')->get();
					return $req;
				}
				else
				{
					$req = App\Request_leave::where('leave_action_status','Disapproved')->whereNotNull('parent_leave')->get();
					return $req;
				}
	
}


function getApproved($type,$div,$userid = null)
{
	switch($type)
	{
		case "leave":
			if($userid)
				{
					$req = App\Request_leave::where('user_id',$userid)->where('leave_action_status','Approved')->whereNotNull('parent_leave')->whereNull('process_code')->get();
					return $req;
				}
			elseif($div == 'All')
				{
					$req = App\Request_leave::where('leave_action_status','Approved')->whereNotNull('parent_leave')->whereNull('process_code')->get();
					return $req;
				}
			else
				{
					$req = App\Request_leave::where('leave_action_status','Approved')->whereNotNull('parent_leave')->whereNull('process_code')->get();
					return $req;
				}
		break;

		case "to":
			if($userid)
				{
					$req = App\RequestTO::where('userid',$userid)->where('to_status','Approved')->where('parent','YES')->whereNull('process_code')->get();
					return $req;
				}
				elseif($div == 'All')
				{
					$req = App\RequestTO::where('to_status','Approved')->where('parent','YES')->whereNull('process_code')->get();
					return $req;
				}
				else
				{
					$req = App\RequestTO::where('to_status','Approved')->where('parent','YES')->whereNull('process_code')->get();
					return $req;
				}
		break;

		case "ot":
			if($userid)
				{
					$req = App\RequestOT::where('userid',$userid)->where('ot_status','Approved')->whereNull('process_code')->get();
					return $req;
				}
				elseif($div == 'All')
				{
					$req = App\RequestOT::where('ot_status','Approved')->whereNull('process_code')->get();
					return $req;
				}
				else
				{
					$req = App\RequestOT::where('ot_status','Approved')->whereNull('process_code')->get();
					return $req;
				}
		break;
	}
	
}

function getProcessed($type,$div,$userid = null)
{
	switch($type)
	{
		case "leave":
			if($userid)
				{
					$req = App\Request_leave::where('user_id',$userid)->whereNotNull('parent_leave')->whereNotNull('process_code')->get();
					return $req;
				}
				elseif($div == 'All')
				{
					$req = App\Request_leave::whereNotNull('parent_leave')->whereNotNull('process_code')->get();
					return $req;
				}
				else
				{
					$req = App\Request_leave::whereNotNull('parent_leave')->whereNotNull('process_code')->get();
					return $req;
				}
		break;

		case "to":
			if($userid)
				{
					$req = App\RequestTO::where('userid',$userid)->where('to_status','Approved')->where('parent','YES')->whereNotNull('process_code')->get();
					return $req;
				}
				elseif($div == 'All')
				{
					$req = App\RequestTO::where('to_status','Approved')->where('parent','YES')->whereNotNull('process_code')->get();
					return $req;
				}
				else
				{
					$req = App\RequestTO::where('to_status','Approved')->where('parent','YES')->whereNotNull('process_code')->get();
					return $req;
				}
		break;

		case "ot":
			if($userid)
				{
					$req = App\RequestOT::where('userid',$userid)->where('ot_status','Approved')->whereNotNull('process_code')->get();
					return $req;
				}
				elseif($div == 'All')
				{
					$req = App\RequestOT::where('ot_status','Approved')->whereNotNull('process_code')->get();
					return $req;
				}
				else
				{
					$req = App\RequestOT::where('ot_status','Approved')->whereNotNull('process_code')->get();
					return $req;
				}
		break;
	}
	
}

function showDateIcos(Array $dtr,$dt,$i,$dayDesc,$userid,$employmentid,$username,$lates,$under)
{

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
		$remarks = "";
		if(checkIfSuspended($dt))
		{	
			$suspension = getSuspension($dt);
			$remarks = $suspension['fldSuspensionRemarks'];
		}
		
			//CHECK WORK SCHUDULE
			$ws = $dtr['dtr_option_id'];
			if(isset($dtr))
					{
							if($dtr['wfh'] == null)
							{

								if($remarks == "")
								{
									$remarks = $dtr['dtr_remarks']; 
								}

								// if($dayDesc != 'Sat' && $dayDesc != 'Sun')
								// {
								// 	$rows =  "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' style='width:10%'>".countTotalTime($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'],$dt,$dayDesc)."</td><td align='center' style='width:10%'>".$lates."</td><td align='center' style='width:10%'>".$under."</td><td align='center'>".$remarks."</td></tr>";
								// }
								// else
								// {
								// 	$rows =  "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center'></td><td align='center' style='width:10%'></td><td align='center'>".$remarks."</td></tr>"; 
								// }
								$rows =  "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' style='width:10%'>".countTotalTime($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'],$dt,$dayDesc)."</td><td align='center' style='width:10%'>".$lates."</td><td align='center' style='width:10%'>".$under."</td><td align='center'>".$remarks."</td></tr>";

							}
							else
							{
										switch($dtr['wfh'])
										{
											case "Wholeday":
												$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>WFH</b></td><td></td></tr>";
											break;

											case "PM":

												$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>WFH</b></td><td align='center'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td></td><td></td><td></td><td></td><</tr>";
											break;

											case "AM":
												$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' colspan='2' class='text-success'><b>WFH</b></td><td></td><td></td><td></td><td></td><</tr>";
											break;
										}
								  
							 	}
					}
	}
	else
	{
		$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>".getHoliday($dt)."</b></td><td></td></tr>";
	}

	

	return $rows;
}

function formatDTRrow($mon,$yr,Object $emp)
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
                        $weeknum = weekOfMonth($yr.'-'.$mon.'-'.$i);
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

                       if($dayDesc != '')
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

				                          		//LWOP
				                          		$lwop = "";
												//if($leave['lwop'] == 'YES')
													// $lwop = " <span class='text-danger'>(LWOP)</span>";

				                          		switch($leave['leave_deduction_time'])
				                          		{
				                          			case 'wholeday':
				                          				$dtrcol = "<td align='center' colspan='5' class='text-primary'><b>".$leave['leave_desc']."$lwop</b></td>";
				                          			break;

				                          			case "PM":
														$dtrcol = $amIn."".$amOut."<td align='center' colspan='2' class='text-primary'><b>".$leave['leave_desc']."$lwop</b></td><td></td>";
				                          			break;

				                          			case "AM":
														$dtrcol = "<td align='center' colspan='2' class='text-primary'><b>".$leave['leave_desc']."$lwop</b></td>".$pmIn."".$pmOut."<td></td>";
				                          			break;

													default:
													  $dtrcol = "<td align='center' colspan='5' class='text-primary'><b>".$leave['leave_desc']."$lwop</b></td>";
												  	break;
				                          		}
				                          	}

											//CHECK IF WFH
											if(checkIfDTR('check',$dtr_date2,$emp['id'],$emp['employment_id'],$emp['username']) > 0)
				                          	{
												$wfh = checkIfDTR('list',$dtr_date2,$emp['id'],$emp['employment_id'],$emp['username']);
												switch($wfh['wfh'])
				                          		{
				                          			case 'Wholeday':
				                          				$dtrcol = "<td align='center' colspan='5' class='text-success'><b>WFH</b></td>";
				                          			break;

				                          			case "AM":
														$dtrcol = $amIn."".$amOut."<td align='center' colspan='2' class='text-success'><b>WFH</b></td><td></td>";
				                          			break;

				                          			case "PM":
														$dtrcol = "<td align='center' colspan='2' class='text-success'><b>WFH</b></td>".$pmIn."".$pmOut."<td></td>";
				                          			break;
				                          		}
											}
											
											//CHECK IF T.O
											if(checkIfDTR('check',$dtr_date2,$emp['id'],$emp['employment_id'],$emp['username']) > 0)
				                          	{
												$wfh = checkIfDTR('list',$dtr_date2,$emp['id'],$emp['employment_id'],$emp['username']);
												switch($wfh['dtr_to'])
				                          		{
				                          			case 'Wholeday':
				                          				$dtrcol = "<td align='center' colspan='5' class='text-success'><b>On-Trip</b></td>";
				                          			break;

				                          			case "AM":
														$dtrcol = $amIn."".$amOut."<td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td></td>";
				                          			break;

				                          			case "PM":
														$dtrcol = "<td align='center' colspan='2' class='text-success'><b>On-Trip</b></td>".$pmIn."".$pmOut."<td></td>";
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


function plotDate(Array $dtr,$i,$dayDesc,$dt,$userid)
{
	//return $dt."<br/>";
	$totalhrs = 0;
	$lates = 0;
	$undertime = 0;	
	$leavesctr = 0;
	$excess = 0;
	$deficit = 0;
	$remarks = "";
	$required_hrs = 480;
	$lates_ctr = 0;

	$sus = App\Suspension::where('fldSuspensionDate',$dt)->first();

	if(!checkIfHoliday($dt))
	{
		if(checkIfHasLeave($dt,$userid))
		{
			//GET LEAVE DETAILS
			$leave = getLeaveDetails($dt,$userid);

			//IF LATE
			$lates = getEmpLate($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRpmIn']);

			//IF UNDERTIME
			$undertime = getEmpUnder($dayDesc,$dt,$dtr['fldEmpDTRpmOut'],$dtr['fldEmpDTRamIn']);
			
			if($dayDesc != 'Sat' && $dayDesc != 'Sun')
			{
				switch($leave['leave_deduction_time'])
				{
					case "wholeday":
						$lates = 0;
						$undertime = 0; 
						$leavesctr = 480;
						$required_hrs = $required_hrs - 480;

						//IF CTO
						if($leave['leave_id'] == 5 || $leave['leave_id'] == 16)
						{
							$leavesctr = 0;
							$totalhrs = 480;
							$leavesctr = 0;
							

							// $rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='4' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'>".readableTime($totalhrs)."</td><td></td><td></td><td></td><td>E : $excess D : $deficit R : $required_hrs</td></tr>";

							$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='4' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'>".readableTime($totalhrs)."</td><td></td><td></td><td></td><td>$remarks</td></tr>";
						}
						else
						{
							// $rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='4' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'></td><td></td><td></td><td></td><td>E : $excess D : $deficit L : $leavesctr</td></tr>";

							$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='4' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'></td><td></td><td></td><td></td><td>$remarks</td></tr>";
						}
						

						//$totalhrs = 480;
						//$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='4' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'></td><td></td><td></td><td></td><td></td></tr>";
						//$addmin = 480;
					break;

					case "AM":
						$lst = App\Employee_dtr::where('user_id',$userid)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
						
						$leavesctr = 240;
						$needed = 240;
						$deficit = 0;


						if($leave['leave_id'] == 5 || $leave['leave_id'] == 16)
							{
								//
							}
							else
							{
								$required_hrs = $required_hrs - 240;
							}
						
						if(getLeaveCount("count","PM",$dt,$userid) > 1)
						{
							$needed = 0;

							//GET LEAVE DETAIL
							$leave2 = getLeaveCount("list","PM",$dt,$userid);

							$lates = 0;

							//IF CTO/WFH
							if($leave2['leave_id'] == 5)
							{
								$totalhrs = $totalhrs + 240;
								$leavesctr = 240;
								
							}
							elseif($leave2['leave_id'] == 16)
							{
								$totalhrs = $totalhrs + 240;
								$leavesctr = 240;
								$needed += 240;
							}
							else
							{
								$required_hrs = $required_hrs - 240;
								$leavesctr += 240;
								//$required_hrs = 0;
							}

							// if($leave['leave_id'] == 17)
							// {
							// 	$totalhrs = 0;
							// 	$needed = 0;
							// 	$leavesctr = 0;
							// }



							//IF DEFICIT
							$testdefict = $needed - ($totalhrs + $lates);
							if($testdefict > 0)
								$deficit = $testdefict;

							
							//IF EXCESS
							$testexcess = $totalhrs - $needed;
							if($testexcess > 0)
								$excess = $testexcess;
							

							// $rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td colspan='2' align='center'><b>".$leave2['leave_desc']."</b></td><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>E : $excess D : $deficit ".$leave['leave_id']."</td></tr>";

							$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td colspan='2' align='center'><b>".$leave2['leave_desc']."</b></td><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>$remarks</td></tr>";

						}
						else
						{
							
							if($lst)
								{
									//IF LATE
									$lates = getEmpLate($dayDesc,$dt,$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],'PM');
									if($lates > 0)
									{
										//CHECK HALFDAY HOLIDAY
										if(checkIfHalfHoliday($dt))
										{
											//
										}
										else
										{
											$lates_ctr++;
										}
									}
										
									

									$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],'AM',null,2);

									//IF CTO/WFH
									if($leave['leave_id'] == 5)
									{
										$totalhrs = $totalhrs + 240;
										$leavesctr = 0;
										$needed = 480;
									}
		
									if($leave['leave_id'] == 16)
									{
										$totalhrs = $totalhrs + 240;
										$leavesctr = 0;
										$needed = 480;
									}

									if($sus)
										{
											$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';
											$leavesctr += $sus['required_hrs'] * 60;
											$totalhrs = $sus['fldMinHrs'] * 60;

											if($sus['suspension_time_desc'] == 'AM')
											{

											}
											else
											{
												$lates = 0;
												$undertime = 0;
											}
										}
										else
										{
											
										}

									//IF DEFICIT
									$testdefict = $needed - ($totalhrs + $lates);
									if($testdefict > 0)
										$deficit = $testdefict;

									//IF EXCESS
									$testexcess = $totalhrs - $needed;
									if($testexcess > 0)
										$excess = $testexcess;

									
									if($sus)
									{
										$leavesctr = ($sus['required_hrs'] * 60) + 60;
										//$required_hrs = $required_hrs - ($sus['required_hrs'] * 60);
									}


									// $rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>E : $excess D : $deficit : L: $leavesctr</td></tr>";

									$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>$remarks</td></tr>";
								}
								else
								{
									

									if($sus)
									{
										$leavesctr += 240;

										//$leavesctr = 0;
										
										$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';
										//$leavesctr = $sus['required_hrs'] * 60;
										//$required_hrs = $required_hrs - ($sus['required_hrs'] * 60);
									}

									if($leave['leave_id'] == 16)
									{
										$totalhrs += 240;
										//$leavesctr = 240;
									}
									

									if(checkIfHalfHoliday($dt))
									{
										
										if($leave['leave_id'] == 5)
											$totalhrs += 240;
										else
											$leavesctr += 240;

										$rows = "<tr style='vertical-align: baseline;'><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center' colspan='2' >".getHoliday($dt)."<td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td></td><td>$remarks</td></tr>";
									}
									else
									{
										//$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'></td><td align='center'></td><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td></td><td>E : $excess D : $deficit L: $leavesctr</td></tr>";

										$rows = "<tr style='vertical-align: baseline;'><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td><td align='center'></td><td align='center'></td><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td></td><td>$remarks</td></tr>";
									}

									
								}
						}
								
						break;

					case "PM":

						$lst = App\Employee_dtr::where('user_id',$userid)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();
						$leavesctr = 240;
						$needed = 240;


						//CHECK IF COMBINATION NG LEAVE
						if(getLeaveCount("count","AM",$dt,$userid) > 1)
						{
							$needed = 0;
							$leavesctr += 240;
							//GET LEAVE DETAIL
							$leave2 = getLeaveCount("list","AM",$dt,$userid);

							$lates = 0;

							//IF CTO/WFH
							if($leave['leave_id'] == 5)
							{
								$totalhrs = $totalhrs + 240;
								$leavesctr = 240;
							}
							elseif($leave['leave_id'] == 16)
							{
								$totalhrs = $totalhrs + 240;
								$leavesctr = 240;
								$needed += 240;
							}
							else
							{
								$required_hrs = $required_hrs - 240;
								//$required_hrs = 0;
							}

							//IF UNDERTIME
							$undertime = 0;

							//IF DEFICIT
							$testdefict = $needed - ($totalhrs + $lates);
							if($testdefict > 0)
								$deficit = $testdefict;

							
							//IF EXCESS
							$testexcess = $totalhrs - $needed;
							if($testexcess > 0)
								$excess = $testexcess;

							// $rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td colspan='2' align='center'><b>".$leave2['leave_desc']."</b></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td ><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>E : $excess D : $deficit</td></tr>";

							$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td colspan='2' align='center'><b>".$leave2['leave_desc']."</b></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td ><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>$remarks</td></tr>";

						}
						else
						{
							if($lst)
							{
								if($dtr['fldEmpDTRamIn'] < '07:30:00')
									$amin = "07:30:00";
								else
									$amin = $dtr['fldEmpDTRamIn'];

								
								//IF LATE
								$lates = getEmpLate($dayDesc,$dt,$amin,"12:00:00");
								if($lates > 0)
									$lates_ctr++;


								$totalhrs = totalHrs($dayDesc,$dt,$amin,$dtr['fldEmpDTRamOut'],'PM',null,2);
								$needed = 240;
								
								//IF UNDERTIME
								//$undertime = 240 - $totalhrs;
								$undertime = getEmpUnderAM($dtr['fldEmpDTRamOut']);
							
								// if($undertime > 0)
								// 	$undertime = 0;

								//IF CTO/WFH
								if($leave['leave_id'] == 5)
								{
									$totalhrs = $totalhrs + 240;
									$leavesctr = 0;
									$needed = 480;
								}

								if($leave['leave_id'] == 16)
								{
									$totalhrs = $totalhrs + 240;
									$leavesctr = 0;
									$needed = 480;
								}

								//IF DEFICIT
								$testdefict = $needed - ($totalhrs + $lates);
								if($testdefict > 0)
									$deficit = $testdefict;

								
								//IF EXCESS
								$testexcess = $totalhrs - $needed;
								if($testexcess > 0)
									$excess = $testexcess;

								// $rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td ><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>E : $excess D : $deficit T : $totalhrs</td></tr>";

								$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td ><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>$remarks</td></tr>";
							}
							else
							{
								// $rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center'></td><td align='center'></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td ><td align='center'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td></td><td>E : $excess D : $deficit</td></tr>";

								$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center'></td><td align='center'></td><td align='center' colspan='2' class='text-success'><b>".$leave['leave_desc']."</b></td ><td align='center'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td></td><td>$remarks</td></tr>";
							}
						}
						
												
						break;
				}
			}
			else
			{
				//$leavesctr = 480;

				// $rows =  "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td></td><td align='center'>$leavesctr</td></tr>";

				$rows =  "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td></td><td align='center'>$remarks</td></tr>";
			}
			
		}
		else
		{
				//TOTAL HOURS
				
				$remarks = $dtr['dtr_remarks'];
				$requiredHrs = 480;
				$leavesctr = 0;
				$deficit = 0;

				
				$dtrcase = 0;
				//IF WALANG LEAVE PERO DI COMPLETE ANG DTR
				if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] != null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null)
				{

					$dtrcase = 1;
					//IF LATE
					$lates = getEmpLate($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRpmIn']);
					if($lates > 0)
					{
						//CHECK HALFDAY HOLIDAY
						if(checkIfHalfHoliday($dt))
						{
							//
						}
						else
						{
							$lates_ctr++;
						}
					}

					//IF UNDERTIME
					//$undertime = 240;
					if($dtr['fldEmpDTRamOut'] >= "12:00:00")
						$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],"12:00:00",null,2,$userid);
							
					if($dtr['fldEmpDTRamOut'] < "12:00:00")
						{
							$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],null,2);
								
						}
						
						
					if($sus)
					{
						$leavesctr = $sus['required_hrs'] * 60;
						$required_hrs = $required_hrs - $leavesctr;
						$undertime  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
						$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';
					}
					else
					{
						$undertime1  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
						$undertime2  = ($requiredHrs - $totalhrs);

						if($userid == 233)
							$undertime = $undertime1 + 180;
						else
							$undertime = $undertime1 + 210;

						$deficit = $undertime2 - $undertime;
						
						$leavesctr = 0;
					}

					

					

					//$leavesctr = 240;

					$excess = $totalhrs - $leavesctr;

					if($deficit > 0)
					{
						$excess = 0;
					}

				
					if($sus)
					{
						$leavesctr = ($sus['required_hrs'] * 60) + 60;
					}
				}

				if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] != null && $dtr['fldEmpDTRpmOut'] != null)
				{
					$dtrcase = 2;
					//IF LATE
					//$lates = getEmpLatePM($dt,"13:00:00");

					$tos = App\RequestTO::where('to_date_from',$dt)->where('userid',$userid)->where('to_deduction_time','AM')->whereIn('to_status',['Approved','Processed'])->first();
					if(!$tos)
					{
						$lates = getEmpLatePM($dt,$dtr['fldEmpDTRpmIn']);
						if($lates > 0)
							$lates_ctr++;
					}

					$totalhrs = totalHrs($dayDesc,$dt,"13:00:00",$dtr['fldEmpDTRpmOut'],null,2);

					//$undertime = 480 - $totalhrs;
					$undertime = 0;

					//$leavesctr = 240;
					$excess = $totalhrs - 240;


					if($sus)
					{
						$leavesctr = $sus['required_hrs'] * 60;
						$required_hrs = $required_hrs - $leavesctr;
						$undertime  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
						$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';
					}
					else
					{
						$undertime1  = getEmpUnderAM($dtr['fldEmpDTRpmOut']);
						$undertime2  = ($requiredHrs - $totalhrs);
						$undertime = $undertime1 + $undertime2;
						
						$leavesctr = 0;
					}
					if($excess < 0)
					{
						$deficit = abs($excess) - ($undertime + $lates);
						$excess = 0;
					}
					
				}

				if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] != null && $dtr['fldEmpDTRpmIn'] != null && $dtr['fldEmpDTRpmOut'] != null)
				{
					$dtrcase = 3;
					//FOR MS ANNA
					if($dtr['fldEmpDTRamIn'] < "07:00:00" && $userid == 233)
					{
						$pmOUT = $dtr['fldEmpDTRpmOut'];
						$amIN = "07:00:00";
					}
					else
					{
						$pmOUT = $dtr['fldEmpDTRpmOut'];
						$amIN = $dtr['fldEmpDTRamIn'];
					}

					$leavesctr = 0;
					//IF LATE
					$lates = getEmpLate($dayDesc,$dt,$amIN,$dtr['fldEmpDTRpmIn']);
					if($lates > 0)
						$lates_ctr++;

					//IF LATE SA HAPON
					if($dtr['fldEmpDTRpmIn'] > "13:00:00")
					{
						$lates2 = getEmpLatePM($dt,$dtr['fldEmpDTRpmIn']);
						if($lates2 > 0)
							$lates_ctr++;

						$lates += getEmpLatePM($dt,$dtr['fldEmpDTRpmIn']);
						$totalhrs = totalHrs($dayDesc,$dt,$amIN,"12:00:00",null,2);
						$totalhrs += totalHrs($dayDesc,$dt,$dtr['fldEmpDTRpmIn'],$pmOUT,null,2);
					}
					else
					{
						$totalhrs = totalHrs($dayDesc,$dt,$amIN,$pmOUT,null,1);
					}
						

					//IF UNDERTIME
					$undertime = getEmpUnder($dayDesc,$dt,$pmOUT);

					if($userid == 233)
					{
						$lates = getEmpLateMsAnna($dayDesc,$dt,$amIN,$dtr['fldEmpDTRpmIn']);
						$undertime = getEmpUnderMsAnna($dayDesc,$dt,$pmOUT);
						$totalhrs = totalHrsMsAnna($dayDesc,$dt,$amIN,$pmOUT,null,1);
					}

					$excess = $totalhrs - 480;

					if($sus)
					{
						$leavesctr = $sus['required_hrs'] * 60;
						$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],"12:00:00",null,2);
						$excess = $totalhrs - $leavesctr;
						$required_hrs = $required_hrs - $leavesctr;

						$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';

						if($sus['suspension_time_desc'] == 'PM')
						{
							$undertime  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
						}
						else
						{
							$lates = 0;
						}
					}
					else
					{
						//$undertime = $totalhrs - 480;
					}

					if($excess < 0)
					{
						$deficit = abs($excess) - ($undertime + $lates);
						$excess = 0;
					}
						
						
					if($sus)
					{
						$leavesctr = ($sus['required_hrs'] * 60) + 60;
					}
				}

				if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] != null)
				{
					$dtrcase = 4;
					//FOR MS ANNA
					if($dtr['fldEmpDTRamIn'] < "07:00:00" && $userid == 233)
					{
						$pmOUT = $dtr['fldEmpDTRpmOut'];
						$amIN = "07:00:00";
					}
					else
					{
						$pmOUT = $dtr['fldEmpDTRpmOut'];
						$amIN = $dtr['fldEmpDTRamIn'];
					}

					$leavesctr = 0;
					//IF LATE
					$lates = getEmpLate($dayDesc,$dt,$amIN,$dtr['fldEmpDTRamOut']);
					if($lates > 0)
						$lates_ctr++;

					if($userid == 233)
					{
						$lates = getEmpLateMsAnna($dayDesc,$dt,$amIN,$dtr['fldEmpDTRpmIn']);
						$undertime = getEmpUnderMsAnna($dayDesc,$dt,$pmOUT);
						$totalhrs = totalHrsMsAnna($dayDesc,$dt,$amIN,$pmOUT,null,1);
					}

					$excess = $totalhrs - $requiredHrs;

					if($sus)
					{
						$leavesctr = $sus['required_hrs'] * 60;
						$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],"12:00:00",null,2);
						$excess = $totalhrs - $leavesctr;
						$required_hrs = $required_hrs - $leavesctr;

						$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';

						if($sus['suspension_time_desc'] == 'PM')
						{
							$undertime  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
						}
						else
						{
							$lates = 0;
						}
					}
					else
					{
						//$undertime = $totalhrs - 480;
					}

					if($excess < 0)
					{
						$deficit = abs($excess) - ($undertime + $lates);
						$excess = 0;
					}
						
						
					if($sus)
					{
						$leavesctr = ($sus['required_hrs'] * 60) + 60;
					}
				}


				if($dtr['fldEmpDTRamIn'] == null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null)
				{
					$dtrcase = 5;
					//IF LATE
					$lates = 0;

					if($sus)
					{
						$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';
					}
					else
					{
						
						//$undertime = $totalhrs - 480;
					}

					//IF UNDERTIME
					$undertime = 0;

					$totalhrs = 0;

					$excess = 0;
					$deficit = 0;
				}

				if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] != null && $dtr['fldEmpDTRpmIn'] != null && $dtr['fldEmpDTRpmOut'] == null)
				{
					$dtrcase = 6;
					//IF LATE
					$lates = getEmpLate($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRpmIn']);
					if($lates > 0)
						$lates_ctr++;

					//IF UNDERTIME
					//$undertime = 240;
					
					if($dtr['fldEmpDTRamOut'] >= "12:00:00")
						$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],"12:00:00",null,2);
					
					if($dtr['fldEmpDTRamOut'] < "12:00:00")
					{
						$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],null,2);
						
					}
						

					if($sus)
					{
						$leavesctr = $sus['required_hrs'] * 60;
						$required_hrs = $required_hrs - $leavesctr;
						$undertime  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
						$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';
					}
					else
					{
						$undertime1  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
						$undertime2  = ($requiredHrs - $totalhrs);
						$undertime = $undertime1 + $undertime2;
						
						$leavesctr = 0;
					}

					

					

					//$leavesctr = 240;

					$excess = $totalhrs - $leavesctr;

					if($excess < 0)
					{
						$deficit = abs($excess) - ($undertime + $lates);
						$excess = 0;
					}

				
					if($sus)
					{
						$leavesctr = ($sus['required_hrs'] * 60) + 60;
					}
				}

				if($dtr['fldEmpDTRamIn'] != null && $dtr['fldEmpDTRamOut'] == null && $dtr['fldEmpDTRpmIn'] == null && $dtr['fldEmpDTRpmOut'] == null)
				{
					$dtrcase = 7;
					//FOR MS ANNA
					if($dtr['fldEmpDTRamIn'] < "07:00:00" && $userid == 233)
					{
						$pmOUT = $dtr['fldEmpDTRpmOut'];
						$amIN = "07:00:00";
					}
					else
					{
						$pmOUT = $dtr['fldEmpDTRpmOut'];
						$amIN = $dtr['fldEmpDTRamIn'];
					}

					$leavesctr = 0;
					//IF LATE
					$lates = getEmpLate($dayDesc,$dt,$amIN,$dtr['fldEmpDTRamOut']);
					if($lates > 0)
						$lates_ctr++;

					if($userid == 233)
					{
						$lates = getEmpLateMsAnna($dayDesc,$dt,$amIN,$dtr['fldEmpDTRpmIn']);
						$undertime = getEmpUnderMsAnna($dayDesc,$dt,$pmOUT);
						$totalhrs = totalHrsMsAnna($dayDesc,$dt,$amIN,$pmOUT,null,1);
					}

					$excess = $totalhrs - $requiredHrs;

					if($sus)
					{
						$leavesctr = $sus['required_hrs'] * 60;
						$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],"12:00:00",null,2);
						$excess = $totalhrs - $leavesctr;
						$required_hrs = $required_hrs - $leavesctr;

						$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';

						if($sus['suspension_time_desc'] == 'PM')
						{
							$undertime  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
						}
						else
						{
							$lates = 0;
						}
					}
					else
					{
						//$undertime = $totalhrs - 480;
					}

					if($excess < 0)
					{
						$deficit = abs($excess) - ($undertime + $lates);
						$excess = 0;
					}
						
						
					if($sus)
					{
						$leavesctr = ($sus['required_hrs'] * 60) + 60;
					}
				}


				if($dayDesc == 'Sat' || $dayDesc == 'Sun')
				{
					$totalhrs = 0;

					// $rows = "";
					$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='9' class='text-success'></td></tr>";
					
				}
				else
				{
					
					
					//CHECK HALFDAY HOLIDAY
					if(checkIfHalfHoliday($dt))
					{
						$totalhrs = $totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],null,2);
						$leavesctr += 240;
					}
					else
					{
						$totalhrs = $totalhrs;
					}

					if($sus)
					{
						if($sus['suspension_time_desc'] == 'Wholeday')
						{
							$leavesctr = 480;
							
							$rows =  "<tr style='vertical-align: baseline;'><td style='width:10%;padding-left:8%;'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='8' class='text-success'><b>".$sus['fldSuspensionRemarks']."</b><td align='center'></td></tr>";
						}
						else
						{

							// $rows =  "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' style='width:10%'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td align='center'>E : $excess D : $deficit U : $undertime L : $leavesctr</td></tr>";
							
							$rows =  "<tr style='vertical-align: baseline;'><td style='width:10%;padding-left:8%;'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' style='width:10%'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td align='center'>$remarks</td></tr>";
						}
					}
					else
					{
						

						//CHECK HALFDAY HOLIDAY
						if(checkIfHalfHoliday($dt))
						{
							//IF UNDERTIME
							//$undertime = 240;
							switch ($dtrcase) {
								case 1:
								case 3:
								case 4:	
								case 6:
								case 7:	
									
									//IF LATE
									$lates = getEmpLate($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut']);
									if($lates > 0)
										$lates_ctr++;

									if($dtr['fldEmpDTRamOut'] >= "12:30:00")
										$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],"12:30:00",null,2);
									
										if($dtr['fldEmpDTRamOut'] < "12:30:00")
										{
											$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],null,2);
											
										}

										$undertime  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
										//$undertime2  = (240 - $totalhrs);
										//$undertime = $undertime1 + $undertime2;
										
									break;
								case 5:
										$totalhrs = 0;
										$undertime = 0;
									break;
							}
							
								

							if($sus)
							{
								$leavesctr = $sus['required_hrs'] * 60;
								$required_hrs = $required_hrs - $leavesctr;
								$undertime  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
								$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';
							}
							else
							{
								//$undertime1  = getEmpUnderAM($dtr['fldEmpDTRamOut']);
								//$undertime2  = (240 - $totalhrs);
								//$undertime = $undertime1 + $undertime2;
							}

							$excess = $totalhrs - $leavesctr;

							if($excess < 0)
							{
								$deficit = abs($excess) - ($undertime + $lates);
								$excess = 0;
							}

						
							if($sus)
							{
								$leavesctr = ($sus['required_hrs'] * 60) + 60;
							}


							$rows =  "<tr style='vertical-align: baseline;'><td style='width:10%;padding-left:8%;'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%' colspan='2'>".getHoliday($dt)."</td><td align='center' style='width:10%'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td align='center'>$remarks</td></tr>";
						}
						else
						{
							//$rows =  "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' style='width:10%'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td align='center'>E : $excess D : $deficit U : $undertime L : $leavesctr</td></tr>";

							$rows =  "<tr style='vertical-align: baseline;'><td style='width:10%;padding-left:8%;'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' style='width:10%'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td align='center'>$remarks</td></tr>";
						}

						
					}
				}
							
		}

		//IF MAY T.O
		$tos = App\RequestTO::where('to_date_from',$dt)->where('userid',$userid)->whereIn('to_status',['Approved','Processed'])->first();
		if($tos)
		{
	
			//IF LATE
			$lates = getEmpLate($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRpmIn']);

			//IF UNDERTIME
			$undertime = getEmpUnder($dayDesc,$dt,$dtr['fldEmpDTRpmOut']);

			switch($tos['to_total_day'])
				{
					case 1.0:
						if($dayDesc == 'Sat' || $dayDesc == 'Sun')
						{
							$deficit = 0;
							$totalhrs = 0;
						}
						else
							$totalhrs = 480;

						$leavesctr = 0;
						
						if($sus)
						{
							$remarks = "<span style='font-size:9px'>".$sus['fldSuspensionRemarks'].'</span>';
							
							$rows = "<tr style='vertical-align: baseline;'><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='4' class='text-success'><b>On-Trip</b></td><td align='center'>".readableTime($totalhrs)."</td><td></td><td></td><td></td><td>$remarks</td></tr>";
						}
						else
						{
							$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='4' class='text-success'><b>On-Trip</b></td><td align='center'>".readableTime($totalhrs)."</td><td></td><td></td><td></td><td>$remarks</td></tr>";
						}	

					break;
					
					case 0.5:
						if($tos['to_deduction_time'] == 'AM')
						{
							
							//TOTAL HOURS
							$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],null,2,'PM');

							if($totalhrs <= 0)
								$totalhrs = 240;
							else
								$totalhrs += 240;

							$lst = App\Employee_dtr::where('user_id',$userid)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();


							if($lst)
							{
								//$totalhrs += 240;
								$lates = getEmpLatePM($dt,$dtr['fldEmpDTRpmIn']);
								if($lates > 0)
									$lates_ctr++;

								// $rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td align='center'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center'>".readableTime($totalhrs)."</td><td></td><td></td><td></td><td>E : $excess D : $deficit U : $undertime L : $leavesctr</td></tr>";

								$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td align='center'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center'>".readableTime($totalhrs)."</td><td align='center'>".readableTime($lates)."</td><td></td><td></td><td>$remarks</td></tr>";
							}
							else
							{
								if(getLeaveCount("count","PM",$dt,$userid) >= 1)
								{
									$excess = 0;
									$deficit = 0;
									$leavesctr += 240;

									//GET LEAVE DETAIL
									$leave2 = getLeaveCount("list","PM",$dt,$userid);

									$lates = 0;

									//IF CTO/WFH
									if($leave['leave_id'] == 5 || $leave['leave_id'] == 16)
									{
										$totalhrs = $totalhrs + 240;
										$leavesctr = 0;
									}

									//IF UNDERTIME
									$undertime = 0;

									$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td colspan='2' align='center'><b>".$leave2['leave_desc']."</b></td><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>$remarks</td></tr>";

								}
								else
								{
									$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center'></td><td align='center'></td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td ><td align='center'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td></td><td>$remarks</td></tr>";
								}
							}
						}
						else
						{
							//TOTAL HOURS
							$totalhrs = totalHrs($dayDesc,$dt,$dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],null,2,'AM');
							$needed = 240;

							// if($totalhrs <= 0)
							// 	$totalhrs = 240;
							// else
							// 	$totalhrs += 240;


							//$undertime = getEmpUnder($dayDesc,$dt,$dtr['fldEmpDTRpmOut']);
							
							$undertime = 0;

							//IF DEFICIT
							$testdefict = $needed - ($totalhrs + $lates);
							if($testdefict > 0)
								$deficit = $testdefict;
							
							$undertime = 240 - $totalhrs;
							
							if($undertime > 0)
								$undertime = 0;


							$lst = App\Employee_dtr::where('user_id',$userid)->where('fldEmpDTRdate',$dt)->orderBy('id','DESC')->first();

							if($lst)
							{
								$totalhrs += 240;

								$lates = getEmpLate($dayDesc,$dt,$dtr['fldEmpDTRamIn'],'12:00:00');

								$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td align='center'>".readableTime($totalhrs)."</td><td align='center'>".readableTime($lates)."</td><td></td><td></td><td>$remarks</td></tr>";
							}
							else
							{
								if(getLeaveCount("count","AM",$dt,$userid) >= 1)
								{
									$excess = 0;
									$deficit = 0;
									$leavesctr += 240;
									//$totalhrs = $totalhrs + 240;

									//GET LEAVE DETAIL
									$leave2 = getLeaveCount("list","AM",$dt,$userid);

									$lates = 0;

									//IF CTO/WFH
									if($leave['leave_id'] == 5 || $leave['leave_id'] == 16)
									{
										$totalhrs = $totalhrs + 240;
										$leavesctr = 0;
									}

									//IF UNDERTIME
									$undertime = 0;

									$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td colspan='2' align='center'><b>".$leave2['leave_desc']."</b></td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td align='center'>".readableTime($totalhrs)."</td><td align='center' style='width:10%'>".readableTime($lates)."</td><td align='center' style='width:10%'>".readableTime($undertime)."</td><td></td><td>$remarks</td></tr>";

								}
								else
								{
									$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center'></td><td align='center'></td><td align='center' colspan='2' class='text-success'><b>On-Trip</b></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'>$remarks</td></tr>";
								}
							}
						}

						if($dayDesc == 'Sat' || $dayDesc == 'Sun')
						{
							$deficit = 0;
							$totalhrs = 0;
						}
						else
							$totalhrs = 240;
					break;
				}
		}
	}
	else
	{
		$totalhrs = 0;
		$rows = "<tr><td style='width:10%;padding-left:8%'><span>".$i."</span><span style='float:right;padding-right:8%'>".$dayDesc."</span></td><td align='center' colspan='8' class='text-success'><b>".getHoliday($dt)."$remarks</b></td><td></td></tr>";
	}
	
	if($dayDesc == 'Sat' || $dayDesc == 'Sun')
				{
					$totalhrs = 0;
					$deficit = 0;
					//$rows = "";
					
				}

	//$excess = 480;

	//IF MAY SUSPENSION

	//FOR DTR EXEMPTION
	$exmp = App\User::where('id',$userid)->first();
	$exmp = $exmp['dtr_exe'];
	if($exmp == 1)
	{
		$lates = 0;
		$undertime = 0;
		$deficit = 0;
	}
	

	return $rows."|".$lates."|".$undertime."|".$totalhrs."|".$leavesctr."|".$dt."|".$excess."|".$deficit."|".$required_hrs."|".$lates_ctr;
}

function getEmpLate($day,$dt,$timeAM,$timePM,$leave = null)
{
	if($leave == 'AM')
	{
		$timeAM = "8:00:00";
	}

	if($leave == 'halfday')
	{
		$timeAM = "12:00:00";
	}
		

	if($day == 'Mon')
	{
		$minAM = "8:00:00";
	}
	else
	{
		$minAM = "8:30:00";
	}

	if($leave == 'PM')
		$minAM = "13:00:00";

	$dt1 = Carbon\Carbon::parse($dt.' '.$minAM)->format('Y-m-d H:s:i');
	$dt2 = Carbon\Carbon::parse($dt.' '.$timeAM)->format('Y-m-d H:s:i');

	$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
	$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
	//$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-05-06 3:30:34');
	//$from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-05-06 3:30:54');


	$diff_in_minutes = $to->diffInMinutes($from);

	if($day == 'Sat' || $day == 'Sun' || $timeAM == null || $leave == 'wholeday')
	{
		return 0;
	}	
	else
	{
		if(strtotime($timeAM) > strtotime($minAM))
		{
			return $diff_in_minutes;
		}
	}
		
}

function getEmpLateMsAnna($day,$dt,$timeAM,$timePM,$leave = null)
{
	if($leave == 'AM')
	{
		$timeAM = "8:00:00";
	}

	if($leave == 'halfday')
	{
		$timeAM = "12:00:00";
	}
		

	if($day == 'Mon')
	{
		$minAM = "8:00:00";
	}
	else
	{
		$minAM = "8:00:00";
	}

	if($leave == 'PM')
		$minAM = "13:00:00";

	$dt1 = Carbon\Carbon::parse($dt.' '.$minAM)->format('Y-m-d H:s:i');
	$dt2 = Carbon\Carbon::parse($dt.' '.$timeAM)->format('Y-m-d H:s:i');

	$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
	$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
	//$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-05-06 3:30:34');
	//$from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-05-06 3:30:54');


	$diff_in_minutes = $to->diffInMinutes($from);

	if($day == 'Sat' || $day == 'Sun' || $timeAM == null || $leave == 'wholeday')
	{
		return 0;
	}	
	else
	{
		if(strtotime($timeAM) > strtotime($minAM))
		{
			return $diff_in_minutes;
		}
	}
		
}

function getEmpLatePM($dt,$timePM,$leave = null)
{
	//$timeAM = "13:00:00";
	if($timePM < "13:00:00")
		$timePM = "13:00:00";

	$dt1 = Carbon\Carbon::parse($dt.' '."13:00:00")->format('Y-m-d H:s:i');
	$dt2 = Carbon\Carbon::parse($dt.' '.$timePM)->format('Y-m-d H:s:i');

	$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
	$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);

	$diff_in_minutes = $to->diffInMinutes($from);

	return $diff_in_minutes;
		
}

function getEmpUnder($day,$dt,$timePM,$timeAM = null)
{

	
	$minPM = "16:30:00";

	if($timePM < $minPM)
	{
		$dt1 = Carbon\Carbon::parse($dt.' '.$minPM)->format('Y-m-d H:s:i');
		$dt2 = Carbon\Carbon::parse($dt.' '.$timePM)->format('Y-m-d H:s:i');

		$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
		$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
		//$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-05-06 3:30:34');
		//$from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-05-06 3:30:54');


		$diff_in_minutes = $to->diffInMinutes($from);

		if($day == 'Sat' || $day == 'Sun' || $timePM == null)
		{
			return 0;
		}	
		else
		{
				//return ($diff_in_minutes + 1);
				//return $diff_in_minutes;
		}

		//REMOVE SECOND
		$timePM = date('H:i',strtotime($timePM));

		$dateTimeObject1 = date_create($minPM); 
		$dateTimeObject2 = date_create($timePM); 
  
		$difference = date_diff($dateTimeObject1, $dateTimeObject2);

		$minutes = $difference->days * 24 * 60;
		$minutes += $difference->h * 60;
		$minutes += $difference->i;

		return $minutes;

	}
		
}

function getEmpUnderMsAnna($day,$dt,$timePM,$timeAM = null)
{

	
	$minPM = "16:00:00";

	if($timePM < $minPM)
	{
		$dt1 = Carbon\Carbon::parse($dt.' '.$minPM)->format('Y-m-d H:s:i');
		$dt2 = Carbon\Carbon::parse($dt.' '.$timePM)->format('Y-m-d H:s:i');

		$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
		$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
		//$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-05-06 3:30:34');
		//$from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-05-06 3:30:54');


		$diff_in_minutes = $to->diffInMinutes($from);

		if($day == 'Sat' || $day == 'Sun' || $timePM == null)
		{
			return 0;
		}	
		else
		{
				//return ($diff_in_minutes + 1);
				//return $diff_in_minutes;
		}

		//REMOVE SECOND
		$timePM = date('H:i',strtotime($timePM));

		$dateTimeObject1 = date_create($minPM); 
		$dateTimeObject2 = date_create($timePM); 
  
		$difference = date_diff($dateTimeObject1, $dateTimeObject2);

		$minutes = $difference->days * 24 * 60;
		$minutes += $difference->h * 60;
		$minutes += $difference->i;

		return $minutes;

	}
		
}

function getEmpUnderAM($tm)
{
	if($tm < '12:00:00')
	{
		//REMOVE SECOND
		$tm = date('H:i',strtotime($tm));

		$dateTimeObject1 = date_create('12:00:00'); 
		$dateTimeObject2 = date_create($tm); 

		$difference = date_diff($dateTimeObject1, $dateTimeObject2);

		$minutes = $difference->days * 24 * 60;
		$minutes += $difference->h * 60;
		$minutes += $difference->i;

		return $minutes;
	}
	else
	{
		return 0;
	}
			
}

function totalHrs($dayDesc,$dt,$timeAM,$timePM,$leave = null,$type = null,$tm = null,$userid = null)
{
	if($leave)
		{
			if($leave == 'AM')
			{
				if($dayDesc == 'Mon')
				{
					if($timePM >= "17:00:00")
					{
						$timePM = "17:00:00";
					}
				}
				else
				{
					if($timePM >= "17:30:00")
					{
						 $timePM = "17:30:00";
					}
				}

				if($timeAM <= "13:00:00")
					$timeAM = "13:00:00";

				$dt1 = Carbon\Carbon::parse($dt.' '.$timeAM)->format('Y-m-d H:s:i');
				$dt2 = Carbon\Carbon::parse($dt.' '.$timePM)->format('Y-m-d H:s:i');
				$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
				$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
				$diff_in_minutes = $to->diffInMinutes($from);
				//return ($diff_in_minutes + 1);

				//REMOVE SECOND
				$timePM = date('H:i',strtotime($timePM));
				$timeAM = date('H:i',strtotime($timeAM));

				$dateTimeObject1 = date_create($timeAM); 
				$dateTimeObject2 = date_create($timePM); 
		
				$difference = date_diff($dateTimeObject1, $dateTimeObject2);

				$minutes = $difference->days * 24 * 60;
				$minutes += $difference->h * 60;
				$minutes += $difference->i;

				return $minutes;
			}
			else
			{
				if($timePM >= "12:00:00")
					$timePM = "12:00:00";
				
				$dt1 = Carbon\Carbon::parse($dt.' 12:00:00')->format('Y-m-d H:s:i');
				$dt2 = Carbon\Carbon::parse($dt.' '.$timeAM)->format('Y-m-d H:s:i');
				$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
				$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
				$diff_in_minutes = $to->diffInMinutes($from);
				//return ($diff_in_minutes + 1);
				//return $diff_in_minutes;

				//REMOVE SECOND
				$timePM = date('H:i',strtotime($timePM));
				$timeAM = date('H:i',strtotime($timeAM));

				$dateTimeObject1 = date_create($timeAM); 
				$dateTimeObject2 = date_create($timePM); 
		
				$difference = date_diff($dateTimeObject1, $dateTimeObject2);

				$minutes = $difference->days * 24 * 60;
				$minutes += $difference->h * 60;
				$minutes += $difference->i;

				return $minutes;
			}
			

		}
		else
		{
			//IF SOBRANG AGA
			if($timeAM != null)
				if($timeAM < "07:30:00")
				{
					//$timeAM = "07:30:00";
					if($userid == 233)
						$timeAM = "07:00:00";
					else
						$timeAM = "07:30:00";
				}
					
			
			if($dayDesc == 'Mon')
			{
				if($timePM >= "17:00:00")
					$timePM = "17:00:00";
			}
			else
			{
				if($timePM >= "17:30:00")
					$timePM = "17:30:00";
			}
			
			if($tm == 'AM')
				{
					if($timePM >= "12:00:00")
						$timePM = "12:00:00";
				}
			
			if($tm == 'PM')
				{
					if($timeAM < "13:00:00")
						$timeAM = "13:00:00";
				}

			$timeAM = date("H:i:00",strtotime($timeAM));
			$timePM = date("H:i:00",strtotime($timePM));

			$dt1 = Carbon\Carbon::parse($dt.' '.$timeAM)->format('Y-m-d H:s:i');
			$dt2 = Carbon\Carbon::parse($dt.' '.$timePM)->format('Y-m-d H:s:i');
			$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
			$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
			$diff_in_minutes = $to->diffInMinutes($from);

			if($type == 1)
			{
				return ($diff_in_minutes - 60);
			}	
			else
			{
				return $diff_in_minutes;
			}
				
		}
}

function totalHrsMsAnna($dayDesc,$dt,$timeAM,$timePM,$leave = null,$type = null,$tm = null)
{
	if($leave)
		{
			if($leave == 'AM')
			{
				if($dayDesc == 'Mon')
					if($timePM >= "17:00:00")
						$timePM = "17:00:00";

				if($timeAM <= "13:00:00")
					$timeAM = "13:00:00";

				$dt1 = Carbon\Carbon::parse($dt.' '.$timeAM)->format('Y-m-d H:s:i');
				$dt2 = Carbon\Carbon::parse($dt.' '.$timePM)->format('Y-m-d H:s:i');
				$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
				$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
				$diff_in_minutes = $to->diffInMinutes($from);
				//return ($diff_in_minutes + 1);

				//REMOVE SECOND
				$timePM = date('H:i',strtotime($timePM));
				$timeAM = date('H:i',strtotime($timeAM));

				$dateTimeObject1 = date_create($timeAM); 
				$dateTimeObject2 = date_create($timePM); 
		
				$difference = date_diff($dateTimeObject1, $dateTimeObject2);

				$minutes = $difference->days * 24 * 60;
				$minutes += $difference->h * 60;
				$minutes += $difference->i;

				return $minutes;
			}
			else
			{
				if($timePM >= "12:00:00")
					$timePM = "12:00:00";
				
				$dt1 = Carbon\Carbon::parse($dt.' 12:00:00')->format('Y-m-d H:s:i');
				$dt2 = Carbon\Carbon::parse($dt.' '.$timeAM)->format('Y-m-d H:s:i');
				$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
				$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
				$diff_in_minutes = $to->diffInMinutes($from);
				//return ($diff_in_minutes + 1);
				//return $diff_in_minutes;

				//REMOVE SECOND
				$timePM = date('H:i',strtotime($timePM));
				$timeAM = date('H:i',strtotime($timeAM));

				$dateTimeObject1 = date_create($timeAM); 
				$dateTimeObject2 = date_create($timePM); 
		
				$difference = date_diff($dateTimeObject1, $dateTimeObject2);

				$minutes = $difference->days * 24 * 60;
				$minutes += $difference->h * 60;
				$minutes += $difference->i;

				return $minutes;
			}
			

		}
		else
		{
			//IF SOBRANG AGA
			if($timeAM != null)
				if($timeAM < "07:00:00")
					$timeAM = "07:00:00";
			
			if($dayDesc == 'Mon')
			{
				if($timePM >= "17:00:00")
					$timePM = "17:00:00";
			}
			else
			{
				if($timePM >= "17:30:00")
					$timePM = "17:30:00";
			}
			
			if($tm == 'AM')
				{
					if($timePM >= "12:00:00")
						$timePM = "12:00:00";
				}
			
			// if($tm == 'PM')
			// 	{
			// 		if($timeAM < "13:00:00")
			// 			$timeAM = "13:00:00";
			// 	}

			$timeAM = date("H:i:00",strtotime($timeAM));
			$timePM = date("H:i:00",strtotime($timePM));

			$dt1 = Carbon\Carbon::parse($dt.' '.$timeAM)->format('Y-m-d H:s:i');
			$dt2 = Carbon\Carbon::parse($dt.' '.$timePM)->format('Y-m-d H:s:i');
			$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
			$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);
			$diff_in_minutes = $to->diffInMinutes($from);

			if($type == 1)
			{
				return ($diff_in_minutes - 60);
			}	
			else
			{
				return $diff_in_minutes;
			}
				
			
		}
}

function readableTime($time)
{
	//return $time;
	if($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf('%01dh %01dm', $hours, $minutes);
}

function readableTime2($time)
{
	//return $time;
	if($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf('%01d-%01d', $hours, $minutes);
}

function getWorkingDate($dt)
{
	$mon = date('m',strtotime($dt));
	$from = Carbon\Carbon::parse($dt);
	$total_days = Carbon\Carbon::parse($from)->daysInMonth;
	$ctr = 0;
	for ($i=0; $i <= $total_days; $i++)
        {
            if($i == 0)
            {
                $dt2 = date('Y-m-d',strtotime($from));
            }
            else
            {
				$dt_main = $from->addDays(1);
				$dt2 = $dt_main;
            }
            if(date('m',strtotime($dt2)) == $mon)
			{
				if(!checkIfWeekend($dt2))
				{
					if(!checkIfHoliday($dt2))
					{
						$ctr++;
						//echo $dt2."<br/>";
					}
				}
			}
		}
	return $ctr * 150;
}

// function checkifProcesReq($tbl,$code)
// {
// 	if($tbl == 'leave')
// 	{
// 		$req = App\Request_leave::where('parent_leave_code',$code)->count();
// 	}
// 	else if($tbl == 'to')
// 	{
// 		$req = App\RequestTO::where('parent_to_code',$code)->count();
// 	}

// 	if($req > 0)
// 		return true;
// 	else
// 		return false;
// }

function checkifProcesReq($id,$mon,$yr)
{
	$req = App\DTRProcessed::where('userid',$id)->where('dtr_mon',$mon)->where('dtr_year',$yr)->count();

	if($req > 0)
		return true;
	else
		return false;
}

function checkIfHasLeaveHalf($type,$userid,$dt,$tm)
{
	if($type == 'leave')
	{
		$req = App\Request_leave::where('user_id',$userid)->where('leave_date_from',$dt)->where('leave_deduction',0.5)->where('leave_deduction_time',$tm)->first();
		if(isset($req))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{

	}
}


function getUnderTime($userid,$empcode,$mon,$yr,$process_code)
{
	//return getWorkingDate('2022-05-01');
	ini_set('memory_limit', '512M');
	ini_set('max_execution_time', 180);

	$tardy_text = "";

	$total_tardy = 0;

	$dtr = App\Employee_dtr::where('user_id',$userid)->whereMonth('fldEmpDTRdate',$mon)->whereYear('fldEmpDTRdate',$yr)->orderBy('fldEmpDTRdate')->get();

	foreach ($dtr as $key => $dtrs) {

		$dayDesc = weekDesc($dtrs->fldEmpDTRdate);

		$flag = 0;

		if(!checkIfWeekend($dtrs->fldEmpDTRdate))
		{
			if(!checkIfHoliday($dtrs->fldEmpDTRdate))
			{
				$totalhrs = 0;
				if($dtrs->fldEmpDTRamIn != null && $dtrs->fldEmpDTRamOut != null && $dtrs->fldEmpDTRpmIn != null && $dtrs->fldEmpDTRpmOut != null)
				{
					$totalhrs = totalHrs($dayDesc,$dtrs->fldEmpDTRdate,$dtrs->fldEmpDTRamIn,$dtrs->fldEmpDTRpmOut,null,1);
				}

				if($dtrs->fldEmpDTRamIn != null && $dtrs->fldEmpDTRamOut == null && $dtrs->fldEmpDTRpmIn != null && $dtrs->fldEmpDTRpmOut != null)
				{
					$totalhrs = totalHrs($dayDesc,$dtrs->fldEmpDTRdate,$dtrs->fldEmpDTRamIn,$dtrs->fldEmpDTRpmOut,null,1);
				}

				if($dtrs->fldEmpDTRamIn != null && $dtrs->fldEmpDTRamOut != null && $dtrs->fldEmpDTRpmIn == null && $dtrs->fldEmpDTRpmOut != null)
				{
					$totalhrs = totalHrs($dayDesc,$dtrs->fldEmpDTRdate,$dtrs->fldEmpDTRamIn,$dtrs->fldEmpDTRpmOut,null,1);
				}


				if($dtrs->fldEmpDTRamIn != null && $dtrs->fldEmpDTRamOut != null && $dtrs->fldEmpDTRpmIn == null && $dtrs->fldEmpDTRpmOut == null)
				{
					$dt = $dtrs->fldEmpDTRdate;
					$tm1 = $dtrs->fldEmpDTRamIn;
					$tm2 = $dtrs->fldEmpDTRamOut;
					if($dayDesc == 'Mon')
					{
						if($tm1 <= "07:30:00")
							$tm1 = "07:30:00";
					}

					if($tm2 >= "12:00:00")
							$tm2 = "12:30:00";
					
					$dt1 = Carbon\Carbon::parse($dt.' '.$tm1)->format('Y-m-d H:s:i');
					$dt2 = Carbon\Carbon::parse($dt.' '.$tm2)->format('Y-m-d H:s:i');
					$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
					$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);

					$totalhrs = $to->diffInMinutes($from);
				}

				if($dtrs->fldEmpDTRamIn == null && $dtrs->fldEmpDTRamOut == null && $dtrs->fldEmpDTRpmIn != null && $dtrs->fldEmpDTRpmOut != null)
				{
					$dt = $dtrs->fldEmpDTRdate;
					$tm1 = $dtrs->fldEmpDTRpmIn;
					$tm2 = $dtrs->fldEmpDTRpmOut;
					if($dayDesc == 'Mon')
					{
						if($tm1 <= "13:00:00")
							$tm1 = "13:00:00";
						
						if($tm2 >= "17:00:00")
							$tm1 = "17:00:00";
					}

					if($tm2 >= "17:30:00")
							$tm1 = "17:30:00";
					
					$dt1 = Carbon\Carbon::parse($dt.' '.$tm1)->format('Y-m-d H:s:i');
					$dt2 = Carbon\Carbon::parse($dt.' '.$tm2)->format('Y-m-d H:s:i');
					$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $dt1);
					$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $dt2);

					$totalhrs = $to->diffInMinutes($from);
					//$totalhrs = totalHrs($dayDesc,$dtrs->fldEmpDTRdate,"13:00:00",$dtrs->fldEmpDTRpmOut,null,2,'PM');
				}
				
				if($totalhrs >= 240 && $totalhrs <= 354)
				{
					if(checkIfHasLeave($dtrs->fldEmpDTRdate,$userid))
					{
						$flag++;

						// $test = new App\PSB;
						// $test->name = "DATE : ".$dtrs->fldEmpDTRdate." TOTAL HRS : ".$totalhrs. " HAS LEAVE";
						// $test->save();
					}

					$tos = App\RequestTO::where('to_date_from',$dtrs->fldEmpDTRdate)->where('userid',$userid)->whereIn('to_status',['Approved','Processed'])->first();

					if($tos)
					{
						$flag++;

						// $test = new App\PSB;
						// $test->name = "DATE : ".$dtrs->fldEmpDTRdate." TOTAL HRS : ".$totalhrs. " HAS T.O";
						// $test->save();
					}

					if($flag == 0)
					{
						// $tardy = new App\Employee_tardy;
						// $tardy->user_id = $userid;
						// $tardy->fldEmpCode = $empcode;
						// $tardy->fldEmpDTRdate = $dtrs->fldEmpDTRdate;
						// $tardy->process_code = $process_code;
						// $tardy->total_day = 0.5;
						// $tardy->save();

						$total_tardy += 0.5;
					}
					
					$tardy_text .= "Date ".$dtrs->fldEmpDTRdate." -- Tardy : $total_tardy |";
				}	
				elseif($totalhrs < 240)
				{
					if(checkIfHasLeave($dtrs->fldEmpDTRdate,$userid))
					{
						$flag++;

						// $test = new App\PSB;
						// $test->name = "DATE : ".$dtrs->fldEmpDTRdate." TOTAL HRS : ".$totalhrs. " HAS LEAVE";
						// $test->save();
					}

					$tos = App\RequestTO::where('to_date_from',$dtrs->fldEmpDTRdate)->where('userid',$userid)->whereIn('to_status',['Approved','Processed'])->first();

					if($tos)
					{
						$flag++;

						// $test = new App\PSB;
						// $test->name = "DATE : ".$dtrs->fldEmpDTRdate." TOTAL HRS : ".$totalhrs. " HAS T.O";
						// $test->save();
					}

					if($flag == 0)
					{
						if($flag == 0)
						{
							// $tardy = new App\Employee_tardy;
							// $tardy->user_id = $userid;
							// $tardy->fldEmpCode = $empcode;
							// $tardy->fldEmpDTRdate = $dtrs->fldEmpDTRdate;
							// $tardy->total_day = 1;
							// $tardy->process_code = $process_code;
							// $tardy->save();

							$total_tardy += 1;
						}
					}

					$tardy_text .= "Date ".$dtrs->fldEmpDTRdate." -- Tardy : $total_tardy |";
				}

				//$tardy_text .= "Date ".$dtrs->fldEmpDTRdate." -- Total HRS : $totalhrs |";

			}
		}

	}
	
	return $tardy_text;

}

function monetizeLeave()
{
	$lv = App\View_request_leave::where('leave_action_status','Monetized')->get();
	return $lv;
}

function showLogo()
{
	return '<img src="https://drive.google.com/uc?id=1UyPrRPTZHKsVbBOd6Fw05PRN74MmzwtZ" alt="STII Logo" style="width:70px" />';
}