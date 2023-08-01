<?php

function getDivisionHRDPlan($type,$id)
{
	if($type == 'degree')
	{
		$sub = App\HRD_plan_degree::where('hrd_plan_id',$id)->groupBy('hrd_plan_division_id')->get();
		echo "<span class='badge badge-success'>".count($sub)."/15</span>";
	}
	else
	{
		$sub = App\HRD_plan_non_degree::where('hrd_plan_id',$id)->groupBy('hrd_plan_division_id')->get();
		echo "<span class='badge badge-success'>".count($sub)."/15</span>";
	}
	
}

function countHRDC($id)
{
	//GET TOTAL
	$total = App\HRD_hrdc::where('hrd_plan_id',$id)->count();

	//GET SUBMISSION
	$sub = App\HRD_hrdc::where('hrd_plan_id',$id)->whereNotNull('received_at')->count();

	echo "<span class='badge badge-warning'>".$sub."/".$total."</span>";
}

function checkifHRDC($id)
{
	//GET TOTAL
	$total = App\HRDC_member::where('user_id',$id)->count();

	if($total > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checkifHasHRD()
{
	$total = App\HRD_plan_division::where('division_id',Auth::user()->division)->whereNull('hrd_plan_locked')->count();

	if($total > 0)
	{
		return 1;
	}	
	else
	{
		return 0;
	}
}

function getDirector($div,$userid = null)
{
	if($userid == null)
	{
		$user = App\User::where('division',$div)->where('usertype','Director')->first();
		if($user)
		{
			switch ($div) {
				case 'x':
				case 's':
				case 'q':
				case 't':
				case 'm':
				case 'r':
				case 'v':
				case 'u':
						return mb_strtoupper("Susan S. Molina")."<br><small><b>Division Director</b></small>"; 
					break;
				
				default:
						if($user->oic == 1)
							return mb_strtoupper($user->fname." ".substr($user->mname,0,1).". ".$user->lname." ".$user->exname)."<br><small><b>Officer In Charge</b></small>";
						else
							if($user->username == 'EBO001')
								return mb_strtoupper($user->fname." ".substr($user->mname,0,1).". ".$user->lname." ".$user->exname)."<br><small><b>Executive Director</b></small>";
							else
								return mb_strtoupper($user->fname." ".substr($user->mname,0,1).". ".$user->lname." ".$user->exname)."<br><small><b>Division Director</b></small>";
					break;
			}
			
			
		}
		else
		{
			switch ($div) {
				case 'x':
				case 's':
				case 'q':
				case 't':
				case 'm':
				case 'r':
				case 'v':
				case 'u':
						return mb_strtoupper("Susan S. Molina")."<br><small><b>Division Director</b></small>"; 
					break;
				
				default:
					return null;
					break;
			}
		}
			
	}
	else
	{
		if($userid == 312)
		{
			return mb_strtoupper("Fortunato de la Peña")."<br><small><b>DOST Secretary</b></small>";
		}
		else
		{
			$emp = App\User::where('id',$userid)->first();

			if($emp['usertype'] == 'Director')
			{
				return mb_strtoupper("Reynaldo V. Ebora")."<br><small><b>Executive Director</b></small>";
			}
			else
			{
				switch ($emp['division']) {
					case 'x':
					case 's':
					case 'q':
					case 't':
					case 'm':
					case 'r':
					case 'v':
					case 'u':
							return mb_strtoupper("Susan S. Molina")."<br><small><b>Division Director</b></small>"; 
						break;
					case 'O':
							return mb_strtoupper("Reynaldo V. Ebora")."<br><small><b>Executive Director</b></small>";
						break;
					
					default:
						
							$user = App\User::where('division',$emp['division'])->where('usertype','Director')->first();
												
							//return mb_strtoupper($user->fname." ".substr($user->mname,0,1). ". ".$user->lname." ".$user->exname)."<br><small><b>Division Director</b></small>";
						break;
				}
				
			}
		}
		
	}
	
}

function getDirectorNoDesc($div,$userid = null)
{
	if($userid == null)
	{
		$user = App\User::where('division',$div)->where('usertype','Director')->first();
		if($user)
		{
			switch ($div) {
				case 'x':
				case 's':
				case 'q':
				case 't':
				case 'm':
				case 'r':
				case 'v':
				case 'u':
						return mb_strtoupper("Susan S. Molina");
					break;
				
				default:
						if($user->oic == 1)
							return mb_strtoupper($user->fname." ".substr($user->mname,0,1).". ".$user->lname." ".$user->exname);
						else
							if($user->username == 'EBO001')
								return mb_strtoupper($user->fname." ".substr($user->mname,0,1).". ".$user->lname." ".$user->exname);
							else
								return mb_strtoupper($user->fname." ".substr($user->mname,0,1).". ".$user->lname." ".$user->exname);
					break;
			}
			
			
		}
		else
		{
			switch ($div) {
				case 'x':
				case 's':
				case 'q':
				case 't':
				case 'm':
				case 'r':
				case 'v':
				case 'u':
						return mb_strtoupper("Susan S. Molina");
					break;
				
				default:
					return null;
					break;
			}
		}
		
	}
	else
	{
		if($userid == 312)
		{
			return mb_strtoupper("Fortunato T. de la Peña").", Secretary, DOST";
		}
		else
		{
			$emp = App\User::where('id',$userid)->first();

			if($emp['usertype'] == 'Director')
			{
				return mb_strtoupper("Reynaldo V. Ebora");
			}
			else
			{
				switch ($emp['division']) {
					case 'x':
					case 's':
					case 'q':
					case 't':
					case 'm':
					case 'r':
					case 'v':
					case 'u':
							return mb_strtoupper("Susan S. Molina");
						break;
					
					default:
						
							$user = App\User::where('division',$emp['division'])->where('usertype','Director')->first();
							//return mb_strtoupper($user->fname." ".substr($user->mname,0,1). ". ".$user->lname." ".$user->exname);
						break;
				}
				
			}
		}
	}
	
}

function getCluster($div)
{
	$div = App\Division::where('division_id',$div)->first();

	$user = App\User::where('usertype','Director')->where('division',$div['cluster'])->first();

	return mb_strtoupper($user->fname." ".substr($user->mname,0,1).". ".$user->lname);
}

function getMarshal($div)
{
	$user = App\View_user::where('division',$div)->where('usertype','Marshal')->first();

	if(isset($user))
		return mb_strtoupper($user->fname." ".substr($user->mname,0,1).". ".$user->lname)."<br><small><b>Division Marshal</b></small>";
	return null;
}

function getAreasDiscipline($type,$non_degree_id,$discipline)
{
	if($type == 'check' || $type == 'pdf')
	{
		if($discipline != 'Others')
		{
			$ctr = App\HRD_plan_non_degree_area::where('hrd_plan_non_degrees_id',$non_degree_id)->where('areas_of_discipline',$discipline)->count();
		}
		else
		{
			$disciplines = ['Management/ Supervisory/ Leadership','Information, Education & Communication (iec)','R&d Related Trainings','Value Enhancement','Information & Communication Technology (ict)','General Administration/ Governance','Skills Enhancement'];
			$ctr = App\HRD_plan_non_degree_area::where('hrd_plan_non_degrees_id',$non_degree_id)->whereNotIn('areas_of_discipline',$disciplines)->count();	
		}

		if($ctr > 0)
		{
			if($type == 'pdf')
			{
				return "&#10004";
			}
			else
			{
				echo "<i class='fas fa-check'></i>";
			}
			
		}

	}
	else
	{

	}
		
}

function getQuarter($type,$val)
{
	if($val == 1)
	{
		if($type == 'pdf')
		{
			return "&#10004";
		}
		else
		{
			echo "<i class='fas fa-check'></i>";
		}
		
	}
}

function checkStatusHRD($id)
{
	$hrd = App\HRD_plan_division::where('id',$id)->whereNull('submitted_at')->count();

	if($hrd > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checkUserHRD($type,$user_id,$hrd_plan_id)
{
	if($type == 'degree')
	{
		$ctr = App\HRD_plan_degree::where('hrd_plan_id',$hrd_plan_id)->where('user_id',$user_id)->count();
	}
	else
	{
		$ctr = App\HRD_plan_non_degree::where('hrd_plan_id',$hrd_plan_id)->where('user_id',$user_id)->count();
	}

	if($ctr > 0)
	{
		return true;
	}
	else
	{
		return false;
	}

}

function checkUserDelete($type,$id)
{
	if($type == 'degree')
	{
		$ctr = App\HRD_plan_degree::where('id',$id)->where('user_id',Auth::user()->id)->count();
	}
	else
	{
		$ctr = App\HRD_plan_non_degree::where('id',$id)->where('user_id',Auth::user()->id)->count();
	}

	if($ctr > 0)
	{
		return true;
	}
	else
	{
		return false;
	}

}

function getDegreeList($type,$hrd_plan_division_id)
{
	return App\View_hrd_plan_degree::where('hrd_plan_division_id',$hrd_plan_division_id)->where('hrd_degree_type',$type)->get();
}

function getDegreeList2($type)
{
	return App\View_hrd_plan_degree::get();
}

function getHRDMonitoring()
{
	return App\HRD_plan::where('hrd_status','Closed')->get();
}