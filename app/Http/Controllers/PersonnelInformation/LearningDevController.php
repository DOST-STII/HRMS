<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

use Carbon\Carbon;

class LearningDevController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {

        $data = [
                        "nav" => nav("learn"),
                        "division" => App\Division::orderBy('division_acro')->get(),
                        "hrd_plan" => App\HRD_plan::orderBy('hrd_year','desc')->get(),
                        "total_trainings" => App\View_total_training::orderBy('division_acro')->get(),
                    ];

    	return view('pis.learningdev.index')->with("data",$data);
    }



    public function hrdplandivision()
    {
        $hrd = new App\HRD_plan;
        $hrd->hrd_year = request()->hrd_year;
        $hrd->hrd_year2 = request()->hrd_year2;
        $hrd->hrd_deadline = request()->hrd_deadline;
        $hrd->save();
        $hrd_id = $hrd->id;


        //SEND TO DIVISION
        $division = App\Division::where('type',1)->get();

        foreach ($division as $value) {

            $hrd = new App\HRD_plan_division;
            $hrd->division_id = $value->division_id;
            $hrd->division_acro = $value->division_acro;
            $hrd->hrd_plan_id = $hrd_id;
            $hrd->save();
            $hrd_division_id = $hrd->id;

            //SEND TO STAFF
            $user = App\User::where('usertype','!=','Administrator')->where('division',$value->division_id)->whereNotIn('employment_id',[9,10,12])->get();

            foreach ($user as $users) {

                $hrd = new App\HRD_plan_staff;
                $hrd->hrd_plan_id = $hrd_id;
                $hrd->user_id = $users->id;
                $hrd->hrd_plan_division_id = $hrd_division_id;
                $hrd->save();
            }
        }

        
    }

    public function jsondivhrd($id)
    {
        $hrd = App\HRD_plan_division::where('hrd_plan_id',$id)->whereNotNull('hrd_file_path')->get();

        return json_encode($hrd);
    }

    public function jsonhrdchrd($id)
    {
        $hrd = App\View_hrd_hrdc::where('hrd_plan_id',$id)->whereNotNull('received_at')->get();

        return json_encode($hrd);
    }

    public function sendtohrdc()
    {
        //UPDATE HRD
        // $path = null;
        // if(request()->hasFile('hrd_consolidated_file'))
        // {
        //     $path = request()->file('hrd_consolidated_file')->store('hrd_consolidated');
        // }

        $hrd = App\HRD_plan::where('id',request()->tbl_id)
                            ->update([
                                        'hrd_status' => 'Forwarded to HRDC members',
                                        // 'file_consolidated' => $path,
                                        // 'file_consolidated_uploaded' => Carbon::now()->toDateTimeString()
                                    ]);

        $hrdc = App\HRDC_member::get();

        foreach ($hrdc as $value) {
            $hrd = new App\HRD_hrdc;
            $hrd->hrd_plan_id = request()->tbl_id;
            $hrd->hrdc_member_id = $value->user_id;
            $hrd->save();
        }
    }

    public function sendtooed()
    {

        $hrd = App\HRD_plan::where('id',request()->tbl_id)
                            ->update([
                                        'hrd_status' => 'Forwarded to OED',
                                        'oed_received_at' => Carbon::now()->toDateTimeString()
                                    ]);
    }

    public function closehrd()
    {
        $hrd = App\HRD_plan::where('id',request()->tbl_id)
                            ->update([
                                        'hrd_status' => 'Closed',
                                    ]);
    }

    public function degreejson($id)
    {
        $hrd = App\HRD_plan_degree::where('id',$id)->get();

        return json_encode($hrd);
    }

    public function degreeupdate()
    {
       $hrd = App\HRD_plan_degree::where('id',request()->degree_id)
                            ->update([
                                        'hrd_degree_from' => request()->hrd_degree_from,
                                        'hrd_degree_to' => request()->hrd_degree_to,
                                        'hrd_degree_remarks' => request()->hrd_degree_remarks,
                                    ]);
    }
}
