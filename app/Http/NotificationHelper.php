<?php

function getNotification($type)
{
	switch ($type) {
		case 'request':
				$req = App\Request_for_hiring::where('request_status','Pending')->count();
				return $req;
			break;
		case 'request-count':
				$req = App\Request_for_hiring::whereIn('request_status',['Pending','Received'])->count();
				$req2 = App\Employee_training_temp::whereIn('training_status',['Pending','Received'])->count();
				$total = $req + $req2;
				if($total > 0)
				{
					return $total;
				}
				else
				{
					return "";
				}
			break;
		case 'hiring-count':
				$req = App\Request_for_hiring::whereIn('request_status',['Pending','Received'])->count();
				if($req > 0)
				{
					return $req;
				}
				else
				{
					return "";
				}
			break;
		case 'vacant':
				$req = App\Vacant_plantilla::count();
				if($req > 0)
				{
					return $req;
				}
				else
				{
					return "-";
				}
			break;
	}
}

function NotificationForTraining($userid)
{
	$train = App\Employee_training_temp::where('user_id',$userid)->whereNull('training_completed')->get();
        $total_traning_needed_update = 0;
        foreach ($train as $trains) {
            //GET TRAINING DATE LAST ENTRY
            $arr = explode(",",$trains->training_inclusive_dates);
            $ctr = (count($arr) - 1);
            
            $arr_last = date('Y-m-d',strtotime($arr[$ctr]));
            if($arr_last < date('Y-m-d'))
            {
                $total_traning_needed_update++;
            }
        }
    return $total_traning_needed_update;
}

function NotificationForTrainingList($userid)
{
	$train = App\Employee_training_temp::where('user_id',$userid)->whereNull('training_completed')->get();
    $total_traning_needed_update_id = array();
        foreach ($train as $trains) {
            //GET TRAINING DATE LAST ENTRY
            $arr = explode(",",$trains->training_inclusive_dates);
            $ctr = (count($arr) - 1);
            
            $arr_last = date('Y-m-d',strtotime($arr[$ctr]));
            if($arr_last < date('Y-m-d'))
            {
                array_push($total_traning_needed_update_id,$trains->id);
            }
        }

    
    $training = App\Employee_training_temp::whereIn('id',$total_traning_needed_update_id)->get(); 
    return $training;
}

function checkIfTrainingExistReport()
{
	$train = App\Employee_training_temp::whereNull('training_report')->get();
    $ctr = count($train);

    return $ctr;
}

function checkIfTrainingExistCertificate()
{
	$train = App\Employee_training_temp::whereNull('training_certificate')->get();
    $ctr = count($train);

    return $ctr;
}

