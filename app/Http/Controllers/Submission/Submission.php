<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class Submission extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
    	$i = 0;


    	if(isset(request()->check_report))
    	{
            
            foreach (request()->check_report as $value) 
            {
                $submission = new App\Submission;
                $submission->sub_report = $value;
                $submission->sub_remarks = request()->check_report_remarks[$i];
                $submission->sub_deadline = request()->deadline_check_report[$i];
                $submission->save();
                $submission_id = $submission->id;

                switch ($value) {
                    
                case 'Training Report':
                    $train = App\Employee_training_temp::whereNull('training_report')->get();

                    foreach ($train as $trains) {
                                    $submissions = new App\Training_report_list;
                                    $submissions->submission_id = $submission_id;
                                    $submissions->training_temp_id = $trains->id;
                                    $submissions->user_id = getTrainingInfo('user',$trains->id);
                                    $submissions->training_report = getTrainingInfo('report',$trains->id);
                                    $submissions->save(); 
                                }

                    break;

                case 'Training Certificate':
                    $train = App\Employee_training_temp::whereNull('training_certificate')->get();

                    foreach ($train as $trains) {
                                    $submissions = new App\Training_cert_list;
                                    $submissions->submission_id = $submission_id;
                                    $submissions->training_temp_id = $trains->id;
                                    $submissions->user_id = getTrainingInfo('user',$trains->id);
                                    $submissions->training_cert = getTrainingInfo('cert',$trains->id);
                                    $submissions->save(); 
                                }

                    break;

                
                case 'Monthly Accomplishment Report':
                case 'Monthly Executive Report':
                        $division = App\Division::where('type',1)->get();

                                foreach ($division as $divisions) {
                                    $submissions = new App\Submission_list;
                                    $submissions->submission_id = $submission_id;
                                    $submissions->submission_division = $divisions->division_id;
                                    $submissions->save(); 
                                }
                    break;
                }

                $i++;                
            }
    		
    	}      
    }

    public function update()
    {
        $submission = new App\Submission;
        $submission = $submission
                        ->where('id',request()->tblid)
                        ->update([
                                    'sub_report' => request()->update_report_type,
                                    'sub_remarks' => request()->update_report_remarks,
                                    'sub_deadline' => request()->update_report_deadline
                                ]);
    }

    public function delete()
    {
        $submission = new App\Submission;
        $submission = $submission
                        ->where('id',request()->tblid)
                        ->delete();

        //SUB
        $submission_list = new App\Submission_list;
        $submission_list = $submission_list
                        ->where('submission_id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $submission = new App\Submission;
        $submission = $submission
                        ->where('id',$id)
                        ->get();

        return json_encode($submission);
    }


    //SUBMISSION LIST
    public function update2()
    {
    	$path = null;
    	$store = null;

        if(request()->hasFile('submission_file'))
        {
        	switch (request()->report_type) {
        		case 'Monthly Accomplishment Report':
        				$store = 'submission_file_mar';
        			break;
        		case 'Monthly Executive Report':
        				$store = 'submission_file_mer';
        			break;
        		case 'SALN':
        				$store = 'submission_file_saln';
        			break;
        		case 'IPCR':
        				$store = 'submission_file_ipcr';
        			break;
        	}

            $path = request()->file('submission_file')->store($store);
        }

        $submission = new App\Submission_list;
        $submission = $submission
                        ->where('id',request()->tblid)
                        ->update([
                                    'submission_file' => $path,
                                    "submission_division_datesubmitted" => date('Y-m-d H:i:s')
                                ]);
    }


    //SUBMISSION LIST
    public function trainingreport()
    {
        return view('submission.admin.training-report-list');
    }
}
