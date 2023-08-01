<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\RegretLetterNonPC;
use App\Mail\RegretLetterNonPC2;
use App\Mail\RegretLetterNonPC3;
use App\Mail\RegretLetterPC;

use Carbon\Carbon;

class SharedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function applicants($id,$letterid)
    {

        //CHECK STATUS
        $letter = App\Request_for_hiring::where('id',$letterid)->first();

        if($letter['request_status'] == 'FAD shortlisted applicants')
        {
            $list = App\View_job_application::where('vacant_plantilla_id',$id)->where('fad_shortlisted','YES')->orderBy('lname')->get();
        }
        elseif($letter['request_status'] == 'Division shortlisted applicants' || $letter['request_status'] == 'Sent to PSB' || $letter['request_status'] == 'Uploaded PSB Result')
        {
            $list = App\View_job_application::where('vacant_plantilla_id',$id)->where('div_shortlisted','YES')->orderBy('lname')->get();
        }
        else
        {
            $list = App\View_job_application::where('vacant_plantilla_id',$id)->orderBy('lname')->get();
        }

        $data = [
                    "nav" => nav("vacant"),
                    "employment" => App\Employment::whereIn('employment_id',[1,13,14])->orderBy('employment_id')->get(),
                    "designation" => App\Designation::orderBy('designation_abbr')->get(),
                    "employee" => App\User::where('usertype','!=','Administrator')->whereNotIn('employment_id',[9,10,12])->orderBy('username')->get(),
                    "request_id" => $letterid,
                    "request_status" => $letter['request_status'],
                    "summary" => $letter['personnel_summary'],
                    "list" => $list,
                    "detail" => App\View_vacant_plantilla::where('id',$id)->first()
                ];

        return view('pis.recruitment.list-of-applicant')->with("data",$data);
    }

    public function updateapplicants()
    {
        //CHECK MUNA STATUS
        $status = "marshal";
        $path1 = null;
        $path2 = null;
        if(request()->request_status == 'Vacancy Posted')
        {
            $status = 'admin';
        }

        //RESET FIRST IN CASE MAGUPDATE ULIT ANG DIVISION
        // App\Applicant_position_apply::where('vacant_plantilla_id',request()->plantilla_id)
        //                                 ->update([
        //                                     'qualified' => null,
        //                                 ]);

        $i = 0;
        foreach (request()->applicants as $value) {
            // $arr = explode("-", $value);

            $qualified = request()->qualified[$i];
            $pcaarrd = request()->pcaarrd[$i];

            if($status == 'admin')
            {
                $data = [
                            'pcaarrd' => $pcaarrd,
                            'fad_shortlisted' => $qualified,
                            'remarks' => request()->remarks[$i],
                        ];
                
            }
            else
            {
                $data = [
                            'div_shortlisted' => $qualified,
                            'remarks' => request()->remarks[$i],
                        ];
            }

            App\Applicant_position_apply::where('vacant_plantilla_id',request()->plantilla_id)
                                        ->where('applicant_id',$value)
                                        ->update($data); 
            

            $i++;
        }

        if($status == 'admin')
            {
                //MAIL THE NOT QUALIFIED
                if($qualified == 'NO')
                {   
                    //GET PLANTILLA INFO
                    $plantilla = App\View_vacant_plantilla::where('id',request()->plantilla_id)->first();
                    //GET EMAIL
                    $data = [
                                "position" => $plantilla['position_desc'],
                                "division" => $plantilla['division_acro'],
                            ];
                    $applicant = App\Applicant::where('id',$value)->first();

                    if($pcaarrd == 'YES')
                    {
                        Mail::to($applicant['email'])->send(new RegretLetterPC($data));
                    }
                    else
                    {
                        Mail::to($applicant['email'])->send(new RegretLetterNonPC($data));
                    }
                    
                }

                $st = 'FAD shortlisted applicants';

                 if(request()->hasFile('applicants_summary'))
                    {
                        $path1 = request()->file('applicants_summary')->store('applicants_summary');

                        //FILE HISTORY FOR RECRUITMENT
                        $file = new App\Recruitment_file_history;
                        $file->request_id = request()->request_id;
                        $file->file_type = "Summary of Applicants";
                        $file->file_path = $path1;
                        $file->save();
                    }
            }
            else
            {
                //MAIL THE NOT QUALIFIED
                if($qualified == 'NO')
                {   
                    //GET PLANTILLA INFO
                    $plantilla = App\View_vacant_plantilla::where('id',request()->plantilla_id)->first();
                    //GET EMAIL
                    $data = [
                                "position" => $plantilla['position_desc'],
                                "division" => $plantilla['division_acro'],
                            ];
                    $applicant = App\Applicant::where('id',$value)->first();

                    if($pcaarrd == 'YES')
                    {
                        Mail::to($applicant['email'])->send(new RegretLetterPC($data));
                    }
                    else
                    {
                        Mail::to($applicant['email'])->send(new RegretLetterNonPC2($data));
                    }
                    
                }
                $st = 'Division shortlisted applicants';

                if(request()->hasFile('applicants_summary'))
                    {
                        $path2 = request()->file('applicants_summary')->store('applicants_summary');
                    }
            }

        //UPDATE STATUS
        App\Request_for_hiring::where('id',request()->request_id)->update([
            'request_status' => $st,
            // 'personnel_summary' => $path1,
            // 'division_summary' => $path2,
            'request_seen' => null
        ]);


        //UPDATE HISTORY
        $history = new App\Recruitment_history;
        $history->request_id = request()->request_id;
        $history->request_status = $st;
        $history->created_by = getDivision(Auth::user()->division);
        $history->userid = Auth::user()->id;
        $history->save();

        //return redirect('recruitment/list-of-applicants/'.request()->plantilla_id.'/'.request()->request_id);
    }

    public function hrdapprovallist()
    {

        $data = [
                    "nav" => nav("learn"),
                    "list" => App\View_hrd_hrdc::where('hrdc_member_id',Auth::user()->id)->get(),
                    "hrd_oed_list" => App\HRD_plan::where('hrd_status','Forwarded to OED')->get(),
                ];

        return view('pis.learningdev.hrd-approval')->with("data",$data);
    }

    public function hrdapproval()
    {

        App\HRD_hrdc::where('id',request()->tbl_id)
                    ->update([
                                'received_at' => Carbon::now()->toDateTimeString()
                            ]);
    }

    public function oedupload()
    {

        //UPDATE HRD
        $path = null;
        if(request()->hasFile('hrd_final_file'))
        {
            $path = request()->file('hrd_final_file')->store('hrd_final');
        }

        $hrd = App\HRD_plan::where('id',request()->tbl_id)
                            ->update([
                                        'hrd_status' => 'Forwarded to Personnel',
                                        'file_final' => $path,
                                        'file_final_uploaded' => Carbon::now()->toDateTimeString()
                                    ]);
    }

    public function hrdplan($hrd_degree_id,$hrd_plan_id)
    {
        if(checkifHasHRD())
        {
            
            $data = [
                        "hrd_plan_id" => $hrd_plan_id,
                        "hrd_degree_id" => $hrd_degree_id,
                        'list_degree_local' => App\View_hrd_plan_degree::where('hrd_plan_division_id',$hrd_degree_id)->where('hrd_degree_type','Local')->where('division_id',Auth::user()->division)->orderBy('fullname')->get(),
                        'list_degree_foreign' => App\View_hrd_plan_degree::where('hrd_plan_division_id',$hrd_degree_id)->where('hrd_degree_type','Foreign')->where('division_id',Auth::user()->division)->orderBy('fullname')->get(),
                        'list_non_degree_local' => App\View_hrd_plan_non_degree::where('hrd_plan_division_id',$hrd_degree_id)->where('hrd_non_degree_type','Local')->where('division_id',Auth::user()->division)->orderBy('fullname')->get(),
                        'list_non_degree_foreign' => App\View_hrd_plan_non_degree::where('hrd_plan_division_id',$hrd_degree_id)->where('hrd_non_degree_type','Foreign')->where('division_id',Auth::user()->division)->orderBy('fullname')->get(),
                        
                    ];
            // return view('pis.learningdev.hrd-plan')->with('data',$data);
            return view('pis.learningdev.hrd-plan')->with('data',$data);
        }
        else
        {
            return redirect('letter-request');
        }
    }

    public function savehrddegree()
    {
        $hrd = new App\HRD_plan_degree;
        $hrd->hrd_plan_division_id = request()->hrd_degree_id;
        $hrd->hrd_plan_id = request()->hrd_plan_id;
        $hrd->division_id = Auth::user()->division;
        $hrd->user_id = request()->hrd_degree_staff;
        $hrd->hrd_degree_type = request()->hrd_degree_type;
        $hrd->hrd_degree_program = request()->hrd_degree_program;
        $hrd->hrd_degree_area = request()->hrd_degree_area;
        $hrd->hrd_degree_university = request()->hrd_degree_university;
        $hrd->hrd_degree_target = request()->hrd_degree_target;
        $hrd->hrd_degree_remarks = request()->hrd_degree_remarks;
        $hrd->save();
    }

    public function savehrdnondegree()
    {
        $hrd = new App\HRD_plan_non_degree;
        $hrd->hrd_plan_division_id = request()->hrd_degree_id;
        $hrd->hrd_plan_id = request()->hrd_plan_id;
        $hrd->user_id = request()->hrd_degree_staff;
        $hrd->hrd_non_degree_priority = request()->hrd_non_degree_priority;
        $hrd->hrd_non_degree_type = request()->hrd_degree_type;
        $hrd->hrd_non_degree_target_q1 = request()->hrd_non_degree_target_q1;
        $hrd->hrd_non_degree_target_q2 = request()->hrd_non_degree_target_q2;
        $hrd->hrd_non_degree_target_q3 = request()->hrd_non_degree_target_q3;
        $hrd->hrd_non_degree_target_q4 = request()->hrd_non_degree_target_q4;
        $hrd->save();
        $hrd_id = $hrd->id;

        foreach (request()->hrd_non_degree_areas as $value) {

            $areas = new App\HRD_plan_non_degree_area;
            $areas->hrd_plan_non_degrees_id = $hrd_id;
            $areas->areas_of_discipline =  $value;
            $areas->save();
        }

        // IF OTHERS
        if(isset(request()->hrd_non_degree_area_others))
        {
            $areas = new App\HRD_plan_non_degree_area;
            $areas->hrd_plan_non_degrees_id = $hrd_id;
            $areas->areas_of_discipline = request()->hrd_non_degree_area_others;
            $areas->save();
        }
    }

    public function updatehrddegree()
    {
        $hrd = App\HRD_plan_degree::where('id',request()->hrd_plan_degree_id)
                                ->update([
                                            'user_id' => request()->hrd_degree_staff,
                                            'hrd_degree_type' => request()->hrd_degree_type,
                                            'hrd_degree_program' => request()->hrd_degree_program,
                                            'hrd_degree_area' => request()->hrd_degree_area,
                                            'hrd_degree_university' => request()->hrd_degree_university,
                                            'hrd_degree_target' => request()->hrd_degree_target,
                                            'hrd_degree_remarks' => request()->hrd_degree_remarks,
                                        ]);
    }

    public function deletehrddegree()
    {
        $hrd = App\HRD_plan_degree::where('id',request()->hrd_plan_degree_id)->delete();
    }

    public function deletehrdnondegree()
    {
        $hrd = App\HRD_plan_non_degree::where('id',request()->hrd_plan_degree_id)->delete();

        $hrd = App\HRD_plan_non_degree_area::where('hrd_plan_non_degrees_id',request()->hrd_plan_degree_id)->delete();
    }

    public function jsonhrdplandegree($hrd_plan_id)
    {
        return json_encode(App\HRD_plan_degree::where('id',$hrd_plan_id)->where('division_id',Auth::user()->division)->get());
    }

    public function hrdsubmit()
    {
        App\HRD_plan_division::where('id',request()->hrd_degree_id)
                ->update([
                            'submitted_at' => Carbon::now()->toDateTimeString(),
                        ]);

        //UPDATE ALL STAFF OF THE DIVISION
        $staff = App\HRD_plan_staff::where('hrd_plan_division_id',request()->hrd_degree_id)
                ->update([
                            'submitted_at' => Carbon::now()->toDateTimeString(),
                        ]);
    }
}
