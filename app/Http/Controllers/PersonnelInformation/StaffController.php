<?php

namespace App\Http\Controllers\PersonnelInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use Auth;

class StaffController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index($tab,$subtab)
    {
        $data = [
                    "nav" => nav("myprofile"),
                    "active_tab" => $tab,
                    "active_subtab" => $subtab,
                    "empinfo" => App\User::where('users.id', Auth::user()->id)
                        ->join('employee_basicinfos', 'users.id', '=', 'employee_basicinfos.id')
                        ->first(['users.*', 'employee_basicinfos.*']),        
                    
                    "contact" => App\Employee_contact::where('user_id',Auth::user()->id)->first(),
                    "basicinfo" => App\Employee_basicinfo::where('user_id',Auth::user()->id)->first(),
                    "addinfo" => App\Employee_addinfo::where('user_id',Auth::user()->id)->first(),
                    "family" => App\Employee_family::where('user_id',Auth::user()->id)->first(),
                    "education" => App\Employee_education::where('user_id',Auth::user()->id)->get(),
                    "add_permanent" => App\Employee_address_permanent::where('user_id',Auth::user()->id)->first(),
                    "add_residential" => App\Employee_address_residential::where('user_id',Auth::user()->id)->first(),
                    "organization" => App\Employee_organization::where('user_id',Auth::user()->id)->get(),
                    "work" => App\Employee_work::where('user_id',Auth::user()->id)->orderBy('workexp_date_from','desc')->get(),
                    "work_agency" => App\View_employee_position::where('id',Auth::user()->id)->orderBy('plantilla_date_from','desc')->get(),
                    "eligibility" => App\Employee_eligibility::where('user_id',Auth::user()->id)->get(),
                    "skill" => App\Employee_skill::where('user_id',Auth::user()->id)->get(),
                    "competency" => App\Employee_competency::where('user_id',Auth::user()->id)->get(),
                    "competency_duty" => App\Employee_competencies_duty::where('user_id',Auth::user()->id)->get(),
                    "competency_training" => App\Employee_competencies_training::where('user_id',Auth::user()->id)->get(),
                    "recognition" => App\Employee_nonacademic::where('user_id',Auth::user()->id)->get(),
                    "association" => App\Employee_association::where('user_id',Auth::user()->id)->get(),
                    "reference" => App\Employee_reference::where('user_id',Auth::user()->id)->get(),
                    'training' => App\Employee_training::where('user_id',Auth::user()->id)->orderBy('training_inclusive_dates','desc')->get(),
                    "total_training_hours" => App\Employee_training::where('user_id',Auth::user()->id)->selectRaw('sum(training_hours) as sum')->pluck('sum'),
                    "total_training_amount" => App\Employee_training::where('user_id',Auth::user()->id)->selectRaw('sum(training_amount) as sum')->pluck('sum'),
                    "ipcr" => App\View_performance_group_dpcr_ipcr::where('user_id',Auth::user()->id)->whereNotNull('ipcr_submitted_at')->get(),
                    "file" => App\Employee_file::where('user_id',Auth::user()->id)->get(),
                    "child" => App\Employee_children::where('user_id',Auth::user()->id)->get(),
                    "cases" => App\Employee_case::where('user_id',Auth::user()->id)->get(),
                ];

        return view('pis.staff.myinfo')->with("data",$data);
    }

    public function invitation()
    {
        $data = [
                    "nav" => nav("invitation"),
                ];
        return view('pis.staff.invitation')->with("data",$data);
    }

    public function invitationanswer()
    {
        if(request()->invitation_answer == 'Delete')
        {
            App\Invitation::where('id',request()->invitation_id)->delete();
        }
        else
        {
            App\Invitation::where('id',request()->invitation_id)
                        ->update([
                                    'interested' => request()->invitation_answer
                                ]);  
        }
        
    }

    public function invitationalert()
    {
        $inv = App\Invitation::where('user_id',Auth::user()->id)->whereIn('interested',['','Yes'])->count();

        $arr = ['total' => $inv];
        return json_encode($arr);
    }

    public function attendance($mon,$yr,$userid)
    {
        $user = App\User::where('users.id', $userid)
        ->join('divisions', 'users.division', '=', 'divisions.division_id')
        ->first(['users.*', 'divisions.*']);

        $data = [
                    "nav" => nav("monitor"),
                    "mon" => $mon,
                    "yr" => $yr,
                    "userid" => $userid,
                    "username" => $user['username'],
                    'division_desc' => $user['division_desc'],
                    "username" => $user['username'],
                    "emp" => $user['username'],
                ];

        if(Auth::user()->usertype == 'Marshal' || Auth::user()->usertype == 'Administrator')
        {
            return view('staff.attendance-marshal')->with("data",$data);
        }
        else
        {
            return view('staff.attendance')->with("data",$data);
        }
        
    }

    public function leave()
    {
        return view('staff.leave')->with('staffid',null);
    }

    public function leave2($id)
    {
        return view('staff.leave')->with('staffid',$id);
    }

    public function leavetest($id)
    {
        return view('staff.leave-test')->with('staffid',null);
    }
    public function leave22($id)
    {
        return view('staff.leave-test')->with('staffid',$id);
    }

    public function to()
    {
        return view('staff.to');
    }

    public function cto()
    {
        return view('staff.cto');
    }

    public function payroll()
    {
        $mcs = App\View_employee_mc::where('user_id',Auth::user()->id)->get();

        $data = [
                    "bonus" => App\View_employee_bonus::where('user_id',Auth::user()->id)->get(),
                    "mc" => $mcs
                ];
                
        return view('staff.payroll')->with('data',$data);
    }

    public function loan()
    {
        return view('staff.loan');
    }


    public function weeksched($mon,$yr)
    {
        $data = [
                    "mon" => $mon,
                    "yr" => $yr
                ];
        return view('staff.weeksched')->with("data",$data);
    }

    public function weekschedsend()
    {
        if(request()->schedid == 0)
        {

            if(request()->sched_edit_status == 'WFH')
                        {
                            if(Auth::user()->employment_id == 8 || Auth::user()->employment_id == 5)
                                {
                                    $dtr_new = new App\Employee_icos_dtr;
                                    $tbl = 'employee_icos_dtrs';
                                }
                                else
                                {
                                    $dtr_new = new App\Employee_dtr;
                                    $tbl = 'employee_dtrs';
                                }

                            $dtr_new->user_id = Auth::user()->id;
                            $dtr_new->fldEmpCode = Auth::user()->username;
                            $dtr_new->division = Auth::user()->division;
                            $dtr_new->fldEmpDTRdate = request()->sched_date;
                            $dtr_new->employee_name = Auth::user()->lname.", ".Auth::user()->fname." ".Auth::user()->nname;
                            $dtr_new->fldEmpDTRamIn = '8:00:00';
                            $dtr_new->fldEmpDTRamOut = '12:30:00';
                            $dtr_new->fldEmpDTRpmIn = '13:00:00';
                            $dtr_new->fldEmpDTRpmOut = '17:00:00';
                            $dtr_new->wfh = 'Wholeday';
                            $dtr_new->request_id = 16;
                            $dtr_new->dtr_option_id = getDTROption();
                            $dtr_new->save();
                            $dtr_new_id = $dtr_new->id;
                        }

            $week = new App\WeekSchedule;
            $week->userid = Auth::user()->id;
            // $week->sched_dtr = $tbl."|".$dtr_new_id;
            $week->sched_date = request()->sched_date;
            $week->sched_status = request()->sched_edit_status;
            $week->created_by = Auth::user()->fname." ".Auth::user()->lname;
            $week->save();        
        }
        else
        {
            //GET IF WFH SA UNA
            if(Auth::user()->employment_id == 8 || Auth::user()->employment_id == 5)
                {
                    $dtr_check = App\Employee_icos_dtr::where('user_id',Auth::user()->id)->where('fldEmpDTRdate',request()->sched_date)->first();
                    $dtr_update = new App\Employee_icos_dtr;
                }
                else
                {
                    $dtr_check = App\Employee_dtr::where('user_id',Auth::user()->id)->where('fldEmpDTRdate',request()->sched_date)->first();
                    $dtr_update = new App\Employee_dtr;
                }
                
            if(isset($dtr_check))
            {
               if(request()->sched_edit_status == 'Remove')
               {
                    $dtr_update::where('id',$dtr_check['id'])->delete();
                    App\WeekSchedule::where('id',request()->schedid)->delete();
               }
               else
               {
                    if($dtr_check['wfh'] != null)
                    {
                        if(request()->sched_edit_status != 'WFH')
                        {
                            $dtr_update::where('id',$dtr_check['id'])->delete();
                            $sched = App\WeekSchedule::where('id',request()->schedid)
                                        ->update([
                                                    'sched_status' => request()->sched_edit_status
                                                ]);
                        }
                    }
               }
               
            }
            else
            {
                $sched = App\WeekSchedule::where('id',request()->schedid)
                                    ->update([
                                                'sched_status' => request()->sched_edit_status
                                            ]);
                                    
                $dtr_update->user_id = Auth::user()->id;
                $dtr_update->fldEmpCode = Auth::user()->username;
                $dtr_update->division = Auth::user()->division;
                $dtr_update->fldEmpDTRdate = request()->sched_date;
                $dtr_update->employee_name = Auth::user()->lname.", ".Auth::user()->fname." ".Auth::user()->nname;
                $dtr_update->fldEmpDTRamIn = '8:00:00';
                $dtr_update->fldEmpDTRamOut = '12:30:00';
                $dtr_update->fldEmpDTRpmIn = '13:00:00';
                $dtr_update->fldEmpDTRpmOut = '17:00:00';
                $dtr_update->wfh = 'Wholeday';
                $dtr_update->request_id = 16;
                $dtr_update->dtr_option_id = getDTROption();
                $dtr_update->save();
            }
            
        }

        return redirect('update-weekly-schedule/'.request()->mon.'/'.request()->yr);
    }

    public function updadetails()
    {
        App\User::where('id',Auth::user()->id)
            ->update([
                        'pickup' => request()->pickup,
                        'cellnum' => request()->cellnum
                    ]); 

        return redirect('update-weekly-schedule/'.request()->mon2.'/'.request()->yr2);
    }

    public function ctostaff($userid)
    {
        $cto = App\Employee_cto::where('user_id',$userid)->orderBy('created_at','DESC')->first();

        $pending = getPending(5,$userid);

        $proj = $cto['cto_bal'] - $pending;

        $cto = ["balance" => $cto['cto_bal'], "pending" => $pending, "projected" => $proj];

        return json_encode($cto);
    }

    public function cospayrollpage()
    {
        $data = [
                    "nav" => nav("icospayroll"),
                ];
        return view('payroll.cospayrollpage')->with("data",$data);
    }
}
