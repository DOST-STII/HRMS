<?php
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
function CheckLateUnderTime()
{
	//CHECK ACTIVE SCHEME
	$scheme = collect(App\Duration::orderBy('id','DESC')->first());
	$scheme =  $scheme->all();

	$dtroption = collect(App\DTROption::where('id',$scheme['fldDTROptionID'])->first());

	return $dtroption->all();

}


function getOffset($id)
{
	$offset = collect(App\DTROffset::where('id',$id)->first());
	$offset = $offset->all();
	
}

function showActiveWS($type = null)
{
	$ws = App\View_work_schedule::orderBy('id','DESC')->first();

	switch ($type) {
		case 'desc':
				$col = 'fldDTROptDesc';
			break;
		
		default:
				$col = 'dtr_option_id';
			break;
	}
	return $ws[$col];
}

//maine
function getStaffSched2($dt)
{
	$list = App\View_schedule::where('date',$dt)->whereIn('sched_status',['Office/Pickup','Pickup'])->orderBy('division_acro', 'ASC')->orderBy('lname', 'ASC')->get();
	return $list;
}

function getStaffSchedCount($dt)
{	
	$list = App\View_schedule::where('date',$dt)->whereIn('sched_status',['Office/Pickup','Pickup'])->get();
	$count = $list->count();
	return $count;
}

//workforce: total count of staff *maine

function getMain_ICOS_All($dt)
{	
	// $list = App\View_icos_dtr::where('date',$dt)->where('office_location', '1')->where('employment_id', '8')->groupBy("username")->get();
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '8')
				->where('ofc.office_location', '1')		
				->where('sched_status', '!=', 'On-Leave')			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();  
	$count = $list->count();
	return $count;
}

function getDPITC_ICOS_All($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '8')
				->where('ofc.office_location', '2')		
				->where('sched_status', '!=', 'On-Leave')				
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getMain_Proj_All($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '5')
				->where('ofc.office_location', '1')		
				->where('sched_status', '!=', 'On-Leave')				
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getDPITC_Proj_All($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '5')
				->where('ofc.office_location', '2')		
				->where('sched_status', '!=', 'On-Leave')				
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getMain_Reg_All($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '1')
				->where('ofc.office_location', '1')		
				->where('sched_status', '!=', 'On-Leave')			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getDPITC_Reg_All($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '1')
				->where('ofc.office_location', '2')		
				->where('sched_status', '!=', 'On-Leave')				
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

//per division
function getMain_div($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '1')	
				->where('sched_status', '!=', 'On-Leave')			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

//workforce: total count of scheduled workforce
function getMain_ICOS_Sched($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '8')
				->where('ofc.office_location', '1')		
				->whereIn('sched_status',['Office','Pickup'])			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getDPITC_ICOS_Sched($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '8')
				->where('ofc.office_location', '2')		
				->whereIn('sched_status',['Office','Pickup'])			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getMain_Proj_Sched($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '5')
				->where('ofc.office_location', '1')		
				->whereIn('sched_status',['Office','Pickup'])			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getDPITC_Proj_Sched($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '5')
				->where('ofc.office_location', '2')		
				->whereIn('sched_status',['Office','Pickup'])			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getMain_Reg_Sched($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '1')
				->where('ofc.office_location', '1')		
				->whereIn('sched_status',['Office','Pickup'])			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

function getDPITC_Reg_Sched($dt)
{	
	$list =  App\View_schedule::where('date',$dt)
				->where('employment_id', '1')
				->where('ofc.office_location', '2')		
				->whereIn('sched_status',['Office','Pickup'])			
				->join('staff_office_location AS ofc', 'username', '=', 'ofc.empcode')
				->groupBy("username")
				->get();    
	$count = $list->count();
	return $count;
}

//*maine
//workforce: total count of staff on office

// function getMain_ICOS_Ofc($dt)
// {	
// 	$list = App\View_icos_dtr::where('date',$dt)->where('office_location', '1')->where('employment_id', '8')->groupBy("username")->get();
// 	$count = $list->count();
// 	return $count;
// }

// function getDPITC_ICOS_Ofc($dt)
// {	
// 	$list = App\View_icos_dtr::where('date',$dt)->where('office_location', '2')->where('employment_id', '8')->groupBy("username")->get();
// 	$count = $list->count();
// 	return $count;
// }

// function getMain_Proj_OFc($dt)
// {	
// 	$list = App\View_icos_dtr::where('date',$dt)->where('office_location', '1')->where('employment_id', '5')->groupBy("username")->get();
// 	$count = $list->count();
// 	return $count;
// }

// function getDPITC_Proj_Ofc($dt)
// {	
// 	$list = App\View_icos_dtr::where('date',$dt)->where('office_location', '2')->where('employment_id', '5')->groupBy("username")->get();
// 	$count = $list->count();
// 	return $count;
// }

// function getMain_Reg_Ofc($dt)
// {	
// 	$list = App\View_employee_dtr::where('date',$dt)->where('office_location', '1')->groupBy("username")->get();
// 	$count = $list->count();
// 	return $count;
// }

// function getDPITC_Reg_Ofc($dt)
// {	
// 	$list = App\View_employee_dtr::where('date',$dt)->where('office_location', '2')->groupBy("username")->get();
// 	$count = $list->count();
// 	return $count;
// }
//maine

function getStaffSched($dt)
{
	$list = App\WeekSchedule::where('sched_date',$dt)->whereIn('sched_status',['Office/Pickup','Pickup'])->get();
	return $list;
}