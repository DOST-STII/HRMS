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
				$cl = "badge-warning";
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
		$request_date = date('M d, Y',strtotime($value->leave_date_from)) ."-".date('M d, Y',strtotime($value->leave_date_to));

		if($value->leave_date_from == $value->leave_date_to)
			$request_date = date('F d, Y',strtotime($value->leave_date_from));
		$collection->push([
							'request_id' => $value->id,
							'request_desc' => getLeaveDesc($value->leave_id),
							'request_date' => $request_date,
							'request_lwop' => $value->lwop,
							'request_code' => $value->parent_leave,
							'request_action_status' => $value->leave_action_status
						  ]);
	}

	//CHECK T.O
	$tos = App\RequestTO::where('userid',$empid)->where('parent','YES')->whereNull('process_code')->get();

	foreach ($tos as $value) {
		# code...
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

	//CHECK T.O
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
	$dt = App\Holiday::where('holiday_date',date('Y-m-d',strtotime($dt)))->count();

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
	$leave = App\View_request_leave::where('user_id',$userid)->where('leave_date_from',date('Y-m-d',strtotime($dt)))->where('leave_action_status','Approved')->first();
	return $leave;
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

				}
				else
				{
					$req = App\Request_leave::where('user_div',$div)->where('leave_action_status','Cancelled')->whereNotNull('parent_leave')->get();
					return $req;
				}
		break;

		case "to":
			if($userid)
				{

				}
				else
				{
					$req = App\RequestTO::where('division',$div)->where('to_status','Cancelled')->where('parent','YES')->get();
					return $req;
				}
		break;
	}
	
}

function getDisapproved($div,$userid = null)
{
	if($userid)
				{

				}
				else
				{
					$req = App\Request_leave::where('user_div',$div)->where('leave_action_status','Disapproved')->whereNotNull('parent_leave')->get();
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

				}
				else
				{
					$req = App\Request_leave::where('user_div',$div)->where('leave_action_status','Approved')->whereNotNull('parent_leave')->whereNull('process_code')->get();
					return $req;
				}
		break;

		case "to":
			if($userid)
				{

				}
				else
				{
					$req = App\RequestTO::where('division',$div)->where('to_status','Approved')->where('parent','YES')->whereNull('process_code')->get();
					return $req;
				}
		break;

		case "ot":
			if($userid)
				{

				}
				else
				{
					$req = App\RequestOT::where('division',$div)->where('ot_status','Approved')->whereNull('process_code')->get();
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

				}
				else
				{
					$req = App\Request_leave::where('user_div',$div)->whereNotNull('parent_leave')->whereNotNull('process_code')->get();
					return $req;
				}
		break;

		case "to":
			if($userid)
				{

				}
				else
				{
					$req = App\RequestTO::where('division',$div)->where('to_status','Approved')->where('parent','YES')->whereNotNull('process_code')->get();
					return $req;
				}
		break;

		case "ot":
			if($userid)
				{

				}
				else
				{
					$req = App\RequestOT::where('division',$div)->where('ot_status','Approved')->whereNotNull('process_code')->get();
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

								if($dayDesc != 'Sat' && $dayDesc != 'Sun')
								{
									$rows =  "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center' style='width:10%'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td align='center' style='width:10%'>".countTotalTime($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'],$dt,$dayDesc)."</td><td align='center' style='width:10%'>".$lates."</td><td align='center' style='width:10%'>".$under."</td><td align='center'>".$remarks."</td></tr>";
								}
								else
								{
									$rows =  "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center' style='width:10%'></td><td align='center'></td><td align='center' style='width:10%'></td><td align='center'>".$remarks."</td></tr>"; 
								}

							}
							else
							{
										switch($dtr['wfh'])
										{
											case "Wholeday":
												$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>WFH</b></td><td></td></tr>";
											break;

											case "PM":

												$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='2' class='text-success'><b>WFH</b></td><td align='center'>".formatTime($dtr['fldEmpDTRpmIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRpmOut'])."</td><td></td><td></td><td></td><td></td><</tr>";
											break;

											case "AM":
												$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center'>".formatTime($dtr['fldEmpDTRamIn'])."</td><td align='center'>".formatTime($dtr['fldEmpDTRamOut'])."</td><td align='center' colspan='2' class='text-success'><b>WFH</b></td><td></td><td></td><td></td><td></td><</tr>";
											break;
										}
								  
							 	}
					}
	}
	else
	{
		$rows = "<tr><td style='width:10%'><span>".$i."</span><span style='float:right'>".$dayDesc."</span></td><td align='center' colspan='7' class='text-success'><b>".getHoliday($dt)."</b></td><td></td></tr>";
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

				                          		//LWOP
				                          		$lwop = "";
												if($leave['lwop'] == 'YES')
													$lwop = " <span class='text-danger'>(LWOP)</span>";

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




