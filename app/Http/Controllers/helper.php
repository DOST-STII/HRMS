<?php

function nav($activepage = null)
{
	$dashboard = "";
	$myprofile = "";
	$invitation = "";
	$hiring = "";
	$submission = "";
	$attendance_menu = "";
	$attendance = "";
	$attendance_approval = "";
	$monitor = "";
	$vacant = "";
	$pislibrary = "";
	$calendar = "";
	$servicerecord = "";

	$numemp = "";
	$arvemp = "";

	$retiree = "";
	$jos = "";

	$recruit = "";
    $learn = "";
    $performance = "";

    //DTR
    $empdtr = "";
    $icos = "";
    $dtr = "";

    //PAYROLL
    $payroll_emp = "";
    $payroll_process = "";
	$payroll_ledger = "";
    $payroll_lib = "";
    $payroll_report = "";

	switch ($activepage) {
		//ADMIN
		case 'dashboard':
			# code...
				$dashboard = "active";
			break;
		case 'myprofile':
			# code...
				$myprofile = "active";
			break;
		case 'invitation':
			# code...
				$invitation = "active";
			break;
		case 'hiring':
			# code...
				$hiring = "active";
			break;
		case 'submission':
			# code...
				$submission = "active";
			break;
		case 'attendance':
			# code...
				$attendance_approval = "active";
			break;
		case 'vacant':
			# code...
				$vacant = "active";
			break;
		case 'numemp':
			# code...
				$numemp = "active";
			break;
		case 'arvemp':
			# code...
				$arvemp = "active";
			break;
		case 'retiree':
			# code...
				$retiree = "active";
			break;
		case 'jos':
			# code...
				$jos = "active";
			break;
		case 'pislibrary':
			# code...
				$pislibrary = "active";
			break;
		case 'service-record':
			# code...
				$servicerecord = "active";
			break;
		case 'calendar':
			# code...
				$calendar = "active";
			break;
		case 'recruit':
			# code...
				$recruit = "active";
			break;
		case 'learn':
			# code...
				$learn = "active";
			break;
		case 'performance':
			# code...
				$performance = "active";
			break;
		case 'icos':
			# code...
				$icos = "active";
			break;
		case 'empdtr':
			# code...
				$empdtr = "active";
			break;
		case 'dtr':
			# code...
				$attendance_menu = "menu-open";
				$attendance = "active";
				$dtr = "active";
			break;

		case 'payrollemp':
			# code...
				$payroll_emp = "active";
			break;

		case 'payrollprocess':
			# code...
				$payroll_process = "active";
			break;

		case 'payrolllib':
			# code...
				$payroll_lib = "active";
			break;

		case 'payrollledger':
			# code...
				$payroll_ledger = "active";
			break;

		case 'payrollreport':
			# code...
				$payroll_report = "active";
			break;
		case 'monitor':
			# code...
				$attendance_menu = "menu-open";
				$attendance = "active";
				$monitor = "active";
			break;
	}

	return  [
				"dashboard" => $dashboard,
				"myprofile" => $myprofile,
				"invitation" => $invitation,
				"hiring" => $hiring,
				"submission" => $submission,
				"attendance_menu" => $attendance_menu,
				"attendance" => $attendance,
				"attendance_approval" => $attendance_approval,
				"monitor" => $monitor,
				"vacant" => $vacant,
				"pislibrary" => $pislibrary,
				"calendar" => $calendar,
				"servicerecord" => $servicerecord,
				"recruit" => $recruit,
				"retiree" => $retiree,
				"jos" => $jos,
				"learn" => $learn,
				"performance" => $performance,
				"empdtr" => $empdtr,
				"icos" => $icos,
				"dtr" => $dtr,
				"numemp" => $numemp,
				"arvemp" => $arvemp,
				"payroll_emp" => $payroll_emp,
				"payroll_process" => $payroll_process,
				"payroll_ledger" => $payroll_ledger,
				"payroll_lib" => $payroll_lib,
				"payroll_report" => $payroll_report,
			];
}

function weekOfMonth2($date) {
    $firstOfMonth = date("Y-m-01", strtotime($date));
    return intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth)));
}

function weekOfMonth($date) {
    $firstOfMonth = date("Y-m-01", strtotime($date));
    return weekOfYear($date) - weekOfYear($firstOfMonth) + 1;
}

function weekOfYear($date) {
    $weekOfYear = intval(date("W", strtotime($date)));
    if (date('n', strtotime($date)) == "1" && $weekOfYear > 51) {
        // It's the last week of the previos year.
        return 0;
    }
    else if (date('n', strtotime($date)) == "12" && $weekOfYear == 1) {
        // It's the first week of the next year.
        return 53;
    }
    else {
        // It's a "normal" week.
        return $weekOfYear;
    }
}

function getMonths() {
    return array( 'January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December');
}

function weekDesc($date)
{
	return date('D',strtotime($date));
}

function getMon($val)
{
	return date('F',strtotime($val));
}

function formatDate($date)
{
	return date('M d, Y',strtotime($date));
}

function showCertificate($path)
{
	if(isset($path))
	{
		return "<i class='fas fa-clip'></i>";
	}
}

function formatNumber($type,$val)
{
	switch ($type) {
		case 'currency':
			# code...
				return number_format($val,2);
			break;
		
		default:
			# code...
				return number_format($val);
			break;
	}
	
}

function date_sort($a, $b) {
    return strtotime($a) - strtotime($b);
}

function getAge($date)
{
	return Carbon\Carbon::parse($date)->age;
}

function getDivision($id)
{
	if($id != 'ALL')
	{
		$div = App\Division_all::where('division_id',$id)->first();
		if(isset($div['division_acro']))
		{
			return $div['division_acro'];
		}
		else
		{
			return "";
		}
		
	}
	else
	{
		return "ALL";
	}
	
}

function getDivisionList()
{
	$div = App\Division::orderBy('division_acro')->get();
	return $div;
}

function getDesignationList()
{
	$desig = App\Designation::orderBy('designation_desc')->get();
	return $desig;
}

function getAllDivision()
{
	return App\Division::orderBy('division_acro','ASC')->get();
}

function infoBoxEmployee()
{
	return App\View_user::where('usertype','!=','Administrator')->whereNotIn('employment_id',[9,10,12])->orderBy('username')->count();
}

function infoBoxRetiree()
{
	//TOTAL RETIREES
            $user = App\Users_with_age::whereIn('employment_id',[1,15])->get();
            $total_retiree = 0;
            foreach ($user as $users) {
                # code...
                // $age = Carbon::parse($users->birthdate)->age;
                if($users->age >= 60 &&  $users->age <= 65)
                {
                    $total_retiree++;
                }
            }
    return $total_retiree;
}

function getColor() {
   return dechex(rand(0x000000, 0xFFFFFF));
}

function totalAdmin($div)
{
	if($div == 'ALL')
	{
		$data = App\View_total_position_class::first();

		if(isset($data['total_admin']))
		{
			return $data['total_admin'];
		}
		else
		{
			return 0;
		}
			
	}
	else
	{
		$data = App\View_total_position_class_admin_by_division::where('division_id',$div)->first();
		// return $data['total'];
		if(isset($data['total']))
		{
			return $data['total'];
		}
		else
		{
			return 0;
		}
	}
	
}

function totalVacant($div)
{
	if($div == 'ALL')
	{
		$data = App\View_total_position_class::first();
		// return $data['total_vacant'];
		if(isset($data['total_vacant']))
		{
			return $data['total_vacant'];
		}
		else
		{
			return 0;
		}

	}
	else
	{
		$data = App\View_total_vacant_position_by_division::where('division_id',$div)->first();
		return $data['total'];
	}
	
}

function totalBarAdmin($div)
{	
	if($div == 'ALL')
	{
		return App\View_total_position_class_admin::where('total','>',0)->get();
	}
	else
	{
		$initial_arr = array();
	    $ctr = 0;
		foreach(DB::select('CALL GetAdminPosition("'.$div.'")') AS $arrs)
			{
				if($arrs->total > 0)
				{
					$initial_arr[$ctr]['position_desc'] = $arrs->position_desc;
					$initial_arr[$ctr]['total'] = $arrs->total;
					$ctr++;	
				}	
			}
		return json_decode(json_encode($initial_arr), FALSE);
	}
}

function totalTechnical($div)
{
	if($div == 'ALL')
	{
		$data = App\View_total_position_class::first();
		// return $data['total_technical'];
		if(isset($data['total_technical']))
		{
			return $data['total_technical'];
		}
		else
		{
			return 0;
		}	
	}
	else
	{
		$data = App\View_total_position_class_technical_by_division::where('division_id',$div)->first();
		// return $data['total'];
		if(isset($data['total']))
		{
			return $data['total'];
		}
		else
		{
			return 0;
		}
	}
}

function totalBarTechnical($div)
{
	if($div == 'ALL')
	{
		return App\View_total_position_class_technical::where('total','>',0)->get();
	}
	else
	{
		$initial_arr = array();
	    $ctr = 0;
		foreach(DB::select('CALL GetTechnicalPosition("'.$div.'")') AS $arrs)
			{
				if($arrs->total > 0)
				{
					$initial_arr[$ctr]['position_desc'] = $arrs->position_desc;
					$initial_arr[$ctr]['total'] = $arrs->total;
					$ctr++;	
				}	
			}
		return json_decode(json_encode($initial_arr), FALSE);
	}
}

function showLeaves() 
{
   $leave = App\Leave_type::whereIn('id',[1,2,3,6,7,9,11])->orderBy('leave_desc')->get();

   return $leave;
}

function showLeaveList() 
{
   $leave = App\Leave_type::whereIn('id',[1,2,3,5,6,7,9,11])->get();

   foreach ($leave as $leaves) {

   			//IF CTO
   			if($leaves->id == 5)
   			{
   				$lv = App\Employee_cto::select('cto_bal')->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->limit(1)->first();
   				if($lv)
   				{
   					$data[] = array("id" => $leaves->id,"leave_desc" => 'Compensatory Time-Off' ,"total" => $lv['cto_bal']);
   				}
   				else
   				{
   					$data[] = array("id" => $leaves->id,"leave_desc" => 'Compensatory Time-Off' ,"total" => 0);
   				}
   				
   			}
   			else
   			{
   				$lv = App\Employee_leave::select('leave_bal')->where('leave_id',$leaves->id)->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->limit(1)->first();
   				if($lv)
   				{
   					$data[] = array("id" => $leaves->id,"leave_desc" => $leaves->leave_desc ,"total" => $lv['leave_bal']);
   				}
   				else
   				{
   					$data[] = array("id" => $leaves->id,"leave_desc" => $leaves->leave_desc ,"total" => 0);
   				}
   				
   			}
   			
   }

   return $data;
}

function getLeaves($user_id,$leave_id) {
	if($leave_id == 5)
	{	
		$leave = App\Employee_cto::where('user_id',$user_id)->orderBy('id','DESC')->first();
		if($leave)
		{
			return $leave['cto_bal'];
		}
		else
		{
			return 0;
		}
   		
	}
	else if($leave_id == 16 || $leave_id == 17)
	{	
		return 999;
	}

	else if($leave_id == 1 || $leave_id == 2)
	{	
		// $leave = collect(App\TblSummary::where('user_id',$user_id)->orderBy('id','DESC')->first());
		// $leave = $leave->all();

		// $leave = App\TblSummary::where('user_id',$user_id)->orderBy('id','DESC')->first();
		$leave = App\DTRProcessed::where('userid',$user_id)->orderBy('id','DESC')->first();

   		if($leave_id == 1)
   		{
   			// return $leave['vl_bal'] + (1.25 - $leave['vl_leave']);
   			if($leave)
   			{
   				return $leave['vl_bal'];
   			}
   			else
   			{
   				return 0;
   			}
   			
   		}
   		else
   		{
   			// return $leave['sl_bal'] + (1.25 - $leave['sl_leave']);
   			if($leave)
   			{
   				return $leave['sl_bal'];
   			}
   			else
   			{
   				return 0;
   			}
   		}
	}
	else
	{
		$leave = collect(App\Employee_leave::where('user_id',$user_id)->where('leave_id',$leave_id)->orderBy('id','DESC')->first());
		$leave = $leave->all();

   		if(isset($leave['leave_bal']))
   		{
   			return $leave['leave_bal'];
   		}
   		else
   		{
   			return "0";
   		}
	}
   
}

function getLeaveList()
{
	$leave = App\Employee_leave::where('user_id',$user_id)->where('leave_id',$leave_id)->orderBy('created_at','DESC')->get();
	return $leave;
}

function totalMS($div)
{
	if($div == 'ALL')
	{
		$data = App\View_employee_education::where('educ_level','Master of Science')->count();
		return $data;	
	}
	else
	{
		$data = App\View_employee_education::where('division',$div)->where('educ_level','Master of Science')->count();
		return $data;
	}
}

function totalPHD($div)
{
	if($div == 'ALL')
	{
		$data = App\View_employee_education::where('educ_level','Doctor of Philosophy')->count();
		return $data;	
	}
	else
	{
		$data = App\View_employee_education::where('division',$div)->where('educ_level','Doctor of Philosophy')->count();
		return $data;
	}
}

function totalBS($div)
{
	if($div == 'ALL')
	{
		$data = App\View_employee_education::where('educ_level','College')->count();
		return $data;	
	}
	else
	{
		$data = App\View_employee_education::where('division',$div)->where('educ_level','Doctor of Philosophy')->count();
		return $data;
	}
}

function totalOTHERS($div)
{
	if($div == 'ALL')
	{
		$data = App\View_employee_education::where('educ_level','Doctor of Philosophy')->count();
		return $data;	
	}
	else
	{
		$data = App\View_employee_education::where('division',$div)->whereNotIn('educ_level',['Doctor of Philosophy','Master of Science','College'])->count();
		return $data;
	}
}


function formatStatus($status)
{
	switch ($status) {
		case 'Upload Vacancy Advise':
		case 'Re-upload Vacancy Advise for Reposting':
		case 'For FAD-Budget clearance':
		case 'Vacancy Posted':
				$s = "<span class='badge badge-warning'>";
			break;
		case 'For Posting':
		case 'For OED-ARMSS clearance':
		case 'Received':
		case "Vacancy Advice Uploaded":
				$s = "<span class='badge badge-info'>";
			break;
		case 'Posted':
		case 'Vacancy Advise Uploaded':
		case "For OED`s Approval":
		case "Cleared from FAD-Personnel":
		case "Cleared from FAD-Budget":
		case "Received from OED-ARMSS":
		case "Cleared from OED":
				$s = "<span class='badge badge-success'>";
			break;
		case 'Disapproved':
				$s = "<span class='badge badge-danger'>";
			break;
		default:
				$s = "<span class='badge badge-default bg-gray' >";
			break;
	}

	echo $s.$status."</span>";
}

function getApplicant($type,$id,$request_id,$request_status)
	{
		if($type == 'list')
		{

		}
		else
		{
			switch ($request_status) {
				case 'FAD shortlisted applicants':
						$app = App\Applicant_position_apply::where('vacant_plantilla_id',$id)->where('fad_shortlisted','YES')->count();
					break;

				case 'Division shortlisted applicants':
				case 'Sent to PSB':
				case 'Uploaded PSB Result':
						$app = App\Applicant_position_apply::where('vacant_plantilla_id',$id)->where('div_shortlisted','YES')->count();
					break;
				default:
						$app = App\Applicant_position_apply::where('vacant_plantilla_id',$id)->count();
					break;
			}
			
			if($app > 0)
			{
				echo "<a href='".url('recruitment/list-of-applicants/'.$id.'/'.$request_id)."'><span class='badge badge-danger'>".$app."</span></a>";
			}
			else
			{
				return "-";
			}
		}
		
	}

function getMyInvitation($type)
	{
		if($type == 'list')
		{
			return App\Invitation::where('user_id',Auth::user()->id)->whereIn('interested',['','Yes'])->get();
		}
		else
		{
			return App\Invitation::where('user_id',Auth::user()->id)->whereIn('interested',['','Yes'])->count();
		}
		
	}
function getVacantPlantillaInfo($id,$col)
{
	$plantilla = App\View_vacant_plantilla::where('id',$id)->first();
	return $plantilla[$col];
}

function counInvites($id)
{
	$total = App\Invitation::where('vacant_plantilla_id',$id)->count();

	$response = App\Invitation::where('vacant_plantilla_id',$id)->where('interested','!=','')->count();

	echo "<a href='".url('invites/pdf/'.$id)."' style='text-decoration:none' target='_blank'><b>".$response."/".$total."</b></a>";
}

function getInvites($id,$type)
{
	if($type == 'total')
	{
		return App\Invitation::where('vacant_plantilla_id',$id)->count();
	}
	else if($type == 'Yes')
	{
		return App\Invitation::where('vacant_plantilla_id',$id)->where('interested','Yes')->count();
	}
	else
	{
		return App\Invitation::where('vacant_plantilla_id',$id)->where('interested','No')->count();
	}

}

function getStaffDivision()
{
	return App\View_user::where('division',Auth::user()->division)->whereIn('employment_id',[1,13,14,15])->orderBy('lname','ASC')->get();
}

function getICOSDivision()
{
	return App\User::where('division',Auth::user()->division)->where('employment_id',8)->orderBy('lname','ASC')->get();
}

function getStaffDivision2($div)
{
	return App\View_user::where('division',$div)->whereIn('employment_id',[1,13,14,15])->orderBy('lname','ASC')->get();
}

function getAllStaffDivision()
{
	return App\User::where('division',Auth::user()->division)->whereIn('employment_id',[1,8,13,14])->orderBy('employment_id','ASC')->orderBy('lname','ASC')->get();
}

function getAllStaffDivision4()
{
	return App\User::where('division',Auth::user()->division)->whereIn('employment_id',[1,5,8,13,14,15])->orderBy('employment_id','ASC')->orderBy('lname','ASC')->get();
}

function getAllStaffDivision3($div)
{
	return App\User::where('division',$div)->whereIn('employment_id',[1,8,13,14])->orderBy('lname','ASC')->get();
}

function getStaffInfo($id,$type = null)
{
	$user = App\View_user::where('id',$id)->first();

	if(isset($user))
	{
		switch ($type) {

		case 'fullname':
		case 'empcode':
			# code...
				return $user->username;
			break;

		case 'position':
			# code...
				return $user->position_desc;
			break;

		case 'division':
			# code...
				return $user->division_acro;
			break;

		case 'division_id':
			# code...
				return $user->division;
			break;

		case 'fdos':
			# code...
				return $user->fldservice;
			break;
		
		default:
				return ucwords(strtolower($user['lname'])).", ".$user['fname']." ".$user['mname'];
			break;
		}
	}
}

function getStaffInfo2($empcode,$type = null)
{
	$user = App\View_user::where('username',$empcode)->first();

	if(isset($user))
	{
		switch ($type) {

		case 'position':
			# code...
				return $user->position_desc;
			break;

		case 'division':
			# code...
				return $user->division_acro;
			break;

		case 'division_id':
			# code...
				return $user->division;
			break;

		case 'fdos':
			# code...
				return $user->fldservice;
			break;
		
		default:
				return strtoupper($user['lname'].' '.$user['exname'].', '.$user['fname'].' '.substr($user['mname'],0,1));
			break;
		}
	}
}

function getStaffAllInfo($id)
{
	$user = App\User::where('id',$id)->first();
	return $user;
}

function getStaffID($id)
{
	$user = App\Employee_addinfo::where('user_id',$id)->first();
	return $user;
}

function getPlantillaItemInfo($col,$id)
{
	$user = App\View_vacant_plantilla::where('id',$id)->first();

	if(isset($user))
	{
		switch ($col) {
		case 'number':
				return $user->plantilla_item_number;
			break;
		case 'position':
				return $user->position_desc;
			break;
		case 'division':
				return $user->division_acro;
			break;
		}
	}
	else
	{
		return null;
	}
	
}

function date_sorter($a, $b) {
    return strtotime($a) - strtotime($b);
}

function getFile($sw,$type,$id) {

	switch ($sw) {
		case 'hiring':
				$file = App\Recruitment_file_history::where('request_id',$id)->where('file_type',$type)->orderBy('created_at','desc')->first();

				if(isset($file))
					{
						$dt = Carbon\Carbon::parse($file->created_at)->diffForHumans();
						$link = "<a href='".asset('../storage/app/'.$file['file_path'])."' target='_blank'><i class='fas fa-paperclip'></i></a> <small class='text-muted'>$dt</small>";
					}

			break;

		case 'hrd':
				$file = App\HRD_plan_division::where('id',$id)->first();

				if(isset($file))
					{
						$dt = Carbon\Carbon::parse($file->hrd_file_uploaded)->diffForHumans();
						$link = "<a href='".asset('../storage/app/'.$file['hrd_file_path'])."' target='_blank'><i class='fas fa-paperclip'></i></a> <small class='text-muted'>$dt</small>";
					}

			break;

		case 'hrdc':
				$file = App\HRD_plan::where('id',$id)->first();

				if(isset($file))
					{
						$dt = Carbon\Carbon::parse($file->file_consolidated_uploaded)->diffForHumans();
						$link = "<a href='".asset('../storage/app/'.$file['file_consolidated'])."' target='_blank'><i class='fas fa-paperclip'></i></a> <small class='text-muted'>$dt</small>";
					}

			break;
		case 'ipcr':
				$file = App\Performance_ipcr_staff::where('user_id',$id)->first();

				if(isset($file))
					{
						$dt = Carbon\Carbon::parse($file->ipcr_submitted_at)->diffForHumans();
						$link = "<a href='".asset('../storage/app/'.$file['ipcr_file_path'])."' target='_blank'><i class='fas fa-paperclip'></i></a> <small class='text-muted'>$dt</small>";
					}

			break;
	}

	if(isset($file))
	{
		echo $link;
	}
	else
	{
		echo "-";
	}
}

function getFileForApplicant($id)
{
	$letter_id = App\Request_for_hiring::select('id')->where('plantilla_id',$id)->first();

	//GET LAST FILE
	$file = App\Recruitment_file_history::where('request_id',$letter_id['id'])->where('file_type','Vacancy Advice')->orderBy('id','desc')->first();

	return asset('../storage/app/'.$file['file_path']);
}

function getTraining($id)
{
	return App\Employee_training::where('user_id',$id)->count();
}

function getFiles($type)
{
	switch ($type) {
		case 'applicant':
				$link = App\Applicant::where('user_id',Auth::user()->id)->first();
				
	        	$files[] = array("link" => $link['file_cv'],"desc" => "Resume");
	        	$files[] = array("link" => $link['file_appletter'],"desc" => "Application Letter");
	        	$files[] = array("link" => $link['file_trainingcert'],"desc" => "Training Certificate");
	        	$files[] = array("link" => $link['file_servicerecords'],"desc" => "Service Record");
	        	$files[] = array("link" => $link['file_evaluationcert'],"desc" => "Evaluation Report");
	        	$files[] = array("link" => $link['file_appointment'],"desc" => "Appointment");
	        	$files[] = array("link" => $link['file_oath'],"desc" => "Oath of Office");
	        	$files[] = array("link" => $link['file_duty'],"desc" => "Report for Duty");
			break;
		
		default:
			# code...
			break;
	}

	return $files;
}

function getAllCOS()
{
	return App\View_users_temp::get();
}

function getAllUser()
{
	return App\View_user::whereNotNull('lname')->where('lname','!=','..')->orderBy('lname')->orderBy('fname')->get();
}

function countCOS($div)
{
	return App\View_users_temp::where('division_acro',$div)->count();
}

function getMonth()
{
	$collection = collect([
							'1' => 'January', 
							'2' => 'Fabruary',
							'3' => 'March',
							'4' => 'April',
							'5' => 'May',
							'6' => 'June',
							'7' => 'July',
							'8' => 'August',
							'9' => 'September',
							'10' => 'October',
							'11' => 'November',
							'12' => 'December',
						]);

	return $collection->all();
}

function getLastEduc($userid)
{
	$educ = App\View_employee_education::where('id',$userid)->first();
	return $educ['educ_course'];
}

function getFirstICOS($division)
{
	$user = App\User::where('division',$division)->where('employment_id',8)->orderBy('lname','ASC')->first();

	if($user)
	{
		return $user['id'];
	}
	else
	{
		return 0;
	}
}
	
function countUndertimeIcos(Array $dtr,$dt,$dayDesc)
{	
	return countTotalTimeIcos($dtr['fldEmpDTRamIn'],$dtr['fldEmpDTRamOut'],$dtr['fldEmpDTRpmIn'],$dtr['fldEmpDTRpmOut'],$dtr['dtr_ot'],$dtr['fldEmpDTRotIn'],$dtr['fldEmpDTRotOut'],$dt,$dayDesc);
}

function countTotalTimeIcos($amIn,$amOut,$pmIn,$pmOut,$ot,$otIn = null,$otOut = null,$dt = null,$day = null)
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
		return 0;
	}
	else
	{
		return ($min1 + $min2) + (($h1 + $h2) * 60);
	}
}




