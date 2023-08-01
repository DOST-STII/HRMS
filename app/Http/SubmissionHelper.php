<?php

function getSubmissionCount($class,$id,$type)
{
	switch ($class) {
		case 'Monthly Accomplishment Report':
		case 'Monthly Executive Report':
				if($type == 'total')
					{
						return App\Submission_list::where('submission_id',$id)->count();
					}
					else if($type == 'response')
					{
						return App\Submission_list::where('submission_id',$id)->whereNotNull('submission_file')->count();
					}
			break;
		
		case 'Training Certificate':
				if($type == 'total')
					{
						// return App\Training_cert_list::where('submission_id',$id)->count();
						return App\Employee_training_temp::whereIn('training_completed',[null,'Yes'])->count();
					}
					else if($type == 'response')
					{
						// return App\Training_cert_list::where('submission_id',$id)->whereNotNull('training_cert')->count();
						return App\Employee_training_temp::whereNotNull('training_certificate')->where('training_completed','Yes')->count();
					}
			break;

		case 'Training Report':
				if($type == 'total')
					{
						// return App\Training_cert_list::where('submission_id',$id)->count();
						return App\Employee_training_temp::whereIn('training_completed',[null,'Yes'])->count();
					}
					else if($type == 'response')
					{
						// return App\Training_cert_list::where('submission_id',$id)->whereNotNull('training_cert')->count();
						return App\Employee_training_temp::whereNotNull('training_certificate')->where('training_completed','Yes')->count();
					}
			break;
	}
	

}

function countCallforSubmitDivision($type)
{
	switch ($type) {
		case 'total':
				return App\Submission_list::where('submission_division',Auth::user()->division)->whereNull('deleted_at')->count();
			break;
		
		case 'active':
				$ctr = App\Submission_list::where('submission_division',Auth::user()->division)->whereNull('submission_file')->whereNull('deleted_at')->count();
				if($ctr > 0)
				{
					return $ctr;
				}
				else
				{
					return "";
				}
			break;
	}
	
}

function getTrainingInfo($type,$id)
{
	$training = App\Employee_training_temp::where('id',$id)->first();

	switch ($type) {
		case 'user':
				return $training['user_id'];
			break;
		case 'cert':
				return $training['training_certificate'];
			break;
		case 'report':
				return $training['training_report'];
			break;
	}
}