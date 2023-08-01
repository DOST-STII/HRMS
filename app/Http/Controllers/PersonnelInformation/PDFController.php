<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class PDFController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function servicerecord()
    {
    	$emp = App\User::where('id',request()->empid)->first();

    	//PURPOSE
    	switch (request()->serviceoption) {
    		case 'Attendance To':
    		case 'Official/Travel trip to':
    			# code...
    				$purpose = request()->serviceoption." ".request()->serviceto;
    			break;
    		
    		default:
    			# code...
    			$purpose = request()->serviceoption;
    			break;
    	}
    	//EMPLOYMENT OLD TABLE
    	$employment_old = App\Employee_work::where('username',$emp->username)->where('workexp_gov','!=',2)->orderBy('workexp_date_from')->get();

    	$tbl = "<tr>";
    	foreach ($employment_old as $employments_old) {
    		# code...
    		$tbl .= "<td>".$employments_old->workexp_date_from."</td><td>".$employments_old->workexp_date_to."</td><td>".$employments_old->workexp_title."</td><td>".$employments_old->workexp_empstatus."</td><td align='right'>".number_format($employments_old->workexp_salary,2)."</td><td align='center'>".$employments_old->workexp_company."</td><td align='center'></td></tr><tr>";
    	}

    	//EMPLOYMENT
    	$employment = App\View_plantilla_history::where('username',$emp->username)->orderBy('plantilla_date_from')->get();
    	
    	foreach ($employment as $employments) {
    		# code...
    		$to_date = $employments->plantilla_date_to;
    		if($employments->plantilla_date_to == '0000-00-00' || $employments->plantilla_date_to == null)
    		{
    			$to_date = "to date";
    		}

    		$tbl .= "<td>".$employments->plantilla_date_from."</td><td>".$to_date."</td><td>".$employments->position_desc."</td><td>".$employments->employment_desc."</td><td align='right'>".number_format($employments->plantilla_salary,2)."</td><td align='center'>PCAARRD</td><td align='center'>NAT'L</td></tr><tr>";
    	}

    	$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE html>
							<html>
							<head>
							  <title>HRMIS - Service Record</title>
							  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
							</head>
							<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
							</style>
							<body>
								<center>
									<h4 style="font-size:13px">
										Republic of the Philippines<br/>
										PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES<br/>
										RESEARCH AND DEVELOPMENT<br/>
										Los Baños, Laguna
									</h4>
								</center>

								<center><h3><b>SERVICE RECORD</b></h3></center>
								
								<table border="0" width="65%" style="left:20%;position:relative;font-size:11px">
									<tr>
										<td align="left">NAME</td>
										<td align="center"><b>'.$emp->lname.'</b></td>
										<td align="center"><b>'.$emp->fname.'</b></td>
										<td align="center"><b>'.$emp->mname.''.$emp->exname.'</b></td>
									</tr>
									<tr>
										<td align="left"></td>
										<td align="center" style="font-size:12px"><small>(SURNAME)</small></td>
										<td align="center" style="font-size:12px"><small>(GIVEN NAME)</small></td>
										<td align="center" style="font-size:12px"><small>(MIDDLE NAME)</small></td>
									</tr>
									<tr>
										<td align="left">BIRTH</td>
										<td align="center"><b>'.date('M d, Y',strtotime($emp->birthdate)).'</b></td>
										<td align="left" colspan="2"><b>'.$emp->birthplace.'</b></td>
									</tr>
									<tr>
										<td align="left"></td>
										<td align="center"style="font-size:12px"><small>(DATE)</small></td>
										<td align="center"style="font-size:12px"><small>(PLACE)</small></td>
									</tr>
								</table>
								<br>
								<center><span style="font-size:11px">This is to certify that the employee named herein above actually rendered service in the Philippine Council for Agriculture, Aquatic and Natural Resources Research and Development (PCAARRD) as indicated below and evidenced by the date and supporting papers.</center>

							<br>
							    <table width="100%" border="1" style="border-collapse:collapse; border-color:#000000; border-style:solid;font-size:11px">
								 <tr>
								  <td colspan="2" align="center">INCLUSIVE DATES</td>
								  <td colspan="3" align="center">RECORD OF APPOINTMENT</td>
								  <td rowspan="2" align="center">OFFICE ENTITY<br>of assignment</td>
								  <td rowspan="2" align="center">DIVISION<br>Branch</td>
								 </tr>
								 <tr>
								  <td align="center">From</td>
								  <td align="center">To</td>
								  <td align="center">POSITION</td>
								  <td align="center">STATUS</td>
								  <td align="center">SALARY</td>
								 </tr>
								 '.substr($tbl,0,-4).'
								</table>
								<br>
								<span>Purpose : <b>'.$purpose.'</b></span>
								<br>
								<br>
								<br>
								<br>
								<table width="100%">
								<tr>
									<td align="left" style="font-size:12px"><u>&#160&#160&#160 '.date("M d, Y").'&#160&#160&#160</u><br/>&#160&#160&#160&#160&#160&#160&#160&#160&#160<small style="font-size:11px">DATE</small></td>
								</tr>
								</table>
								<table width="100%">
								<tr>
									<td align="right">
									<span style="margin-right:60px;font-size:12px">Certified Correct</span>
									<br>
									<br>
									<br>
									</td>
								</tr>
								<tr>
									<td align="right">
									<span style="margin-right:35px;text-decoration:underline;font-size:12px;margin-top:10px">ADELINA S JIMENEZ</span>
									<br>
									<span style="margin-right:35px;font-size:12px">Administrative Officer V</span>
									</td>
								</tr>
								</table>
							</body>
							</html>')
		->setPaper('a4', 'portrait');
		return $pdf->stream();
    }

    public function pds()
    {

    	$arr = array();
    	array_push($arr,App\Employee_skill::where('user_id',Auth::user()->id)->count());
    	array_push($arr,App\Employee_association::where('user_id',Auth::user()->id)->count());
    	array_push($arr,App\Employee_nonacademic::where('user_id',Auth::user()->id)->count());
    	rsort($arr);

    	$data = [
            "empinfo" => App\User::where('id',Auth::user()->id)->first(),
            "basicinfo" => App\Employee_basicinfo::where('user_id',Auth::user()->id)->first(),
			"contact" => App\Employee_contact::where('user_id',Auth::user()->id)->first(),
            "addinfo" => App\Employee_addinfo::where('user_id',Auth::user()->id)->first(),
            "family" => App\Employee_family::where('user_id',Auth::user()->id)->first(),
            "education" => App\Employee_education::where('user_id',Auth::user()->id)->get(),
            "organization" => App\Employee_organization::where('user_id',Auth::user()->id)->get(),
            "eligibility" => App\Employee_eligibility::where('user_id',Auth::user()->id)->get(),
            "training" => App\Employee_training::where('user_id',Auth::user()->id)->orderBy('training_inclusive_dates','desc')->get(),
            "work" => App\Employee_work::where('user_id',Auth::user()->id)->orderBy('workexp_date_from','desc')->get(),
			"work_agency" => App\View_employee_position::where('id',Auth::user()->id)->orderBy('plantilla_date_from','desc')->get(),
            'total_row' => $arr,
            'skill' => App\Employee_skill::where('user_id',Auth::user()->id)->pluck('skill_desc')->toArray(),
            'recognition' => App\Employee_nonacademic::where('user_id',Auth::user()->id)->pluck('academic_desc')->toArray(),
            'association' => App\Employee_association::where('user_id',Auth::user()->id)->pluck('assoc_desc')->toArray(),
			"residential" => App\Employee_address_residential::where('user_id',Auth::user()->id)->first(),
			"permanent" => App\Employee_address_permanent::where('user_id',Auth::user()->id)->first(),
			"reference" => App\Employee_reference::where('user_id',Auth::user()->id)->get(),
			"child" => App\Employee_children::where('user_id',Auth::user()->id)->get(),
        ];
    	return view('pis.reports.pds')->with("data",$data);
    }

    public function positionClass($div,$type)
    {
    	if($type == 'Vacant')
    	{
    		$title = 'Vacant Position';
    		$emp = new App\View_vacant_plantilla;

	    	if($div == 'ALL')
	    	{
	    		$emp = $emp->get();
	    	}
	    	else
	    	{
	    		$emp = $emp->where('division_id',$div)->get();
	    	}
	    	$td = '<td style="width: 10px" align="center"><b>#</b></td>
								<td align="center"><b>Division</b></td>
								<td align="center"><b>Plantilla Item</b></td>
								<td align="center"><b>Position</b></td>
								<td align="center"><b>Salary</b></td>';
	    	$lst = "";
	    	$ctr = 1;
	    	foreach ($emp as $emps) {
	    		$lst .= '<tr><td>'.$ctr.'</td><td>'.getDivision($emps->division_id).'</td><td>'.$emps->plantilla_item_number.'</td><td>'.$emps->position_desc.'</td><td>P'.formatNumber('currency',$emps->plantilla_salary).'</td></tr>';
	    		$ctr++;
	    	}
    	}
    	else
    	{
    		$title = 'Position Classification - '.$type;
    		$emp = new App\View_employee_position;

	    	if($div == 'ALL')
	    	{
	    		$emp = $emp->where('position_class',$type)->get();
	    	}
	    	else
	    	{
	    		$emp = $emp->where('division',$div)->where('position_class',$type)->get();
	    	}
	    	$td = '<td style="width: 10px" align="center"><b>#</b></td>
								<td align="center"><b>Employee</b></td>
								<td align="center"><b>Division</b></td>
								<td align="center"><b>Plantilla Item</b></td>
								<td align="center"><b>Position</b></td>';
	    	$lst = "";
	    	$ctr = 1;
	    	foreach ($emp as $emps) {
	    		$lst .= '<tr><td>'.$ctr.'</td><td>'.$emps->lname.', '.$emps->fname.' '.$emps->mname.'</td><td>'.getDivision($emps->division).'</td><td>'.$emps->plantilla_item_number.'</td><td>'.$emps->position_desc.'</td></tr>';
	    		$ctr++;
	    	}
    	}
    	

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>HRMS - Position Classification</title>
						</head>
						<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
							</style>
						<body>
						<center><h2>'.$title.'</h2></center>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								'.$td.'
							</tr>'.$lst.'
						</table>
						</body>
						</html>')
		->setPaper('a4', 'portrait');
		return $pdf->stream();
    }

    public function positionDesc($div,$type)
    {
    	$emp = new App\View_employee_position;

    	if($div == 'ALL')
    	{
    		$emp = $emp->where('position_desc',$type)->get();
    	}
    	else
    	{
    		$emp = $emp->where('division',$div)->where('position_desc',$type)->get();
    	}

    	$lst = "";
    	$ctr = 1;
    	foreach ($emp as $emps) {
    		$lst .= '<tr><td>'.$ctr.'</td><td>'.$emps->lname.', '.$emps->fname.' '.$emps->mname.'</td><td>'.getDivision($emps->division).'</td><td>'.$emps->plantilla_item_number.'</td></tr>';
    		$ctr++;
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>HRMS - Position Description</title>
						</head>
						<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
							</style>
						<body>
						<center><h2>Position Description - '.$type.'</h2></center>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width: 10px" align="center"><b>#</b></td>
								<td align="center"><b>Employee</b></td>
								<td align="center"><b>Division</b></td>
								<td align="center"><b>Plantilla Item</b></td>
							</tr>'.$lst.'
						</table>
						</body>
						</html>')
		->setPaper('a4', 'portrait');
		return $pdf->stream();
    }

    public function trainingsList($div)
    {
    	$emp = new App\View_employee_training;
    	$emp = $emp->where('division_acro',$div)->whereYear('training_date_from','>=',2015)->get();

    	// if($div == 'ALL')
    	// {
    	// 	$emp = $emp->where('position_desc',$type)->get();
    	// }
    	// else
    	// {
    	// 	$emp = $emp->where('division',$div)->where('position_desc',$type)->get();
    	// }

    	$lst = "";
    	$ctr = 1;
    	foreach ($emp as $emps) {
    		$lst .= '<tr><td valign="top" align="center">'.$ctr.'</td><td valign="top">'.$emps->training_title.'</td><td valign="top">'.$emps->fullname.'</td><td align="center">'.$emps->training_type.'</td><td valign="top">'.$emps->training_amount.'</td><td valign="top">'.$emps->training_inclusive_dates.'</td><td align="center">'.$emps->training_hours.'</td><td valign="top" style="word-wrap: break-word">'.$emps->training_ld.'</td><td valign="top">'.$emps->training_conducted_by.'</td></tr>';
    		$ctr++;
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>HRMS - Training List</title>
						</head>
						<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
							</style>
						<body>
						<center><h2>Training List - '.$div.'</h2></center>
						<table border="1" width="100%" style="font-size:11px;table-layout:fixed;" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width:2%" align="center"><b>#</b></td>
								<td style="width:30%" align="center"><b>Title</b></td>
								<td align="center"><b>Employee</b></td>
								<td align="center"><b>Type</b></td>
								<td align="center"><b>Amount</b></td>
								<td align="center"><b>Dates</b></td>
								<td align="center"><b>Hours</b></td>
								<td align="center"><b>LD</b></td>
								<td align="center"><b>Conducted By</b></td>
							</tr>'.$lst.'
						</table>
						</body>
						</html>')
		->setPaper('a4', 'landscape');
		return $pdf->stream();
    }

    public function educationClass($div,$type)
    {
    	$title = 'Education - '.$type;
    	$emp = new App\View_employee_education;

    	if($type == 'MS')
    	{
	    	if($div == 'ALL')
	    	{
	    		$emp = $emp->where('educ_level','Master of Science')->get();
	    	}
	    	else
	    	{
	    		$emp = $emp->where('division',$div)->where('educ_level','Master of Science')->get();
	    	}
	    	
    	}
    	elseif($type == 'PhD')
    	{
	    	if($div == 'ALL')
	    	{
	    		$emp = $emp->where('educ_level','Doctor of Philosophy')->get();
	    	}
	    	else
	    	{
	    		$emp = $emp->where('division',$div)->where('educ_level','Doctor of Philosophy')->get();
	    	}
	    	
    	}
    	else
    	{
    		if($div == 'ALL')
	    	{
	    		$emp = $emp->whereNotIn('educ_level',['Doctor of Philosophy','Master of Science'])->get();
	    	}
	    	else
	    	{
	    		$emp = $emp->where('division',$div)->whereNotIn('educ_level',['Doctor of Philosophy','Master of Science'])->get();
	    	}
    	}
    		$td = '<td style="width: 10px" align="center"><b>#</b></td>
								<td align="center"><b>Staff</b></td>
								<td align="center"><b>Division</b></td>
								<td align="center"><b>Course</b></td>';
    		$lst = "";
	    	$ctr = 1;
	    	foreach ($emp as $emps) {
	    		$lst .= '<tr><td>'.$ctr.'</td><td>'.$emps->lname.', '.$emps->fname.' '.$emps->mname.'</td><td>'.$emps->division_acro.'</td><td>'.$emps->educ_course.'</td></tr>';
	    		$ctr++;
	    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>HRMS - Employee Education</title>
						</head>
						<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
							</style>
						<body>
						<center><h2>'.$title.'</h2></center>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								'.$td.'
							</tr>'.$lst.'
						</table>
						</body>
						</html>')
		->setPaper('a4', 'portrait');
		return $pdf->stream();
    }

    public function invites($id)
    {
    	$title = 'List of Invited Staff for the Vacant Position';

    	$plantilla = App\View_vacant_plantilla::where('id',$id)->first();

    	$inv = App\View_for_pdf_invitation::where('vacant_plantilla_id',$id)->get();

    	$ctr = 1;
    	$lst = "";
    	foreach ($inv as $invs) {
    		$lst .= '<tr><td valign="top" align="center">'.$ctr.'</td><td>'.$invs->lname.', '.$invs->fname.' '.$invs->mname.'</td><td>'.$invs->division_acro.'</td><td>'.$invs->position_desc.'</td><td align="center">'.$invs->interested.'</td></tr>';
    		$ctr++;
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>HRMS - Call for Application</title>
						</head>
						<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
							</style>
						<body>
						<center><h2>'.$title.'</h2></center>

						<table border="0" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td><b>Item Number</b> : '.$plantilla['plantilla_item_number'].'</td><td><b>Position</b> : '.$plantilla['position_desc'].'</td>
							</tr>
							<tr>
								<td><b>Division</b> : '.$plantilla['division_acro'].'</td><td></td>
							</tr>
							<tr>
								<td><br/><b>Total invites</b> : <u>'.getInvites($id,'total').'</u> <br/><b>Interested</b> :  <u>'.getInvites($id,'Yes').'</u> <br/><b>Not Interested</b> : <u>'.getInvites($id,'No').'</u></td><td></td>
							</tr>
						</table>
						<br>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width:5%" align="center"><b>#</b></td>
								<td><b>Family Name, FN, MI</b></td>
								<td><b>Division</b></td>
								<td><b>Position</b></td>
								<td style="width:5%"><b>Interested</b></td>
							</tr>
							'.$lst.'
						</table>
						</body>
						</html>')
		->setPaper('a4', 'portrait');
		return $pdf->stream();
    }

    public function previewInvitation()
    {
    	// print_r(request()->selected);
    	$title = 'Call for Application - Preview';

    	$plantilla = App\View_vacant_plantilla::where('id',request()->plantilla_id)->first();

    	$emp = App\View_user::whereIn('id',request()->selected)->get();


    	$ctr = 1;
    	$lst = "";
    	foreach ($emp as $emps) {
    		$lst .= '<tr><td valign="top" align="center">'.$ctr.'</td><td>'.$emps->lname.', '.$emps->fname.' '.$emps->mname.'</td><td>'.$emps->division_acro.'</td><td>'.$emps->position_desc.'</td></tr>';
    		$ctr++;
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>HRMS - Call for Application | Preview</title>
						</head>
						<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
							</style>
						<body>
						<center><h2>'.$title.'</h2></center>

						<table border="0" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td><b>Item Number</b> : '.$plantilla['plantilla_item_number'].'</td><td><b>Position</b> : '.$plantilla['position_desc'].'</td>
							</tr>
							<tr>
								<td><b>Division</b> : '.$plantilla['division_acro'].'</td><td></td>
							</tr>
						</table>
						<br>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width:5%" align="center"><b>#</b></td>
								<td><b>Employee</b></td>
								<td><b>Division</b></td>
								<td><b>Position</b></td>
							</tr>
							'.$lst.'
						</table>
						</body>
						</html>')
		->setPaper('a4', 'portrait');
		return $pdf->stream();
    }

    public function hiringHistory($id)
    {
    	$title = 'Hiring History';

    	$req = App\Recruitment_history::where('request_id',$id)->orderBy('created_at','desc')->get();

    	$ctr = 1;
    	$lst = "";
    	foreach ($req as $reqs) {
    		$lst .= '<tr><td valign="top" align="center">'.$ctr.'</td><td>'.$reqs->request_status.'</td><td align="center">'.$reqs->created_by.'</td><td align="center">'.$reqs->created_at.'</td></tr>';
    		$ctr++;
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>HRMS - Hiring History</title>
						</head>
						<style type="text/css">
								body
								{
									font-family:Helvetica;
								}
							</style>
						<body>
						<center><h2>'.$title.'</h2></center>

						<br>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width:5%" align="center"><b>#</b></td>
								<td><b>STATUS</b></td>
								<td align="center"><b>DIVISION</b></td>
								<td align="center"><b>DATE/TIME</b></td>
							</tr>
							'.$lst.'
						</table>
						</body>
						</html>')
		->setPaper('a4', 'portrait');
		return $pdf->stream();
    }

    public function hrddegree($degree_id)
    {
    	$title = 'HRMS | HUMAN RESOURCE DEVELOPMENT PLAN';

    	$hrd_degree = App\View_hrd_division::where('id',$degree_id)->first();

    	$local = App\HRD_plan_degree::where('hrd_plan_division_id',$degree_id)->where('hrd_degree_type','Local')->where('division_id',Auth::user()->division)->get();

    	$locallst = "<tr><td colspan='8'>A. Local</td></tr>";
    	foreach ($local as $locals) {

    		$td = '<td></td><td align="center"><b>&#10004<b></td>';
    		if($locals->hrd_degree_program == 'PhD')
    		{
    			$td = '<td align="center"><b>&#10004<b></td><td></td>';
    		}

    		$td2 = '<td></td><td align="center"><b>&#10004<b></td>';
    		if($locals->hrd_degree_area == '1st sem of SY')
    		{
    			$td2 = '<td align="center"><b>&#10004<b></td><td></td>';
    		}

    		$locallst .= '<tr><td>'.getStaffInfo($locals->user_id).'</td><td>'.getStaffInfo($locals->user_id,'position').'</td>'.$td.'<td>'.$locals->hrd_degree_university.'</td>'.$td2.'<td>'.$locals->hrd_degree_remarks.'</td></tr>';
    	}

    	$foreign = App\HRD_plan_degree::where('hrd_plan_division_id',$degree_id)->where('hrd_degree_type','Foreign')->where('division_id',Auth::user()->division)->get();

    	$foreignlist = "<tr><td colspan='8'>B. Foreign</td></tr>";
    	foreach ($foreign as $foreigns) {

    		$td = '<td></td><td align="center"><b>&#10004<b></td>';
    		if($foreigns->hrd_degree_program == 'PhD')
    		{
    			$td = '<td align="center"><b>■<b></td><td></td>';
    		}

    		$td2 = '<td></td><td align="center"><b>&#10004<b></td>';
    		if($foreigns->hrd_degree_area == '1st sem of SY')
    		{
    			$td2 = '<td align="center"><b>&#10004<b></td><td></td>';
    		}

    		$foreignlist .= '<tr><td>'.getStaffInfo($foreigns->user_id).'</td><td>'.getStaffInfo($foreigns->user_id,'position').'</td>'.$td.'<td>'.$foreigns->hrd_degree_university.'</td>'.$td2.'<td>'.$foreigns->hrd_degree_remarks.'</td></tr>';
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>'.$title.'</title>
						</head>
						<style type="text/css">
								body
								{
									font-family: DejaVu Sans;
								}
							</style>
						<body>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td align="center" rowspan="3"><img src="'.asset('img/DOST.png').'" style="width:70px"></td>
								<td><center><b>PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES RESEARCH AND DEVELOPMENT</b></center></td>
								<td>DOCUMENT NUMBER</td>
								<td>QMSF-FADPS-07-01-09</td>
							</tr>
							<tr>
								<td rowspan="2" style="font-size:20px"><center><b>HUMAN RESOURCE DEVELOPMENT PLAN</b></center></td>
								<td>REVISION NUMBER</td>
								<td><center>1</center></td>
							</tr>
							<tr>
								<td>PAGE NUMBER</td>
								<td><center>1/3</center></td>
							</tr>
							<tr>
								<td><center><b>TITLE</b></center></td>
								<td><center><b>YEAR '.$hrd_degree->hrd_year.' - '.$hrd_degree->hrd_year2.'</b></center></td>
								<td>EFFECTIVITY DATE</td>
								<td><center>May 21, 2019</center></td>
							</tr>
						</table>
						<br>
						<p>Division : '.$hrd_degree->division_acro.'</p>
						<p><h4><b>1. Degree Program</b></h4></p>

						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
                                     <tr>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>NAME</b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>POSITION</b></td>
                                        <td align="center" colspan="2"><small><b>DEGREE PROGRAM</small></b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>PROPOSED UNIVERSITY</b></td>
                                        <td align="center" colspan="2"><small><b>TARGET DATE (PLS CHECK)</small></b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>REMARKS</b></td>
                                     </tr>
                                     <tr>
                                       <td align="center" ><b><small>PhD</small></b></td>
                                       <td align="center" ><b><small>MS</small></b></td>
                                       <td align="center" ><b><small>1st sem of SY</small></b></td>
                                       <td align="center" ><b><small>2nd  sem of SY</small></b></td>
                                     </tr>
                            '.$locallst.'
                            '.$foreignlist.'
                        </table>
                        <br>
                        <br>
                        <br>
						<table width="35%" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width :40%">Recommended by:</td><td align="center" style="border-bottom : 1px solid #000;width :60%">'.getDirectorNoDesc($hrd_degree->division_id).'</td>
							</tr>
							<tr>
								<td></td><td align="center">Division Director</td>
							</tr>
						</table>
						</body>
						</html>')
		->setPaper('legal', 'landscape');
		return $pdf->stream();
    }

    public function hrdnondegree($degree_id)
    {
    	$title = 'HRMS | HUMAN RESOURCE DEVELOPMENT PLAN';

    	$hrd_degree = App\View_hrd_division::where('id',$degree_id)->first();

    	$local = App\View_hrd_plan_non_degree::where('hrd_plan_division_id',$degree_id)->where('hrd_non_degree_type','Local')->where('division_id',Auth::user()->division)->orderBy('fullname')->get();

    	$locallst = "<tr><td colspan='15'>A. Local</td></tr>";
    	foreach ($local as $locals) {

    		$locallst .= '<tr><td>'.$locals->fullname.'</td><td>'.$locals->position_desc.'</td><td align="center">'.$locals->hrd_non_degree_priority.'</td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Management/ Supervisory/ Leadership').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'R&d Related Trainings').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Skills Enhancement').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Information & Communication Technology (ict)').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Information, Education & Communication (iec)').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Value Enhancement').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'General Administration/ Governance').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Others').'</b></td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$locals->hrd_non_degree_target_q1).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$locals->hrd_non_degree_target_q2).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$locals->hrd_non_degree_target_q3).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$locals->hrd_non_degree_target_q4).'</td>

    				</tr>';
    	}


    	$foreign = App\View_hrd_plan_non_degree::where('hrd_plan_division_id',$degree_id)->where('hrd_non_degree_type','Foreign')->where('division_id',Auth::user()->division)->orderBy('fullname')->get();

    	$foreignlst = "<tr><td colspan='15'>A. Local</td></tr>";
    	foreach ($foreign as $foreigns) {

    		$foreignlst .= '<tr><td>'.$foreigns->fullname.'</td><td>'.$foreigns->position_desc.'</td><td align="center">'.$foreigns->hrd_non_degree_priority.'</td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Management/ Supervisory/ Leadership').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'R&d Related Trainings').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Skills Enhancement').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Information & Communication Technology (ict)').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Information, Education & Communication (iec)').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Value Enhancement').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'General Administration/ Governance').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Others').'</b></td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$foreigns->hrd_non_degree_target_q1).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$foreigns->hrd_non_degree_target_q2).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$foreigns->hrd_non_degree_target_q3).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$foreigns->hrd_non_degree_target_q4).'</td>

    				</tr>';
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>'.$title.'</title>
						</head>
						<style type="text/css">
								body
								{
									font-family: DejaVu Sans;
								}
							</style>
						<body>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td align="center" rowspan="3"><img src="'.asset('img/DOST.png').'" style="width:70px"></td>
								<td><center><b>PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES RESEARCH AND DEVELOPMENT</b></center></td>
								<td>DOCUMENT NUMBER</td>
								<td>QMSF-FADPS-07-01-09</td>
							</tr>
							<tr>
								<td rowspan="2" style="font-size:20px"><center><b>HUMAN RESOURCE DEVELOPMENT PLAN</b></center></td>
								<td>REVISION NUMBER</td>
								<td><center>1</center></td>
							</tr>
							<tr>
								<td>PAGE NUMBER</td>
								<td><center>2/3</center></td>
							</tr>
							<tr>
								<td><center><b>TITLE</b></center></td>
								<td><center><b>YEAR '.$hrd_degree->hrd_year.'</b></center></td>
								<td>EFFECTIVITY DATE</td>
								<td><center>May 21, 2019</center></td>
							</tr>

						</table>
						<br>
						<p>Division : '.$hrd_degree->division_acro.'</p>
						<p><h4><b>2. Non-Degree Program</b></h4></p>

						<table border="1" width="100%" style="font-size:9px;" cellpadding="2" cellspacing="0">
							<tr>
                                      <td align="center" style="vertical-align: middle;" rowspan="2"><b>NAME</b></td>
                                      <td align="center" style="vertical-align: middle;" rowspan="2"><b>POSITION</b></td>
                                      <td align="center" style="vertical-align: middle;width:15px" rowspan="2"><b>TRAINING PRIORITIZATION</b></td>
                                      <td align="center" style="vertical-align: middle;" colspan="8"><small><b>AREA OF DISCIPLINE</b></small></small></td>
                                      <td align="center" style="vertical-align: middle;" colspan="4"><small><b>TARGET DATE</b></small></td>
                                    </tr>
                                     <tr style="font-size: 8px">
                                        
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>MANAGEMENT/ SUPERVISORY/ LEADERSHIP</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>R&D RELATED TRAININGS</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>SKILLS ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>INFORMATION & COMMUNICATION TECHNOLOGY (ICT)</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>INFORMATION, EDUCATION & COMMUNICATION (IEC)</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>VALUE ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>GENERAL ADMINISTRATION/ GOVERNANCE</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>OTHERS</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><b>Q1</b></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><b>Q2</b></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><b>Q3</b></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><b>Q4</b></td>
                                     </tr>

                            <tbody>
							'.$locallst.'
							'.$foreignlst.'
							</tbody>
						</table>
						
                        <br>
                        <br>
                        <br>
						<table width="35%" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width :40%">Recommended by:</td><td align="center" style="border-bottom : 1px solid #000;width :60%">'.getDirector($hrd_degree->division_id).'</td>
							</tr>
							<tr>
								<td></td><td align="center">Division Director</td>
							</tr>
						</table>
						</body>
						</html>')
		->setPaper('legal', 'landscape');
		return $pdf->stream();
    }

    public function hrdconsolidated($hrd_id)
    {
    	$title = 'HRMS | HUMAN RESOURCE DEVELOPMENT PLAN';

    	$division = App\View_hrd_division::where('hrd_plan_id',$hrd_id)->whereNotNull('submitted_at')->get();

    	// $division = App\Division::whereNull('type')->get();

    	$html = "";

    	foreach ($division as $divisions) {

    		//TABLE
    		$local = App\View_hrd_plan_non_degree::where('hrd_plan_division_id',$divisions->id)->where('hrd_non_degree_type','Local')->orderBy('fullname')->get();

    		$locallst = '<tr><td colspan="15">A. Local</td></tr>';

    		foreach ($local as $locals) {

    		$locallst .= '<tr><td>'.$locals->fullname.'</td><td>'.$locals->position_desc.'</td><td align="center">'.$locals->hrd_non_degree_priority.'</td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Management/ Supervisory/ Leadership').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'R&d Related Trainings').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Skills Enhancement').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Information & Communication Technology (ict)').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Information, Education & Communication (iec)').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Value Enhancement').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'General Administration/ Governance').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$locals->id,'Others').'</b></td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$locals->hrd_non_degree_target_q1).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$locals->hrd_non_degree_target_q2).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$locals->hrd_non_degree_target_q3).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$locals->hrd_non_degree_target_q4).'</td>

    				</tr>';
    			}

    		$foreign = App\View_hrd_plan_non_degree::where('hrd_plan_division_id',$divisions->id)->where('hrd_non_degree_type','Foreign')->orderBy('fullname')->get();

    	$foreignlst = "<tr><td colspan='15'>B. Foreign</td></tr>";
    	foreach ($foreign as $foreigns) {

    		$foreignlst .= '<tr><td>'.$foreigns->fullname.'</td><td>'.$foreigns->position_desc.'</td><td align="center">'.$foreigns->hrd_non_degree_priority.'</td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Management/ Supervisory/ Leadership').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'R&d Related Trainings').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Skills Enhancement').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Information & Communication Technology (ict)').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Information, Education & Communication (iec)').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Value Enhancement').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'General Administration/ Governance').'</b></td>
    				<td align="center" style="vertical-align: middle;"><b>'.getAreasDiscipline('pdf',$foreigns->id,'Others').'</b></td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$foreigns->hrd_non_degree_target_q1).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$foreigns->hrd_non_degree_target_q2).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$foreigns->hrd_non_degree_target_q3).'</td>
    				<td align="center" style="vertical-align: middle;">'.getQuarter('pdf',$foreigns->hrd_non_degree_target_q4).'</td>

    				</tr>';
    	}

    		$html .= '<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td align="center" rowspan="3"><img src="'.asset('img/DOST.png').'" style="width:70px"></td>
								<td><center><b>PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES RESEARCH AND DEVELOPMENT</b></center></td>
								<td>DOCUMENT NUMBER</td>
								<td>QMSF-FADPS-07-01-09</td>
							</tr>
							<tr>
								<td rowspan="2" style="font-size:20px"><center><b>HUMAN RESOURCE DEVELOPMENT PLAN</b></center></td>
								<td>REVISION NUMBER</td>
								<td><center>1</center></td>
							</tr>
							<tr>
								<td>PAGE NUMBER</td>
								<td><center>2/3</center></td>
							</tr>
							<tr>
								<td><center><b>TITLE</b></center></td>
								<td><center><b>YEAR '.$divisions->hrd_year.'</b></center></td>
								<td>EFFECTIVITY DATE</td>
								<td><center>May 21, 2019</center></td>
							</tr>

						</table>
						<br>
						<p>Division : '.$divisions->division_acro.'</p>
						

						<table border="1" width="100%" style="font-size:9px;" cellpadding="2" cellspacing="0">
							<tr>
                                      <td align="center" style="vertical-align: middle;" rowspan="2"><b>NAME</b></td>
                                      <td align="center" style="vertical-align: middle;" rowspan="2"><b>POSITION</b></td>
                                      <td align="center" style="vertical-align: middle;width:15px" rowspan="2"><b>TRAINING PRIORITIZATION</b></td>
                                      <td align="center" style="vertical-align: middle;" colspan="8"><small><b>AREA OF DISCIPLINE</b></small></small></td>
                                      <td align="center" style="vertical-align: middle;" colspan="4"><small><b>TARGET DATE</b></small></td>
                                    </tr>
                                     <tr style="font-size: 8px">
                                        
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>MANAGEMENT/ SUPERVISORY/ LEADERSHIP</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>R&D RELATED TRAININGS</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>SKILLS ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>INFORMATION & COMMUNICATION TECHNOLOGY (ICT)</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>INFORMATION, EDUCATION & COMMUNICATION (IEC)</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>VALUE ENHANCEMENT</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>GENERAL ADMINISTRATION/ GOVERNANCE</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><small><b>OTHERS</b></small></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><b>Q1</b></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><b>Q2</b></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><b>Q3</b></td>
                                        <td align="center" style="vertical-align: middle;width:5px"><b>Q4</b></td>
                                     </tr>

                            <tbody>
                            	'.$locallst.'
                            	'.$foreignlst.'
							</tbody>
						</table>
						
                        <br>
                        <br>
                        <br>
						<table width="25%" cellpadding="2" cellspacing="0" style="font-size : 9px">
							<tr>
								<td style="width :40%">Recommended by:</td><td align="center" style="border-bottom : 1px solid #000;width :60%">'.getDirector($divisions->division_id).'</td>
							</tr>
							<tr>
								<td></td><td align="center">Division Director</td>
							</tr>
						</table>
						<div class="page-break"></div>
						';
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>'.$title.'</title>
						</head>
						<style type="text/css">
								body
								{
									font-family: DejaVu Sans;
								}
								.page-break {
									 page-break-after: always;
									}
							</style>
						<body>
							'.$html.'
						</body>
						</html>')
		->setPaper('legal', 'landscape');
		return $pdf->stream();
    }

    public function hrdconsolidated2($hrd_id)
    {
    	$title = 'HRMS | HUMAN RESOURCE DEVELOPMENT PLAN';

    	$division = App\View_hrd_division::where('hrd_plan_id',$hrd_id)->whereNotNull('submitted_at')->get();

    	// $division = App\Division::whereNull('type')->get();

    	$html = "";

    	foreach ($division as $divisions)
    	{
			$local = App\HRD_plan_degree::where('hrd_plan_division_id',$divisions->id)->where('hrd_degree_type','Local')->get();

	    	$locallst = "<tr><td colspan='8'>A. Local</td></tr>";
	    	foreach ($local as $locals) {

	    		$td = '<td></td><td align="center"><b>&#10004<b></td>';
	    		if($locals->hrd_degree_program == 'PhD')
	    		{
	    			$td = '<td align="center"><b>&#10004<b></td><td></td>';
	    		}

	    		$td2 = '<td></td><td align="center"><b>&#10004<b></td>';
	    		if($locals->hrd_degree_area == '1st sem of SY')
	    		{
	    			$td2 = '<td align="center"><b>&#10004<b></td><td></td>';
	    		}

	    		$locallst .= '<tr><td>'.getStaffInfo($locals->user_id).'</td><td>'.getStaffInfo($locals->user_id,'position').'</td>'.$td.'<td>'.$locals->hrd_degree_university.'</td>'.$td2.'<td>'.$locals->hrd_degree_remarks.'</td></tr>';
	    	}

	    	$foreign = App\HRD_plan_degree::where('hrd_plan_division_id',$divisions->id)->where('hrd_degree_type','Foreign')->get();

	    	$foreignlist = "<tr><td colspan='8'>B. Foreign</td></tr>";
	    	foreach ($foreign as $foreigns) {

	    		$td = '<td></td><td align="center"><b>&#10004<b></td>';
	    		if($foreigns->hrd_degree_program == 'PhD')
	    		{
	    			$td = '<td align="center"><b>■<b></td><td></td>';
	    		}

	    		$td2 = '<td></td><td align="center"><b>&#10004<b></td>';
	    		if($foreigns->hrd_degree_area == '1st sem of SY')
	    		{
	    			$td2 = '<td align="center"><b>&#10004<b></td><td></td>';
	    		}

	    		$foreignlist .= '<tr><td>'.getStaffInfo($foreigns->user_id).'</td><td>'.getStaffInfo($foreigns->user_id,'position').'</td>'.$td.'<td>'.$foreigns->hrd_degree_university.'</td>'.$td2.'<td>'.$foreigns->hrd_degree_remarks.'</td></tr>';
	    	}	
    	

    		$html .= '<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td align="center" rowspan="3"><img src="'.asset('img/DOST.png').'" style="width:70px"></td>
								<td><center><b>PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES RESEARCH AND DEVELOPMENT</b></center></td>
								<td>DOCUMENT NUMBER</td>
								<td>QMSF-FADPS-07-01-09</td>
							</tr>
							<tr>
								<td rowspan="2" style="font-size:20px"><center><b>HUMAN RESOURCE DEVELOPMENT PLAN</b></center></td>
								<td>REVISION NUMBER</td>
								<td><center>1</center></td>
							</tr>
							<tr>
								<td>PAGE NUMBER</td>
								<td><center>1/3</center></td>
							</tr>
							<tr>
								<td><center><b>TITLE</b></center></td>
								<td><center><b>YEAR '.$divisions->hrd_year.'</b></center></td>
								<td>EFFECTIVITY DATE</td>
								<td><center>May 21, 2019</center></td>
							</tr>
						</table>
						<br>
						<p>Division : '.$divisions->division_acro.'</p>
						<p><h4><b>1. Degree Program</b></h4></p>

						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
                                     <tr>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>NAME</b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>POSITION</b></td>
                                        <td align="center" colspan="2"><small><b>DEGREE PROGRAM</small></b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>PROPOSED UNIVERSITY</b></td>
                                        <td align="center" colspan="2"><small><b>TARGET DATE (PLS CHECK)</small></b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>REMARKS</b></td>
                                     </tr>
                                     <tr>
                                       <td align="center" ><b><small>PhD</small></b></td>
                                       <td align="center" ><b><small>MS</small></b></td>
                                       <td align="center" ><b><small>1st sem of SY</small></b></td>
                                       <td align="center" ><b><small>2nd  sem of SY</small></b></td>
                                     </tr>
                            '.$locallst.'
                            '.$foreignlist.'
                        </table>
                        <br>
                        <br>
                        <br>
						<table width="35%" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width :40%">Recommended by:</td><td align="center" style="border-bottom : 1px solid #000;width :60%">'.getDirector($divisions->division_id).'</td>
							</tr>
							<tr>
								<td></td><td align="center">Division Director</td>
							</tr>
						</table>
						<div class="page-break"></div>';
    	}

       $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>'.$title.'</title>
						</head>
						<style type="text/css">
								body
								{
									font-family: DejaVu Sans;
								}
								.page-break {
									 page-break-after: always;
									}
							</style>
						<body>
							'.$html.'
						</body>
						</html>')
		->setPaper('legal', 'landscape');
		return $pdf->stream();
    }

    public function monitoringdegree()
    {
    	$title = 'HRMS | HUMAN RESOURCE DEVELOPMENT PLAN';

    	// $hrd_degree = App\HRD_plan::where('id',$hrdid)->first();

    	$list = App\HRD_plan_degree::get();

    	$tdlist = "";
    	$ctr = 1;
    	foreach ($list as $lists) {

    		$td = '<td></td><td align="center"><b>&#10004<b></td>';
    		if($lists->hrd_degree_program == 'PhD')
    		{
    			$td = '<td align="center"><b>&#10004<b></td><td></td>';
    		}

    		$td2 = '<td></td><td align="center"><b>&#10004<b></td>';
    		if($lists->hrd_degree_area == '1st sem of SY')
    		{
    			$td2 = '<td align="center"><b>&#10004<b></td><td></td>';
    		}

    		$tdlist .= '<tr><td align="center">'.$ctr.'</td><td>'.getStaffInfo($lists->user_id).'</td><td>'.getStaffInfo($lists->user_id,'position').'</td><td>'.getStaffInfo($lists->user_id,'division').'</td>'.$td.'<td>'.$lists->hrd_degree_area.'</td><td>'.$lists->hrd_degree_university.'</td>'.$td2.'<td>'.$lists->hrd_degree_remarks.'</td></tr>';

    		$ctr++;
    	}


        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>'.$title.'</title>
						</head>
						<style type="text/css">
								body
								{
									font-family: DejaVu Sans;
								}
							</style>
						<body>
						
						<center><h3>PCAARRD`s DEGREE HUMAN RESOURCE DEVELOPMENT PLAN</h3></center>

						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
                                     <tr>
                                     	<td align="center" rowspan="2" style="vertical-align: middle;width:2%"><b>NO.</b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>NAME</b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>POSITION</b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>DIVISION</b></td>
                                        <td align="center" colspan="2"><small><b>DEGREE PROGRAM</small></b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>FIELD/AREA OF DISCIPLINE</b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>PROPOSED UNIVERSITY</b></td>
                                        <td align="center" colspan="2"><small><b>TARGET DATE</small></b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>REMARKS</b></td>
                                     </tr>
                                     <tr>
                                     	<td align="center"><small><b>PhD</small></b></td>
                                     	<td align="center"><small><b>MS</small></b></td>
                                     	<td align="center"><small><b>1st Sem</small></b></td>
                                     	<td align="center"><small><b>2nd Sem</small></b></td>
                                     </tr>

                                     <tbody>
                                     		'.$tdlist.'
                                     </tbody>
                        </table>
						</body>
						</html>')
		->setPaper('legal', 'landscape');
		return $pdf->stream();
    }

    public function monitoringnondegree($hrdid)
    {
    	$title = 'HRMS | HUMAN RESOURCE DEVELOPMENT PLAN';

    	$hrd_degree = App\HRD_plan::where('id',$hrdid)->first();

    	//GET ALL TRAINING
    	$list = collect(App\View_hrd_plan_non_degree_areas::where('hrd_plan_id',$hrdid)->get());

    	$tdlist = "";
    	$ctr = 1;
    	foreach ($list->all() as $lists) {

    		//GET ACTUAL
	    		$training = collect(App\Employee_training::where('user_id',$lists->user_id)->whereYear('training_date_from',$hrd_degree->hrd_year)->where('areas_of_discipline',$lists->areas_of_discipline)->get());
	    		$training = $training->all();
	    		$rowspan = "";
	    	if(count($training) > 0)
	    		{
	    			$rowspan = 'rowspan ="'.count($training).'"';	
	    		}
	    			
    		$tdlist .= '<tr><td $rowspan>'.getStaffInfo($lists->user_id).'</td><td $rowspan>'.getStaffInfo($lists->user_id,'position').'</td><td $rowspan>'.$lists->areas_of_discipline.'</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

    			

	    		if(count($training) > 0)
	    		{
	    			foreach($training AS $trainings)
	    			{
	    				$tdlist .= '<tr><td></td><td></td><td></td><td>'.$trainings->training_title.'</td><td>'.$trainings->training_inclusive_dates.'</td><td>'.$trainings->training_conducted_by.'</td><td></td><td></td><td></td></tr>';
	    			}
	    			
	    		}
	    		else
	    		{
	    			// $tdlist .= '';
	    		}

    		// $tdlist .= '</tr>';

    		
    		

    		$ctr++;
    	}

        $pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML('<!DOCTYPE>
						<html>
						<head>
							<title>'.$title.'</title>
						</head>
						<style type="text/css">
								body
								{
									font-family: DejaVu Sans;
								}
							</style>
						<body>
						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
							<tr>
								<td align="center" rowspan="3"><img src="'.asset('img/DOST.png').'" style="width:70px"></td>
								<td><center><b>PHILIPPINE COUNCIL FOR AGRICULTURE, AQUATIC AND NATURAL RESOURCES RESEARCH AND DEVELOPMENT</b></center></td>
								<td>DOCUMENT NUMBER</td>
								<td>QMSF-FADPS-07-01-13</td>
							</tr>
							<tr>
								<td rowspan="2" style="font-size:20px"><center><b>HRD MONITORING SHEET</b></center></td>
								<td>REVISION NUMBER</td>
								<td><center>0</center></td>
							</tr>
							<tr>
								<td>PAGE NUMBER</td>
								<td><center>1/1</center></td>
							</tr>
							<tr>
								<td><center><b>TITLE</b></center></td>
								<td><center><b>YEAR '.$hrd_degree->hrd_year.'</b></center></td>
								<td>EFFECTIVITY DATE</td>
								<td><center>May 2, 2018</center></td>
							</tr>
						</table>
						<br>

						<table border="1" width="100%" style="font-size:11px;" cellpadding="2" cellspacing="0">
                                     <tr>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>NAME OF STAFF</b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>POSITION</b></td>
                                        <td align="center" colspan="2" style="width:20%"><small><b>TRAINING/SEMINAR/CONFERENCES/CONVENTIONS/CONGRESS</small></b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>DATE</b></td>
                                        <td align="center" rowspan="2" style="vertical-align: middle;"><b>VENUE</b></td>
                                        <td align="center" colspan="3"><small><b>POST-TRAINING ASSESSMENT</small></b></td>
                                     </tr>
                                     <tr>
                                     	<td align="center" style="vertical-align: middle;"><b>PLANNED</b></td>
                                     	<td align="center" style="vertical-align: middle;"><b>ACTUAL</b></td>
                                     	<td align="center" style="vertical-align: middle;"><small><b>DUE DATE</b></small></td>
                                     	<td align="center" style="vertical-align: middle;"><small><b>DATE SUBMITTED</b></small></td>
                                     	<td align="center" style="vertical-align: middle;"><small><b>REMARKS</b></small></td>
                                     </tr>

                                     '.$tdlist.'
                        </table>
						</body>
						</html>')
		->setPaper('legal', 'landscape');
		return $pdf->stream();
    }
}
