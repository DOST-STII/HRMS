<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class TrainingController extends Controller
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
        if(request()->hasFile('certificate'))
        {
            $path = request()->file('certificate')->store('certificates');
        }

        return $path;

        //TRAINING TYPE, IF PAID SAKA LANG MAGKAKAROON NG AMOUNT
        $amt = null;
        if(request()->training_type == 'Funded')
        {
        	$amt = request()->training_amount;
        }

        $training = new App\Employee_training;
        $training->user_id = Auth::user()->id;
        $training->division_id = Auth::user()->division;
        $training->training_title = request()->training_title;
        $training->training_type = request()->training_type;
        $training->training_amount = $amt;
        $training->training_title = request()->training_title;
        $training->training_hours = request()->training_hours;
        $training->training_ld = implode(",", request()->training_ld);
        $training->training_conducted_by = request()->training_conducted_by;
        $training->training_certificate = $path;
        $training->training_inclusive_dates = request()->training_inclusive_date;
        $training->save();
    }

    public function createrequest(Request $request)
    {
        //CERTIFICATE
        $path_cert = "";
        if(request()->hasFile('training_attachment'))
        {
            $path = request()->file('uploadphoto')->store('training_certificates');
            $path = explode('/',$path);
            $request->training_attachment->move(public_path('storage/training_certificates'), $path[1]);
            $path_cert = $path[1];
        }

        $path2 = "";
        if(request()->hasFile('trainingreport'))
        {
            $path2 = request()->file('trainingreport')->store('training_reports');
        }

        //TRAINING TYPE, IF PAID SAKA LANG MAGKAKAROON NG AMOUNT
        $amt = null;
        if(request()->training_type == 'Funded')
        {
            $amt = request()->training_amount;
        }

        $training = new App\Employee_training_temp;
        $training->user_id = request()->user_id;
        $training->division_id = getStaffInfo(request()->user_id,'division_id');
        $training->areas_of_discipline = request()->hrd_degree_area;
        $training->training_title = request()->training_title;
        $training->training_type = request()->training_type;
        $training->training_amount = $amt;
        $training->training_title = request()->training_title;
        $training->training_hours = request()->training_hours;
        $training->training_ld = implode(",", request()->training_ld);
        $training->training_conducted_by = request()->training_conducted_by;
        $training->training_attachment = $path;
        $training->training_inclusive_dates = request()->training_inclusive_date;
        $training->save();
    }

    public function update(Request $request)
    {
        //CHECK ATTACHMENT 
        $train = App\Employee_training::where('id',request()->tblid)->first();
        $path_cert = null;
        $path_training = null;
        if($train)
        {
            $path_cert = $train['training_certificate'];
            $path_training = $train['training_report'];
        }

        
        if(request()->hasFile('training_certificate'))
        {
            $path = request()->file('training_certificate')->store('training_certificates');
            $path = explode('/',$path);
            $request->training_certificate->move(public_path('storage/training_certificates'), $path[1]);
            $path_cert = $path[1];
        }

        
        if(request()->hasFile('training_report'))
        {
            $path2 = request()->file('training_report')->store('training_reports');
            $path2 = explode('/',$path2);
            $request->training_report->move(public_path('storage/training_reports'), $path2[1]);
            $path_training = $path2[1];
        }


        //TRAINING TYPE, IF PAID SAKA LANG MAGKAKAROON NG AMOUNT
        $amt = null;
        if(request()->training_type == 'Funded')
        {
            $amt = request()->training_amount;
        }

        $ld = null;
        if(isset(request()->training_ld))
        {
            $ld = implode(",",request()->training_ld);
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
                                    'training_ld' => $ld.',',
                                    'areas_of_discipline' => request()->areasdiscip,
                                    'training_certificate' => $path_cert,
                                    'training_report' => $path_training,
                                ]);
    }

    public function delete()
    {
        $training = new App\Employee_training;
        $training = $training
                        ->where('id',request()->tblid)
                        ->delete();
    }

    public function json($id)
    {
        $training = new App\Employee_training;
        $training = $training
                        ->where('id',$id)
                        ->get();

        return json_encode($training);
    }

    public function list()
    {
        $training = new App\Employee_training;
        $training = $training
                        ->where('division_id',Auth::user()->division)
                        ->get();
        $data = [
                    "training_list" => $training,
                ];

        return view("pis.director.training")->with("data",$data);
    }
}
