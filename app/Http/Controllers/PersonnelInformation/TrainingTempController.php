<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class TrainingTempController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
    	//CERTIFICATE
        $path = "";
        if(request()->hasFile('training_attachment'))
        {
            $path = request()->file('training_attachment')->store('request_training');
        }

        //TRAINING TYPE, IF PAID SAKA LANG MAGKAKAROON NG AMOUNT
        $amt = null;
        if(request()->training_type == 'Funded')
        {
        	$amt = request()->training_amount;
        }

        //SORT DATE
        $arrdate = explode(",", request()->training_inclusive_date);
        usort($arrdate, "date_sorter");

        $sortdate = implode(",", $arrdate);


        $training = new App\Employee_training_temp;
        $training->user_id = request()->user_id;
        $training->division_id = Auth::user()->division;
        $training->training_title = request()->training_title;
        $training->training_type = request()->training_type;
        $training->training_amount = $amt;
        $training->training_title = request()->training_title;
        $training->training_hours = request()->training_hours;
        $training->training_ld = implode(",", request()->training_ld);
        $training->training_conducted_by = request()->training_conducted_by;
        $training->training_attachment = $path;
        $training->training_inclusive_dates = $sortdate;
        $training->save();
    }

    public function action()
    {
        switch (request()->status) {
            case 'receive':

                $data = [
                            'training_status' => 'Received',
                            'training_seen' => null,
                            'training_received' => date('Y-m-d H:i:s')
                        ];
                break;
            case 'approve':
                //MOVE TO MAIN TABLE
                $details = App\Employee_training_temp::where('id',request()->tblid)->first();

                $training = new App\Employee_training;
                $training->user_id = $details->user_id;
                $training->division_id = $details->division_id;
                $training->training_title = $details->training_title;
                $training->training_type = $details->training_type;
                $training->areas_of_discipline = $details->areas_of_discipline;
                $training->training_amount = $details->training_amount;
                $training->training_title = $details->training_title;
                $training->training_hours = $details->training_hours;
                $training->training_ld = $details->training_ld;
                $training->training_conducted_by = $details->training_conducted_by;
                $training->training_approved_by = 1;
                $training->training_attachment = $details->training_attachment;
                $training->training_inclusive_dates = $details->training_inclusive_dates;
                $training->save();

                $data = [
                            'training_status' => 'Approved',
                            'training_seen' => null,
                            'training_approved' => date('Y-m-d H:i:s')
                        ];
                break;
            case 'disapprove':
                $data = [
                            'training_status' => 'Disapproved',
                            'training_seen' => null,
                            'training_disapproved' => date('Y-m-d H:i:s')
                        ];
                break;
        }

       App\Employee_training_temp::where('id',request()->tblid)
                                ->update($data);

        

    }

    public function update()
    {
        $path = "";
        if(request()->hasFile('certificate'))
        {
            $path = request()->file('certificate')->store('certificates');
        }

        //TRAINING TYPE, IF PAID SAKA LANG MAGKAKAROON NG AMOUNT
        $amt = null;
        if(request()->training_type == 'Funded')
        {
            $amt = request()->training_amount;
        }

        $training = new App\Employee_training;
        $training = $training
                        ->where('id',request()->tblid)
                        ->where('user_id',Auth::user()->id)
                        ->update([
                                    'training_title' => request()->training_title,
                                    'training_hours' => request()->training_hours,
                                    'training_conducted_by' => request()->training_conducted_by,
                                    'training_type' => request()->training_type,
                                    'training_amount' => $amt,
                                    'training_inclusive_dates' => request()->training_inclusive_date,
                                    'training_ld' => implode(",", request()->training_ld),
                                    'training_certificate' => $path,
                                ]);
    }

    public function complete()
    {
        $cert = "";
        if(request()->hasFile('training_certificate'))
        {
            $cert = request()->file('training_certificate')->store('traning_certificates');
        } 

        $report = "";
        if(request()->hasFile('training_report'))
        {
            $report = request()->file('training_report')->store('traning_reports');
        }

        $training = new App\Employee_training_temp;
        if(request()->training_go == "Yes")
        {
            $training = $training
                        ->where('id',request()->tblid)
                        ->update([
                                    'training_certificate' => $cert,
                                    'training_report' => $report,
                                    'training_completed' => "Yes",
                                ]);
        }
        else
        {
            $training = $training
                        ->where('id',request()->tblid)
                        ->update([
                                    'training_completed' => "No",
                                    'training_reason_for_no' => request()->training_reason
                                ]);
        }
        
        //GET TEMP DATA
        $training_temp = App\Employee_training_temp::where('id',request()->tblid)->first();

        //MOVE TO PERMANENT TABLE
        $training_perma = new App\Employee_training;
        $training_perma->user_id = $training_temp['user_id'];
        $training_perma->division_id = $training_temp['division_id'];
        $training_perma->training_title = $training_temp['training_title'];
        $training_perma->training_type = $training_temp['training_type'];
        $training_perma->training_amount = $training_temp['training_amount'];
        $training_perma->training_inclusive_dates = $training_temp['training_inclusive_dates'];
        $training_perma->training_hours = $training_temp['training_hours'];
        $training_perma->training_ld = $training_temp['training_ld'];
        $training_perma->training_conducted_by = $training_temp['training_conducted_by'];
        $training_perma->training_attachment = $training_temp['training_attachment'];
        $training_perma->training_certificate = $training_temp['training_certificate'];
        $training_perma->training_report = $training_temp['training_report'];
        $training_perma->training_approved_by = 9999;
        $training_perma->save();

    }

    public function delete()
    {
        $training = new App\Employee_training_temp;
        $training = $training
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $training = new App\Employee_training_temp;
        $training = $training
                        ->where('id',$id)
                        ->get();

        return json_encode($training);
    }

    public function trainings()
    {
        $data = [
                    "nav" => nav("dashboard"),
                    "list" => NotificationForTrainingList(Auth::user()->id)
                ];

        return view('pis.shared.training-updates')->with("data",$data);
    }
}
